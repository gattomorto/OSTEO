import pandas as pd
import numpy as np
import re
from nltk.stem.snowball import SnowballStemmer
from nltk.corpus import stopwords
from nltk.tokenize import RegexpTokenizer
# nltk.download('stopwords')
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.preprocessing import MultiLabelBinarizer
from sqlalchemy import create_engine
from sqlalchemy import text
import json
from collections import Counter
import operator
from abc import ABC, abstractmethod
# TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA(beginning)/TERAPIE_ORMONALI_LISTA(class)/ORM.SOST./C.O.(site)
ter_orm_kinds = ['tibolone', 'estradiolo + drospirenone', 'tsec, estrogeni coniugati equini 0,4 mg- bazedoxifene 20 mg', 'terapia ormonale sostitutiva per via transdermica']
# TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA(beginnig)/TERAPIE_OSTEOPROTETTIVE_LISTA(class)/OSTEOPROTETTIVA SPECIFICA(site)
ter_osteo_kinds = ['alendronato', 'risedronato', 'ibandronato', 'clodronato', 'raloxifene', 'bazedoxifene', 'denosumab', 'teriparatide', 'zoledronato']
# VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA(beginning)/VITAMINA_D_SUPPLEMENTAZIONE_LISTA(class)/VITAMINA D SUPPLEMENTAZIONE(site)
vit_d_sup_kinds = ['colecalciferolo 300.000ui', 'colecalciferolo 100.000ui', 'colecalciferolo 25.000ui', 'colecalciferolo 10.000ui', 'calcifediolo', 'supplementazione giornaliera di vit d3 (colecalciferolo) a dose  2000ui /die']

# VITAMINA_D_TERAPIA_LISTA(class)/VITAMINA D TERAPIA(site)
vit_d_ter_kinds = ['colecalciferolo', 'calcifediolo']
# CALCIO_SUPPLEMENTAZIONE_LISTA(class)/CALCIO SUPPLEMENTAZIONE(site)
calcio_supp_kinds = ['calcio citrato', 'calcio carbonato']

commons_PATOLOGIE_UTERINE_DIAGNOSI = ['isterectomia', 'fibromi', 'cisti', 'endometriosi']
commons_ALTRE_PATOLOGIE = ['vit d', 'eutirox']
commons_NEOPLASIA_MAMMARIA_TERAPIA = ['quadrantectomia/mastectomia', 'radioterapia', 'tamoxifene', 'anastrozolo', 'chemioterapia', 'letrozolo']
commons_DISLIPIDEMIA_TERAPIA = ['statine', 'integratori', 'dieta', 'ezetimibe']
commons_INTOLLERANZE = ['lattosio', 'bisfosfonati', 'alendronato', 'glutine', 'clodronato', 'risedronato', 'tos', 'colecalciferolo']
commons_ALLERGIE = ['nichel', 'asa', 'penicillina', 'fans', 'acetilsalicilico', 'paracetamolo', 'graminacee', 'augmentin', 'lattosio']
commons_TERAPIA_ALTRO = ['alendronato', 'vit d', '100.000ui','2.000ui','tos', 'natecal', 'clodronato', 'prolia', 'colecalciferolo', 'dxa', 'dibase', 'forsteo', 'fosavance', 'risedronato', '10.000ui', 'bisfosfonati', '25.000ui']
CAUSE_OSTEOPOROSI_SECONDARIA_kinds = ['diabete-insulino dipendente', 'osteogenesi imperfecta', 'ipertiroidismo non trattato per lungo tempo', 'ipogonadismo', 'menopausa prematura', 'malnutrizione cronica', 'm.i.c.i.', 'malattia cronica epatica come cirrosi/epatite cronica']

binary_columns = [
            'TERAPIA_OSTEOPROTETTIVA_ORMONALE',  # checkbox
            'TERAPIA_OSTEOPROTETTIVA_SPECIFICA',  # ceckbox
            'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA',  # NON lo riconosce come nominal**
            'TERAPIA_ALTRO_CHECKBOX',  # NON lo riconosce come nominal attenzione ci sono dei null**
            'TERAPIA_COMPLIANCE',  # NON lo riconosce come nominal**
            'FRATTURA_SITI_DIVERSI',  # NON lo riconosce come nominal**
            'FRATTURA_FAMILIARITA',  # NON lo riconosce come nominal**
            'MALATTIE_ATTUALI_CHECKBOX',  # NON**
            'MALATTIE_ATTUALI_ARTRITE_REUM',  # NON**
            'MALATTIE_ATTUALI_ARTRITE_PSOR',  # NON**
            'MALATTIE_ATTUALI_LUPUS',  # NON**
            'MALATTIE_ATTUALI_SCLERODERMIA',  # NON**
            'MALATTIE_ATTUALI_ALTRE_CONNETTIVITI',  # NON**
            'CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX',  # NON***
            'PATOLOGIE_UTERINE_CHECKBOX',  # NON
            'NEOPLASIA_CHECKBOX',  # NON
            'SINTOMI_VASOMOTORI',  # NON
            'SINTOMI_DISTROFICI',  # NON
            'DISLIPIDEMIA_CHECKBOX',  # NON
            'IPERTENSIONE',  # NON
            'RISCHIO_TEV',  # NON
            'PATOLOGIA_CARDIACA',  # NON
            'PATOLOGIA_VASCOLARE',  # NON
            'INSUFFICIENZA_RENALE',  # NON
            'PATOLOGIA_RESPIRATORIA',  # NON
            'PATOLOGIA_CAVO_ORALE_CHECKBOX',  # NON
            'PATOLOGIA_EPATICA',  # NON
            'PATOLOGIA_ESOFAGEA',  # NON
            'GASTRO_DUODENITE',  # NON
            'GASTRO_RESEZIONE',  # NON
            'RESEZIONE_INTESTINALE',  # NON
            'MICI',
            'VITAMINA_D_CHECKBOX',  # NON
            'ALLERGIE_CHECKBOX',  # NON + quella sotto
            'INTOLLERANZE_CHECKBOX',
            'OSTEOPOROSI_GRAVE',  # NON
            'VERTEBRE_NON_ANALIZZATE_CHECKBOX',  # NON
            'VERTEBRE_NON_ANALIZZATE_L1',  # NON
            'VERTEBRE_NON_ANALIZZATE_L2',  # NON
            'VERTEBRE_NON_ANALIZZATE_L3',  # NON
            'VERTEBRE_NON_ANALIZZATE_L4',  # NON
            'COLONNA_NON_ANALIZZABILE',  # NON
            'COLONNA_VALORI_SUPERIORI',  # NON
            'FEMORE_NON_ANALIZZABILE',  # NON
            'FRAX_APPLICABILE',  # NON**
            'TBS_COLONNA_APPLICABILE',  # NON**
            'DEFRA_APPLICABILE',]
numeric_columns = [
    'PATIENT_KEY',
    'SCAN_DATE',
    'AGE',  # OK
    'ETA_MENOPAUSA',
    'ANNI_DALLA_MENOPAUSA',
    'BMI',  # OK
    'VITAMINA_D',  # ok
    'FRAX_FRATTURE_MAGGIORI_INTERO',
    'FRAX_COLLO_FEMORE_INTERO',
    'TBS_COLONNA_VALORE',
    'DEFRA_INTERO',
    'TOT_Tscore',
    'TOT_Zscore']
class_columns = [

                'TERAPIE_ORMONALI_CHECKBOX',  # [0]
                'TERAPIE_ORMONALI_LISTA',  # [1]
                'TERAPIE_OSTEOPROTETTIVE_CHECKBOX',  # [2]
                'TERAPIE_OSTEOPROTETTIVE_LISTA',  # [3]
                'VITAMINA_D_TERAPIA_CHECKBOX',  # [4]
                'VITAMINA_D_TERAPIA_LISTA',  # [5]
                'VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',  # [6]
                'VITAMINA_D_SUPPLEMENTAZIONE_LISTA',  # [7]
                'CALCIO_SUPPLEMENTAZIONE_CHECKBOX',  # [8]
                'CALCIO_SUPPLEMENTAZIONE_LISTA'
            ]
nominal_columns = [
                'STATO_MENOPAUSALE',  # OK weka trasforma in nominal
                'TERAPIA_STATO',  # OK
                'FRATTURA_VERTEBRE',  # ok trasformato in nominal {no fratture, 1, piu di 1}
                'FRATTURA_FEMORE',  # ok trasformato in nominal {no fratture, 1, piu di 1}
                'ABUSO_FUMO',  # ok  tengo cosi com'è .. al posto di null metto 'non fuma'
                'USO_CORTISONE',  # ok trasformato in nominal
                'SITUAZIONE_COLONNA',  # ok
                'SITUAZIONE_FEMORE_SN',  # ok
                'SITUAZIONE_FEMORE_DX']
one_hot_encoded_columns = []


# todo: su intolleranze dove ce scritto nulla bisogna metttere intolleranze checko = 0
#       controlla che non ci sia piu '>= 5 mg (Prednisone)'
def df_column_uniquify(df):
    df_columns = df.columns
    new_columns = []
    for item in df_columns:
        counter = 0
        newitem = item
        while newitem in new_columns:
            counter += 1
            newitem = "{}_{}".format(item, counter)
        new_columns.append(newitem)
    df.columns = new_columns
    return df

def remove_stopwords_and_stem(sentence, regex= r'[a-z]{2,}|100000|2000|25000|10000'):
    # TODO: 10.000ui li trasforma in 10.000u
    '''
    Data una stringa contenete una frase ritorna una stringa con parole in forma radicale e senza rumore
    es:
    sentence: ha assunto alendronato per 2 anni
    regex: voglio solo parole
    returns: assunt alendronato
    '''

    if 'mici' in sentence:
        i=44

    tokenizer = RegexpTokenizer(regex)
    # crea una lista di tutti i match del regex
    tokens = tokenizer.tokenize(sentence)
    # tokens = [x.lower() for x in tokens]

    # libreria nltk
    stop_words = stopwords.words('italian')
    # 'non' è molto importante
    stop_words.remove('non')
    stop_words += ['.', ',', 'gg', 'die', 'fa', 'im', 'fino', 'uno', 'due', 'tre', 'quattro', 'cinque',
                   'sei', 'ogni',
                   'alcuni', 'giorni', 'giorno', 'mesi', 'mese', 'settimana', 'settimane', 'circa', 'aa', 'gtt',
                   'poi', 'gennaio', 'febbraio', 'marzo', 'maggio', 'aprile', 'giugno', 'luglio', 'agosto',
                   'settembre', 'ottobre', 'novembre', 'dicembre', 'anno', 'anni', 'sett', 'pu', 'dx', 'sn',
                   'nel']

    # trovo tutti i token da eliminare dalla frase
    to_be_removed = []
    for token in tokens:
        if token in stop_words:
            to_be_removed.append(token)

    # rimuovo i token dalla frase
    for elem in to_be_removed:
        if elem in tokens:
            tokens.remove(elem)

    # print(tokens)
    # la parte di stemming
    stemmer = SnowballStemmer("italian")

    # solo questa è la parte che è diversa
    stemmed_to_original_locale = {}
    for t in tokens:
        stemmed_to_original_locale[stemmer.stem(t)] = t

    tokens = [stemmer.stem(tok) for tok in tokens]

    # converto da lista di token in striga
    output = ' '.join(tokens)

    '''if output != '':
        print(sentence)
        print(output+'\n')'''
    return output, stemmed_to_original_locale

def preprocess(instances, is_single_instance):
    def prova():
        words = []
        tokenizer = RegexpTokenizer(r'[a-z]+|100000|2000|25000|10000|100.000|2.000|25.000|10.000')
        for _, item in instances['INTOLLERANZE'].iteritems():
            t =  tokenizer.tokenize(item.lower())
            if 'tollerato' in t:
                print(item)
            words += t

        # lascio iu, assunto.. ma devi fare solo bigrammi
        stop_words = stopwords.words('italian')

        to_be_removed = []
        for token in words:
            if token in stop_words:
                to_be_removed.append(token)

        for elem in to_be_removed:
            if elem in words:
                words.remove(elem)
        counts = Counter(words)

        print(counts)

        '''for _, item in instances['TERAPIA_ALTRO'].iteritems():
            if 'D' in item:
                print(item)'''



        exit()

    global stemmed_to_original
    stemmed_to_original = {}
    global col_name_to_ngram
    col_name_to_ngram = {}

    def vectorize(column_name, frame, prefix, n_gram_range=(2, 2)):

        # TODO: ha detto all'inizio di rimuovere gli grammi che compaiono poco
        '''
        ATTENZIONE: se scegli solo bigrammi il vettore delle frasi diverse di una sola parola sarà nullo
                    mi sono accorto perche 'na' e 'calciferolo' trattato uguale

        dato il nome di una colonna contenente testo, per ogni riga crea una rappresentazione
        vettoriale senza stopwords e stemmed (vedi remove_stopwords_and_stem())
        dopo aver visto tutte le righe, avremo un vettore per ogni riga, cioè una matrice
        questa verrà integrato al dataframe in input.

        :param column_name: nome colonna da vettorizzare
        :param frame: dataframe
        :param prefix: per differenziare i nomi delle colonne in output
        :param regex: come dividere i token (cosa sarà un gramma) (il regex di default prende solo parole+numeri
        importanti di 10.000UI). Di default trova solo parole. C'è il punto per la parola 'M.I.C.I'
        no 2000 perchè ci confondiamo con l'anno
        :param n_gram_range: (2,2) se voglio solo bigrammi, (1,2) se voglio bigrammi e monogrammi...
        :return: la tabella con le nuove colonne e i nomi delle nuove colonne
        '''
        # lista di frasi
        column_list = frame[column_name].tolist()


        # lista di frasi tutte in minuscolo
        column_list = [str(cell).lower() for cell in column_list]


        global stemmed_to_original
        for i in range(0, len(column_list)):
            column_list[i], stemmed_to_original_locale = remove_stopwords_and_stem(column_list[i])
            stemmed_to_original = {**stemmed_to_original, **stemmed_to_original_locale}

        # non idf = false perchè voglio un output binario.
        # binario perchè voglio poter dire: consiglio TERAPIE_ORMONALI perchè è presente (non è presente) la parola 'p'
        vectorizer = TfidfVectorizer(ngram_range=n_gram_range, norm=None, use_idf=False)

        # in vectorized_matrix ogni riga è un vettore corrispondente ad una frase
        vectorized_matrix = vectorizer.fit_transform(column_list).toarray()
        # servono per filtrare instances. il suffisso serve perchè cosi se viene chiamata la funzione piu volte,
        # non si confonde i nomi delle colonne... perchè vectorized ha le colonne numerate da 0 a n
        # nomi_nuove_colonne_vectorized sarà = [prefix0, prefix1, ... , prefixn]
        nomi_nuove_colonne_vectorized = [prefix + str(i) for i in range(0, vectorized_matrix.shape[1])]
        # converto in DataFrame perchè devo accostarlo alla instances
        vectorized_frame = pd.DataFrame(vectorized_matrix, columns=nomi_nuove_colonne_vectorized)
        frame = pd.concat([frame, vectorized_frame], axis=1)

        # todo: commenti
        global col_name_to_ngram
        ngrams = vectorizer.get_feature_names()
        # da nome colonna a ngramma
        col_name_to_ngram = {**col_name_to_ngram, **dict(zip(nomi_nuove_colonne_vectorized, ngrams))}
        # da tag a nome della colonna ogriginale
        col_name_to_ngram[prefix] = column_name

        return frame, nomi_nuove_colonne_vectorized

    def one_hot_encode(instances, col_name, classes, sep = '\n'):
        # x is a list of tuples, each tuple contains classes present in some row of the column
        x = []
        # item is some row of the column
        for i ,item in instances[col_name].iteritems():
            #print('{}:{}'.format(i,item))
            # the tuple for the current row
            t = ()
            if item != '':
                # bear in mind that a row may contain multiple classes. For example the TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA column
                # might contain 'ibandronato capsule\nRaloxifene 20mg'
                # first of all we split the sentences and get: ['ibandronato capsule', 'Raloxifene 20mg']
                # for each sentence:
                for line in item.split(sep):
                    line = line.lower()
                    # each sentence should be assigned to some class, if not, something's not working
                    line_assigned = False
                    # we check if the sentence contains some class
                    for cl in classes:
                        # each sentence should contain one and only one class, so the it should fire once
                        if cl in line:
                            # add the class to the tuple
                            t = t + (cl,)
                            line_assigned = True
                    if line_assigned is False:
                        print('err')
                        exit(-2)
                # t for this example is ('ibandronato','raloxifene')
                x.append(t)
            else:
                x.append(t)
            '''
            print(i)
            print(item)
            print(t)
            '''

        # one column for each class
        new_columns = MultiLabelBinarizer(classes = classes).fit_transform(x)
        new_columns_names = ['{}_{}'.format(col_name,i) for i in range(0,len(classes)) ]
        new_columns= pd.DataFrame(new_columns, columns=new_columns_names)
        instances = pd.concat([instances, new_columns], axis=1)
        return instances, new_columns_names

    def one_hot_encode2(instances, col_name, classes, sep):
        tokenizer = RegexpTokenizer(sep)
        # x is a list of tuples, each tuple contains classes present in some row of the column
        x = []
        # item is some row of the column
        for i ,item in instances[col_name].iteritems():
            #print('{}:{}'.format(i,item))
            # the tuple for the current row
            t = ()
            item = item.lower()
            if item != '':
                for token in tokenizer.tokenize(item):
                    # we check if the sentence contains some class
                    for cl in classes:
                        # each sentence should contain one and only one class, so the if shoul fire once
                        if cl == token:
                            # add the class to the tuple
                            t = t + (cl,)
                # t for this example is ('ibandronato','raloxifene')
                x.append(t)
            else:
                x.append(t)


            '''print(i)
            print(item)
            print(t)'''


        # one column for each class
        new_columns = MultiLabelBinarizer(classes = classes).fit_transform(x)
        new_columns_names = ['{}_{}'.format(col_name,i) for i in range(0,len(classes)) ]
        new_columns= pd.DataFrame(new_columns, columns=new_columns_names)
        instances = pd.concat([instances, new_columns], axis=1)
        return instances, new_columns_names

    db_connection_str = 'mysql+pymysql://utente_web:CMOREL96T45@localhost/CMO2'
    db_connection = create_engine(db_connection_str)
    instances.reset_index(drop=True, inplace=True)
    # per il doppio bmi
    instances = df_column_uniquify(instances)
    instances.rename(columns={'PAROLOGIA_ESOFAGEA': 'PATOLOGIA_ESOFAGEA'}, inplace=True)
    instances.replace('NULL', value='', inplace=True)
    instances.replace(r"'", value='', inplace=True, regex=True)
    #prova()
    # some text has uncomfortable values for regular expressions, such as: <,=,>,(,...
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'ABUSO_FUMO'] = re.sub('> 10 sigarette/di', 'piu di 10 sigarette', instances.loc[row_index, 'ABUSO_FUMO'])
        instances.loc[row_index, 'ABUSO_FUMO'] = re.sub('<= 10 sigarette/di', 'meno di 10 sigarette', instances.loc[row_index, 'ABUSO_FUMO'])
        instances.loc[row_index, 'USO_CORTISONE'] = re.sub(r'>=\s5\smg\s\(Prednisone\)', 'piu di 5 mg', instances.loc[row_index, 'USO_CORTISONE'])
        instances.loc[row_index, 'USO_CORTISONE'] = re.sub('> 2.5 mg e < 5 mg', 'tra 2.5 e 5 mg', instances.loc[row_index, 'USO_CORTISONE'])


    # region one hot encoding TERAPIA_ALTRO
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'10000', '10.000', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'25000', '25.000', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'100000', '100.000', instances.loc[row_index, 'TERAPIA_ALTRO'])
        # no 2000 on purpose, bc it can be mistaken for year 2000

        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'10\.000', '10.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'25\.000', '25.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'100\.000', '100.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'2\.000', '2.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])


        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VITAMINA\sD', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VIT\.\sD', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VIT\.D', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VITD', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
    instances, new_column_names_for_TERAPIA_ALTRO = one_hot_encode2(instances, 'TERAPIA_ALTRO', commons_TERAPIA_ALTRO, sep=r'vit\sd|[a-z]+|100.000ui|2.000ui|25.000ui|10.000ui')
    one_hot_encoded_columns.extend(new_column_names_for_TERAPIA_ALTRO)
    # endregion
    # region one hot encoding ALTRE_PATOLOGIE
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'ALTRE_PATOLOGIE'] = re.sub(r'VITD', 'VIT D', instances.loc[row_index, 'ALTRE_PATOLOGIE'])
        instances.loc[row_index, 'ALTRE_PATOLOGIE'] = re.sub(r'VITAMINA D', 'VIT D ', instances.loc[row_index, 'ALTRE_PATOLOGIE'])

    instances, new_column_names_for_ALTRE_PATOLOGIE = one_hot_encode2(instances,'ALTRE_PATOLOGIE', commons_ALTRE_PATOLOGIE,sep=r'vit\sd|eutirox')
    one_hot_encoded_columns.extend(new_column_names_for_ALTRE_PATOLOGIE)

    # endregion

    # region one hot encoding NEOPLASIA_MAMMARIA_TERAPIA
    instances['NEOPLASIA_MAMMARIA_TERAPIA'] = instances['NEOPLASIA_MAMMARIA_TERAPIA'].str.lower()
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = re.sub(r'radiotp', 'radioterapia', instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'])
        instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = re.sub(r'chemiotp', 'chemioterapia', instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'])
        instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = re.sub(r'anastrazolo', 'anastrozolo', instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'])

        x = instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA']
        # quadrantectomia and mastectomia are basically the same thing
        if 'quadrantectomia' in x or 'mastectomia' in x:
            x += ' quadrantectomia/mastectomia'
            instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = x
    instances, new_column_names_for_NEOPLASIA_MAMMARIA_TERAPIA = one_hot_encode2(instances, 'NEOPLASIA_MAMMARIA_TERAPIA', commons_NEOPLASIA_MAMMARIA_TERAPIA, sep=r'[a-z/]+')
    one_hot_encoded_columns.extend(new_column_names_for_NEOPLASIA_MAMMARIA_TERAPIA)
    # endregion



    # region one hot encoding PATOLOGIE_UTERINE_DIAGNOSI
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ISTEROANNESSIECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ISTEROANNESSECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ISTEROENNESSIECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ANNESSIECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'FIBROMATOSO', 'FIBROMI', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'FIBROMATOSI', 'FIBROMI', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'FIBROMA', 'FIBROMI', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
    instances, new_column_names_for_PATOLOGIE_UTERINE_DIAGNOSI = one_hot_encode2(instances,'PATOLOGIE_UTERINE_DIAGNOSI', commons_PATOLOGIE_UTERINE_DIAGNOSI,sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_PATOLOGIE_UTERINE_DIAGNOSI)
    # endregion

    # region one hot encoding DISLIPIDEMIA_TERAPIA
    instances['DISLIPIDEMIA_TERAPIA'] = instances['DISLIPIDEMIA_TERAPIA'].str.lower()
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'] = re.sub(r'statina', 'statine', instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'])
        instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'] = re.sub(r'integratore', 'integratori', instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'])

    instances, new_column_names_for_DISLIPIDEMIA_TERAPIA = one_hot_encode2(instances, 'DISLIPIDEMIA_TERAPIA', commons_DISLIPIDEMIA_TERAPIA, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_DISLIPIDEMIA_TERAPIA)

    # endregion

    # region one hot encoding ALLERGIE
    instances['ALLERGIE'] = instances['ALLERGIE'].str.lower()
    for row_index in range(0, instances.shape[0]):
        # aspirin and asa are the same thing
        instances.loc[row_index, 'ALLERGIE'] = re.sub(r'aspirina', 'asa', instances.loc[row_index, 'ALLERGIE'])
        instances.loc[row_index, 'ALLERGIE'] = re.sub(r'penicilline', 'penicillina', instances.loc[row_index, 'ALLERGIE'])

        x = instances.loc[row_index, 'ALLERGIE']
        # aspirin(asa) is contained in the class of fans, so if someone is allergic to fans, is also allergic to aspirin(asa)
        if 'fans' in x:
            x += ' asa'
            instances.loc[row_index, 'ALLERGIE'] = x
    instances, new_column_names_for_ALLERGIE = one_hot_encode2(instances, 'ALLERGIE', commons_ALLERGIE, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_ALLERGIE)

    # endregion

    # region one hot encoding INTOLLERANZE
    instances['INTOLLERANZE'] = instances['INTOLLERANZE'].str.lower()
    instances, new_column_names_for_INTOLLERANZE = one_hot_encode2(instances, 'INTOLLERANZE', commons_INTOLLERANZE, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_INTOLLERANZE)

    # endregion


    # commento per il momento perchè devo fare riferimento a valori vecchi
    '''# Tengo solo quelli che sono venuti prima di ottobre
    instances = tabella_completa.loc[instances['SCAN_DATE'] <= '2019-10-01', :].copy()
    instances.reset_index(drop=True, inplace=True)'''
    '''
    many sentences taken from the html select list have the this form: \t\t\r\n\telement1\r\n\t\t\selement2\t\t\t\t\s\s
    we remove the stuff in front, in the back, and for some in the middle.
    '''
    # todo non so se importa rimuovere alle colonne da vettorizzare la roba
    back_front_junk_regex = r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$'
    middle_junk = r'[\r\n\s\t]*(\r\n)+[\r\n\s\t]*'
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'] = re.sub(back_front_junk_regex, '',instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub( back_front_junk_regex, '', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub( back_front_junk_regex, '', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])
        instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'] = re.sub(back_front_junk_regex, '',  instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'])
        instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_LISTA'] = re.sub(back_front_junk_regex,'', instances.loc[row_index, 'VITAMINA_D_TERAPIA_LISTA'])
        # removing the junk in middle and substituting it with \n
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub(middle_junk, '\n', instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub(middle_junk, '\n', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] = re.sub(middle_junk,'\n', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])

    # region one hot encoding CAUSE_OSTEOPOROSI_SECONDARIA
    instances, new_column_names_for_CAUSE_OSTEOPOROSI_SECONDARIA = one_hot_encode(instances, 'CAUSE_OSTEOPOROSI_SECONDARIA', CAUSE_OSTEOPOROSI_SECONDARIA_kinds, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_CAUSE_OSTEOPOROSI_SECONDARIA)

    # endregion

    # tolgo le informazioni sugli anni da TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA, TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
    # perche sono gia state salvate in un altra colonna
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub(  r',[0-9]+([,.][0-9]+)?\s*anni', '',instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub( r',[0-9]+([,.][0-9]+)?\s*anni', '', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])

    # sostituisco a 10000UI 10.000UI e 25000 UI con 25.000UI in VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA(beginning)/VITAMINA_D_SUPPLEMENTAZIONE_LISTA(class)
    # perchè non voglio avere robe diverse
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'10000', r'10.000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'25000', r'25.000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'100000', r'100.000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'\sUI', r'UI', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])

        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'10000', r'10.000', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'25000', r'25.000', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'100000', r'100.000', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'\sUI', r'UI', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])

    # region one hot encoding TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA, TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA, VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA
    instances, new_column_names_for_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = one_hot_encode(instances,'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA', ter_orm_kinds)
    instances, new_column_names_for_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA = one_hot_encode(instances,'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA', ter_osteo_kinds)
    instances, new_column_names_for_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA = one_hot_encode(instances,'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA', vit_d_sup_kinds)
    one_hot_encoded_columns.extend(new_column_names_for_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA)
    one_hot_encoded_columns.extend(new_column_names_for_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA)
    one_hot_encoded_columns.extend(new_column_names_for_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA)
    # endregion



    # creates a new column with the difference between the current year and last period year
    # all nans in ULTIMA_MESTRUAZIONE transfer to ANNI_SENZA_MESTRUAZIONI
    for row_index in range(0, instances.shape[0]):
        scan =  instances.loc[row_index, 'SCAN_DATE'].year
        um =  instances.loc[row_index, 'ULTIMA_MESTRUAZIONE']
        adm = scan - um
        instances.loc[row_index, 'ANNI_DALLA_MENOPAUSA'] = adm




    # questo paragrafo perchè voglio che 10000UI sia trattato come 10.000UI
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'10.000', r'10000',
                                                                  instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'100.000', r'100000',
                                                                  instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'2.000', r'2000',
                                                                  instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'25.000', r'25000',
                                                                  instances.loc[row_index, 'TERAPIA_ALTRO'])


    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'] = re.sub(r'M.I.C.I.', r'mici',
                                                                                 instances.loc[
                                                                                     row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'])

    # alcuni hanno -1
    instances['BMI'].replace(-1, np.nan, inplace=True)


    # region fillna
    instances['FRATTURA_VERTEBRE'].replace('', 'no fratture', inplace=True)
    instances['FRATTURA_FEMORE'].replace('','no fratture', inplace=True)
    instances['ABUSO_FUMO'].replace('','non fuma', inplace=True)
    instances['USO_CORTISONE'].replace('','non usa cortisone', inplace=True)
    instances['TERAPIA_ALTRO_CHECKBOX'].fillna(0, inplace=True)
    instances['STATO_MENOPAUSALE'].replace('', np.nan, inplace=True)
    instances['TERAPIA_STATO'].replace('', np.nan, inplace=True)
    instances['SITUAZIONE_FEMORE_SN'].replace('', np.nan, inplace=True)
    instances['SITUAZIONE_COLONNA'].replace('', np.nan, inplace=True)
    # classes
    instances['TERAPIE_ORMONALI_LISTA'].replace('', np.nan, inplace=True)
    instances['VITAMINA_D_TERAPIA_LISTA'].replace('', np.nan, inplace=True)
    instances['VITAMINA_D_SUPPLEMENTAZIONE_LISTA'].replace('', np.nan, inplace=True)
    instances['TERAPIE_OSTEOPROTETTIVE_LISTA'].replace('', np.nan, inplace=True)
    instances['CALCIO_SUPPLEMENTAZIONE_LISTA'].replace('', np.nan, inplace=True)



    # endregion

    if not is_single_instance:
        def class_preprocess(class_name, class_kinds):
            i = 0
            for row_index in range(0, instances.shape[0]):
                row = instances.loc[row_index, class_name]
                #print('{}:{}'.format(i, row))

                if not pd.isna(row):
                    row = row.lower()
                    row_assigned = False
                    for kind in class_kinds:
                        if kind in row:
                            instances.loc[row_index, class_name] = kind
                            #print('\t' + kind)
                            row_assigned = True
                            break

                    if row_assigned is False:
                        print('errrrr')
                        exit(-3)
                i = i + 1
        class_preprocess('CALCIO_SUPPLEMENTAZIONE_LISTA',calcio_supp_kinds)
        class_preprocess('TERAPIE_OSTEOPROTETTIVE_LISTA',ter_osteo_kinds)
        class_preprocess('VITAMINA_D_SUPPLEMENTAZIONE_LISTA',vit_d_sup_kinds)
        class_preprocess('VITAMINA_D_TERAPIA_LISTA',vit_d_ter_kinds)
        class_preprocess('TERAPIE_ORMONALI_LISTA',ter_orm_kinds)

        final_columns = numeric_columns +binary_columns + nominal_columns + one_hot_encoded_columns + class_columns

        return instances[final_columns], col_name_to_ngram, stemmed_to_original
    else:
        return instances.T.squeeze()

class Proposizione:
    '''
    Proposition for some rule.
    operando1 contains the name of some column
    operatore can be =,<,>,>=,<= for numeric and nominal datatypes, or 'contiene', 'non contiene' for text columns
    operando2 contains some value to check operando1 against to
    '''

    def __init__(self, prop_string):
        self.operatore =re.search(r'(?<=\s)(<=|>=|=|<|>|!=|contiene|(non\scontiene))(?=\s)', prop_string).group(0)
        self.operando1 = re.search(r'(^\w+(?=\s(=|<|>|<=|>=|!=|non contiene)\s))|(^\w+(?=\s(=|<|>|<=|>=|!=|contiene)\s))', prop_string).group(0)
        self.operando2 = re.search(r'((?<=([=><])\s)[\w\s.,+-]+$)|((?<=contiene\sle\sparole\s#)[\w\s.]+(?=#$))', prop_string).group(0)

    def valuta(self, istanza):
        '''
        checks wether some proposition is true or false, and returns in accordance
        '''
        def is_number(s):
            try:
                float(s)
                return True
            except ValueError:
                return False

        def is_sub(frase1, frase2):
            '''
            true if frase1 is a "subfrase" of frase2
            note that subfrase is different from substring (subfrase implies substring)

            examples:
                "hi how was y" is a substring of "hi how was your day" but not a subfrase
                "hi how was your" is a subfrase and substring of "hi how was your day"
                "hi was" is neither a substrig nor a subfrase
            '''
            f1_list = frase1.split(' ')
            f2_list = frase2.split(' ')
            for i2 in range(0, len(f2_list) - len(f1_list) + 1):
                if f2_list[i2] == f1_list[0]:
                    i2c = i2
                    for i1 in range(0, len(f1_list)):
                        if f2_list[i2c] != f1_list[i1]:
                            break
                        if i1 == len(f1_list) - 1:
                            return True
                        i2c += 1

            return False

        # operando1 now contains the value of a column, not the column name
        operando1 = istanza[self.operando1]
        operando2 = self.operando2
        operatore = self.operatore

        # numeric and nominal case
        if 'contiene' not in operatore:

            # if a missing value is present, the proposision is false
            if pd.isna(operando1):
                return False

            if not is_number(operando1) or not is_number(operando2):
                operando1 = re.sub("'", '', operando1)
                operando2 = re.sub("'", '', operando2)
                operando1 = "'{}'".format(operando1)
                operando2 = "'{}'".format(operando2)
            else:
                operando1 = float(operando1)
                operando2 = float(operando2)
                operando1 = str(operando1)
                operando2 = str(operando2)

            if operatore == '=':
                operatore = '=='

            # some examples of what s might be
            # 1.0 >= 0.0
            # 'non fuma' == '>10 sigarette'
            s = operando1 + operatore + operando2

            if eval(s) is True:
                return True
            else:
                return False

        # text case
        else:
            operando1 = operando1.lower()
            x, _ = remove_stopwords_and_stem(operando1)
            if is_sub(operando2, x):
                if operatore == 'contiene':
                    return True
                elif operatore == 'non contiene':
                    return False
            else:
                if operatore == 'non contiene':
                    return True
                elif operatore == 'contiene':
                    return False

    def __str__(self):
        if self.operatore == ':':
            return self.operando1 + "" + self.operatore + " " + self.operando2
        else:
            return self.operando1 + " " + self.operatore + " " + self.operando2

    def __eq__(self, other_prop):
        return self.operando1 == other_prop.operando1 and self.operatore == other_prop.operatore and self.operando2 == other_prop.operando2

class Regola:
    '''
    A PART rule is just a list of proposition plus presision (m/n) and prediction.

    The last rule of every PART decision list is an "else rule" with no propositions, a rule which is always true.
    For us this "else rule" is one with self.propositions = None
    '''

    def __init__(self):
        self.m = None
        self.n = None
        self.prediction = None
        self.propositions = []



    def add_proposision(self, prop):
        self.propositions.append(prop)


    def valuta(self, istanza):
        '''
        Given an instans of type 'Series', returns true if it satisfies the rule, false otherwise
        '''

        # list is empty
        if self.propositions==[]:
            return True, None
        # for a rule to be true it needs to have all its propositions true
        for prop in self.propositions:
            # if at least one proposition is False, then the whole rule is false
            if prop.valuta(istanza) is False:
                return False, prop
        return True, None

    def __str__(self):
        if self.propositions==[]:
            output = ""
            for prop in self.propositions:
                if prop != self.propositions[-1]:
                    output += str(prop) + " AND\n"
                else:
                    output += str(prop) + ": " + self.prediction + " (" + str(self.m) + "/" + str(self.n) + ")"
                    return output
        else:
            return ": " + self.prediction + " (" + str(self.m) + "/" + str(self.n) + ")"

    def get_medic_readable_version(self, instance, props_not_satisfied):
        conv = {'AGE':"ETA'"}

        '''
        We read from the db a generic rule. Like this one for example:

        1 SITUAZIONE_FEMORE_SN = Situazione di normalita AND
        1 ALTRE_PATOLOGIE non contiene le parole "cataratt oper" AND (138.59/3.0)

        We cannot deliver such a rule to the eyes of the medic. The issue, among other things, is
        "cataratt oper". This is not very nice, this should have been "cataratte operazioni" or
        "cataratta operata".

        stemm_dict: what happens when the proposition is: 'X does not contain "(some stemmed word)"' and X
        in fact does not contain it. How do I retrieve the original word? There is no way! the stemming
        function is not injective, so it has (no inverse)/(multiple inverses).
        The route I chose is to show a random inverse.
        The inverses are stored in 'stemm_dict', a dictionary which allows, given a key (stemmed) to
        retrieve some random word that generated the the key

        '''

        output = ''
        # the else rule
        if self.propositions is None:
            return 'None'

        for prop in self.propositions:
            new_perando1 = prop.operando1.replace('_', ' ')
            if new_perando1 in conv:
                new_perando1 = conv[new_perando1]

            if prop != self.propositions[-1]:
                punctuation = ",\n"
            else:
                punctuation = '.'

            if 'contiene' not in prop.operatore:
                output += new_perando1 + ' ' + prop.operatore + ' ' + prop.operando2 + punctuation
            else:
                original_sentence = instance[prop.operando1]
                operando2 = prop.operando2
                toks_operando2 = operando2.split(' ')
                new_toks_operando2 = []
                stemm_dict = None

                if prop.operatore == 'contiene':
                    _, stemm_dict = remove_stopwords_and_stem(original_sentence)
                elif prop.operatore == 'non contiene':
                    stemm_dict = json.load(open("/var/www/sto/stemmed_to_original.txt"))

                for t in toks_operando2:
                    new_toks_operando2.append(stemm_dict[t])

                new_operando2 = ' '.join(new_toks_operando2)
                output += new_perando1 + " " + prop.operatore + ": " + '"' + new_operando2 + '"' + punctuation

        return output
class Regole:
    '''
    Semplicemente una lista di 'Regola'
    Usato per prevedere una classe data un istanza
    '''

    def __init__(self, string_rules):
        '''
        ATTENZIONE: è importante l'ordine delle regole. La lista di decisione di PART va interpretata dall'altro
        verso il basso
        '''
        list_rules = self.extract_rules(string_rules)
        self.regole = list_rules

    def __str__(self):
        output = ''
        for r in self.regole:
            output += str(r) + '\n\n'
        return output

    def remove_rules(self, rules_to_remove):
        for rule in rules_to_remove:
            self.regole.remove(rule)

    # not used anywhere
    def __iter__(self):
        self.current_index_rule = 0
        return self
    # not used anywhere
    def __next__(self):
        if self.current_index_rule < len(self.regole):
            to_be_returned = self.regole[self.current_index_rule]
            self.current_index_rule += 1
            return to_be_returned
        else:
            raise StopIteration
    # not used anywhere
    def get_num_of_rules(self):
        return len(self.regole)


    def predict(self, istanza):
        '''
        Given an instance, checks every rule in order and returns the prediction of the
        first one satisfied, and the rule itself.
        '''
        # contine
        propositions_not_satisfied = []
        for r in self.regole:
            valutazione, prop_not_satisfied = r.valuta(istanza)
            if valutazione is True:
                return r.prediction, r, propositions_not_satisfied
            else:
                propositions_not_satisfied.append(prop_not_satisfied)

        # it happens that sometimes we don't know which class the instance belongs to, thus we return None
        return None, None, None

    def add_rule(self, rule):
        self.regole.append(rule)

    def extract_rules(self, string_rules):
        '''
        Given the rule set output of a PART classifier, it returns a list of type 'Regola' used to initialize
        the 'Regole' class
        '''
        rules = []
        # every for iteration deals with one rule only
        for rule in string_rules.split('\n\n'):
            r = Regola()
            # every for iteration deals with one proposition only of a specific rule
            for prop in rule.split('\n'):
                '''
                if prop =  'STEOPOROSI_GRAVE = 0 AND' then:
                operand1 = 'STEOPOROSI_GRAVE'
                operator = '='
                operand2 = '0'
                '''
                # column name pattern
                match_obj_operand1 = re.search(
                    r'(^\w+(?=\s(=|<|>|<=|>=|non contiene)\s))|(^\w+(?=\s(=|<|>|<=|>=|contiene)\s))',
                    prop)

                # generic case
                if match_obj_operand1 is not None:
                    # operand1 now contains some column name
                    operand1 = match_obj_operand1.group(0)
                    operator = re.search(r'(?<=\s)(<=|>=|=|<|>|contiene|(non\scontiene))(?=\s)', prop).group(0)
                    # some opernd2s contain </>/>=/.. signs which makes the writing of a correct regex a bit difficult
                    # TODO: rewrite the regular expression to handle </>/>=/.. signs
                    operand2 = re.search(
                        r'((> 2.5 mg e < 5 mg)|(>= 5 mg \(Prednisone\))|(<= 10 sigarette/di)|(> 10 sigarette/di))|((?<=#)[\s\w.]+((?=#\s)|(?=#:)))|((?<=\s)[\w\s.,+-]+((?=\sAND$)|(?=:)))',
                        prop).group(0)
                    p = Proposizione(operator, operand1, operand2)
                    r.add_proposision(p)

                match_obj_mn = re.search(r'(?<=\s)[(].*[)]$', prop)
                # the last proposition case:
                if match_obj_mn is not None:
                    '''
                    if prop = 'PATOLOGIA_RESPIRATORIA = 0: 1 (11.14/2.0)' then:
                    prediction = '1'
                    m = '11.14'
                    n = '2.0'
                    '''
                    # contiene solo la stringa di tipo '(m/n)' oppure '(m)'
                    mn = match_obj_mn.group(0)
                    mn = re.findall(r'(?:\d+(?:[.]\d+)?)', mn)
                    m = mn[0]
                    n = mn[1]

                    prediction = re.search(r'(?<=:\s)[\d\w\s.,+-/]+(?=\s\()', prop).group(0)

                    r.prediction = prediction
                    r.m = float(m)
                    r.n = float(n)
                    # given that this is the last proposition, now the rule 'r' is complete.
                    rules.append(r)
        return rules

class Clause:
    def __init__(self, clause_string):
        self.propositions, self.logical_operator = self.extract_propositions(clause_string)

    def evaluate(self,instance):
        # conjunctive clause
        if self.logical_operator == 'AND':
            for p in self.propositions:
                if p.valuta(instance) == False:
                    return False
            return True
        # disunctive clause
        elif self.logical_operator == 'OR':
            for p in self.propositions:
                if p.valuta(instance) == True:
                    return True
            return False

    def extract_propositions(self, clause_string):
        output = []

        if re.search('\sOR\s',clause_string)is not None:
            logical_operator = 'OR'
        else:
            logical_operator = 'AND'

        props = clause_string.split(' '+logical_operator+' ')

        for prop in props:
            output.append(Proposizione(prop))

        return output, logical_operator

    def __eq__(self, other_clause):
        if len(self) != len(other_clause) or self.logical_operator != other_clause.logical_operator:
            return False

        for i in range(0,len(self)):
            if self.propositions[i] != other_clause.propositions[i]:
                return False

        return True

    def __len__(self):
        return len(self.propositions)

    def __str__(self):
        output = '('
        for prop in self.propositions:
            if prop != self.propositions[len(self.propositions)-1]:
                output += str(prop) +' ' +self.logical_operator+' '
            else:
                output+=str(prop)+')'
        return output
class ConjunctiveFormula:
    def __init__(self,formula):
        self.mn = self.extract_mn(formula)
        self.prediction = self.extract_prediction(formula)
        # just keeping the clauses
        formula = re.sub(r'\s::.*','',formula)
        self.clauses=self.extract_clauses(formula)

    def evaluate(self,inst):
        for cl in self.clauses:
            if cl.evaluate(inst)==False:
                return False
        return True

    def extract_clauses(self, formula):
        output = []
        clauses_list = formula.split(') AND (')
        for cl in clauses_list:
            if cl != '':
                cl = re.sub(r'(^\()|(\).*)','',cl)
                output.append(Clause(cl))

        return output

    def extract_mn(self, formula):
        mn = re.findall(r'(?:(?<=\()|(?<=/))(\d+\.\d+)(?:(?=/)|(?=\)))',formula)
        return float(mn[0]),float(mn[1])

    def extract_prediction(self, formula):
        x = re.search(r'(?<=::\s)[\w\s.+,-/()]+(?=\s\(\d)', formula).group()
        return x

    def get_user_readable_version(self, inst):
        def remove_duplicate_clauses():
            no_duplicates = []
            for cl in self.clauses:
                if cl not in no_duplicates:
                    no_duplicates.append(cl)
            self.clauses = no_duplicates

        def simplify_disjunctive_clauses(inst):
            for cl in self.clauses:
                if cl.logical_operator == 'OR':
                    for prop in cl.propositions:
                        if prop.valuta(inst) == True:
                            cl.propositions = [prop]
                            cl.logical_operator = 'AND'
                            break

        ohe_column_to_kinds = {'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA': ter_orm_kinds,
             'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA': ter_osteo_kinds,
             'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA': vit_d_sup_kinds,
             'PATOLOGIE_UTERINE_DIAGNOSI': commons_PATOLOGIE_UTERINE_DIAGNOSI,
             'ALTRE_PATOLOGIE': commons_ALTRE_PATOLOGIE,
             'NEOPLASIA_MAMMARIA_TERAPIA': commons_NEOPLASIA_MAMMARIA_TERAPIA,
             'DISLIPIDEMIA_TERAPIA': commons_DISLIPIDEMIA_TERAPIA,
             'INTOLLERANZE': commons_INTOLLERANZE,
             'ALLERGIE': commons_ALLERGIE,
             'TERAPIA_ALTRO': commons_TERAPIA_ALTRO,
             'CAUSE_OSTEOPOROSI_SECONDARIA': CAUSE_OSTEOPOROSI_SECONDARIA_kinds}

        def fix_binary_columns():
            for cl in self.clauses:
                for prop in cl.propositions:
                    if prop.operando1 in binary_columns:
                        if prop.operatore =='!=':
                            prop.operatore = '='
                            if prop.operando2 == '0':
                                prop.operando2 = '1'
                            elif prop.operando2 == '1':
                                prop.operando2 = '0'

                        prop.operatore = ':'

                        if prop.operando2 == '0':
                            prop.operando2 = 'no'
                        elif prop.operando2 == '1':
                            prop.operando2 = 'si'

        def fix_one_hot_encoded_columns():
            for cl in self.clauses:
                for prop in cl.propositions:
                    # checking if operand1 is an one-hot-encoded column
                    for ohe_column in ohe_column_to_kinds:
                        # if one-hot-encoded column = ALLERGIE and operando1 = ALLERGIE_3, then the if statement matches
                        if re.match(r'{}_\d+'.format(ohe_column),prop.operando1) is not None:

                            if prop.operatore == '=':
                                if prop.operando2 == '1':
                                    prop.operatore = 'contiene'
                                elif prop.operando2 == '0':
                                    prop.operatore = 'non contiene'
                            elif prop.operatore == '!=':
                                if prop.operando2 == '1':
                                    prop.operatore = 'non contiene'
                                elif prop.operando2 == '0':
                                    prop.operatore = 'contiene'



                            # class_index = 3
                            class_index = re.search(r'(?<=_)\d+$',prop.operando1).group(0)
                            # prop.operando2 becomes the third item in commons_TERAPIA_ALTRO
                            prop.operando2 = '\'{}\''.format(ohe_column_to_kinds[ohe_column][int(class_index)])


        def fix_column_names():
            old_column_name_to_new_one = {}

            for ohe_column, ohe_kinds in ohe_column_to_kinds.items():
                # "NEOPLASIA_MAMMARIA_TERAPIA_LISTA_5" will be substituted by "Il campo NEOPLASIA MAMMARIA TERAPIA"
                for i in range(0,len(ohe_kinds)):
                    # 'Il campo NEOPLASIA_MAMMARIA_TERAPIA_LISTA'
                    new_col_name = 'Il campo {}'.format(ohe_column)
                    # 'Il campo NEOPLASIA MAMMARIA TERAPIA LISTA'
                    new_col_name = re.sub(r'_',' ',new_col_name)
                    # 'Il campo NEOPLASIA MAMMARIA TERAPIA'
                    new_col_name = re.sub(r'\sLISTA','',new_col_name)

                    old_column_name_to_new_one['{}_{}'.format(ohe_column,i)]=new_col_name

            # region old_column_name_to_new_one
            old_column_name_to_new_one['AGE']="ETA'"
            old_column_name_to_new_one['SEX']='SESSO'
            old_column_name_to_new_one['TERAPIA_ALTRO_CHECKBOX']='TERAPIA OSTEOPROTETTIVE ALTRO'
            old_column_name_to_new_one['TERAPIA_COMPLIANCE']='COMPLIANCE ALLA TERAPIA'
            old_column_name_to_new_one['MALATTIE_ATTUALI_CHECKBOX']='MALATTIE ATTUALI'
            old_column_name_to_new_one['MALATTIE_ATTUALI_ARTRITE_REUM']='ARTRITE REUMATOIDE'
            old_column_name_to_new_one['MALATTIE_ATTUALI_ARTRITE_PSOR']='ARTRITE PSORIASICA'
            old_column_name_to_new_one['MALATTIE_ATTUALI_LUPUS']='LUPUS'
            old_column_name_to_new_one['MALATTIE_ATTUALI_SCLERODERMIA']='SCLERODERMIA'
            old_column_name_to_new_one['MALATTIE_ATTUALI_ALTRE_CONNETTIVITI']='ALTRE CONNETTIVITI'
            old_column_name_to_new_one['CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX']='CAUSE OSTEOPOROSI SECONDARIA'
            old_column_name_to_new_one['PATOLOGIE_UTERINE_CHECKBOX']='PATOLOGIE UTERINE'
            old_column_name_to_new_one['NEOPLASIA_CHECKBOX']='NEOPLASIA'
            old_column_name_to_new_one['DISLIPIDEMIA_CHECKBOX']='DISLIPIDEMIA'
            old_column_name_to_new_one['PATOLOGIA_CAVO_ORALE_CHECKBOX']='PATOLOGIE CAVO ORALE'
            old_column_name_to_new_one['VITAMINA_D_CHECKBOX']='VITAMINA D'
            old_column_name_to_new_one['ALLERGIE_CHECKBOX']='ALLERGIE'
            old_column_name_to_new_one['INTOLLERANZE_CHECKBOX']='INTOLLERANZE'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_CHECKBOX']='VERTEBRE NON ANALIZZATE'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L1']='VERTEBRA L1 NON ANALIZZATA'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L2']='VERTEBRA L2 NON ANALIZZATA'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L3']='VERTEBRA L3 NON ANALIZZATA'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L4']='VERTEBRA L4 NON ANALIZZATA'
            old_column_name_to_new_one['FRAX_FRATTURE_MAGGIORI_INTERO']='FRAX FRATTURE MAGGIORI'
            old_column_name_to_new_one['FRAX_COLLO_FEMORE_INTERO']='FRAX COLLO FEMORE'
            old_column_name_to_new_one['TBS_COLONNA_APPLICABILE']='TBS APPLICABILE'
            old_column_name_to_new_one['TBS_COLONNA_VALORE']='TBS'
            old_column_name_to_new_one['DEFRA_INTERO']='DEFRA'
            old_column_name_to_new_one['TOT_Tscore']='TOTAL T SCORE'
            old_column_name_to_new_one['TOT_Zscore']='TOTAL Z SCORE'
            # endregion

            for cl in self.clauses:
                for prop in cl.propositions:
                    # this means that the current column name is ok, just getting rid of "_"
                    if prop.operando1 not in old_column_name_to_new_one:
                        prop.operando1 = re.sub(r'_',' ',prop.operando1)
                    # the column name is not ok, so it gets assigned a new one
                    else:
                        prop.operando1 = old_column_name_to_new_one[prop.operando1]

        simplify_disjunctive_clauses(inst)
        fix_one_hot_encoded_columns()
        fix_binary_columns()
        fix_column_names()
        remove_duplicate_clauses()

        complete_prop_set = []
        for cl in self.clauses:
            complete_prop_set.extend(cl.propositions)

        output = ''
        for prop in complete_prop_set:
            if prop is not complete_prop_set[-1]:
                output += str(prop) + ',\n'
            else:
                output += str(prop)

        return output



    def __str__(self):
        output = ''
        for cl in self.clauses:
            if cl is not self.clauses[-1]:
                output+=str(cl)+' AND '
            else:
                output+='{} :: {} ({}/{})'.format(cl,self.prediction,self.mn[0],self.mn[1])
        return output
class Formulaes:
    def __init__(self,formulaes_string):
        self.formulaes =self.extract_formulaes(formulaes_string)

    def extract_formulaes(self, formulaes_string):
        output = []
        formulaes_list = formulaes_string.split('\n')
        for formula in formulaes_list:
            if formula != '':
                output.append(ConjunctiveFormula(formula))

        return output

    def predict(self,inst):
        for f in self.formulaes:
            if f.evaluate(inst) == True:
                return f
        return None

    def __str__(self):
        output = ''
        for f in self.formulaes:
            if f != self.formulaes[-1]:
                output+=str(f)+'\n'
            else:
                output+=str(f)

        return output

