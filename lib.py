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
        tokenizer = RegexpTokenizer(r'[a-z]+')
        for _, item in instances['TERAPIA_ALTRO'].iteritems():
            t =  tokenizer.tokenize(item.lower())
            if 'menopausa' in t:
                print(item)
            words += t

        # lascio iu, assunto.. ma devi fare solo bigrammi
        stop_words = stopwords.words('italian')+['ui','vacanza','menopausa','supplementazione','sospeso','anni','anno','mesi','aa','circa','mese','die','dopo','tp','cp','ogni','indicazione','fino','assunto','gtt','mg','curante','sett','settimana','poi','fa','riferisce','assume','assunzione','alcuni','terapia','due','im','volont','ricorda','gg','fl','precedente','consigliata','seguito','odierna','consigliato']


        to_be_removed = []
        for token in words:
            if token in stop_words:
                to_be_removed.append(token)

        for elem in to_be_removed:
            if elem in words:
                words.remove(elem)
        counts = Counter(words)

        normalized_count = {w: c / sum(counts.values()) for w, c in counts.items()}
        normalized_count = sorted(normalized_count.items(), key=lambda kv: kv[1],reverse=True)
        print(counts)

        '''for _, item in instances['TERAPIA_ALTRO'].iteritems():
            if 'D' in item:
                print(item)'''


        print(normalized_count)
        exit()
    def prova2():
        words = []
        tokenizer = RegexpTokenizer(r'[a-z]+')
        for _, item in instances['ALTRE_PATOLOGIE'].iteritems():
            t = tokenizer.tokenize(item.lower())
            if 'menopausa' in t:
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

        normalized_count = {w: c / sum(counts.values()) for w, c in counts.items()}
        normalized_count = sorted(normalized_count.items(), key=lambda kv: kv[1], reverse=True)
        print(counts)


        #print(normalized_count)
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

    def one_hot_encode(sep, instances, col_name, classes):
        # x is a list of tuples, each tuple contains classes present in some row of the column
        x = []
        # item is some row of the column
        for _ ,item in instances[col_name].iteritems():
            #print('{}:{}'.format(i,item))
            # the tuple for the current row
            t = ()
            if item != '':
                # bear in mind that a row may contain multiple classes. For example the TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA column
                # might contain 'ibandronato capsule\nRaloxifene 20mg'
                # first of all we split the sentences and get: ['ibandronato capsule', 'Raloxifene 20mg']
                # for each sentence:
                for line in item.split('\n'):
                    line = line.lower()
                    # each sentence should be assigned to some class, if not, something's not working
                    line_assigned = False
                    # we check if the sentence contains some class
                    for cl in classes:
                        # each sentence should contain one and only one class, so the if shoul fire once
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




    # TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA(beginning)/TERAPIE_ORMONALI_LISTA(class)/ORM.SOST./C.O.(site)
    ter_orm_kinds = ['tibolone', 'estradiolo + drospirenone', 'tsec, estrogeni coniugati equini 0,4 mg- bazedoxifene 20 mg', 'terapia ormonale sostitutiva per via transdermica']
    # TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA(beginnig)/TERAPIE_OSTEOPROTETTIVE_LISTA(class)/OSTEOPROTETTIVA SPECIFICA(site)
    ter_osteo_kinds = ['alendronato', 'risedronato', 'ibandronato', 'clodronato', 'raloxifene', 'bazedoxifene', 'denosumab', 'teriparatide', 'zoledronato']
    # VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA(beginning)/VITAMINA_D_SUPPLEMENTAZIONE_LISTA(class)/VITAMINA D SUPPLEMENTAZIONE(site)
    vit_d_sup_kinds = [ 'colecalciferolo 300.000ui','colecalciferolo 100.000ui', 'colecalciferolo 25.000ui','colecalciferolo 10.000ui', 'calcifediolo', 'supplementazione giornaliera di vit d3 (colecalciferolo) a dose  2000ui /die']
    # VITAMINA_D_TERAPIA_LISTA(class)/VITAMINA D TERAPIA(site)
    vit_d_ter_kinds = ['colecalciferolo','calcifediolo']
    # CALCIO_SUPPLEMENTAZIONE_LISTA(class)/CALCIO SUPPLEMENTAZIONE(site)
    calcio_supp_kinds = ['calcio citrato','calcio carbonato']

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
        # remove the middle and substitute with \n
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub(middle_junk, '\n', instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub(middle_junk, '\n', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] = re.sub(middle_junk,'\n', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])


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



    instances, new_column_names_for_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = one_hot_encode('\r\n',instances,'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA', ter_orm_kinds)
    instances, new_column_names_for_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA = one_hot_encode('\r\n',instances,'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA', ter_osteo_kinds)
    instances, new_column_names_for_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA = one_hot_encode('\r\n',instances,'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA', vit_d_sup_kinds)


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

    '''
    # region creazione di TERAPIA_OST_ORM_ANNI da TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA separando gli anni
    # attenzione questo paragrafo deve stare prima di sostituizione della colonna con il principio

    # la lista da trasformare poi in colonna del DataFrame
    terapia_osteoprotettiva_ormon_anni_col = []
    for row_index in range(0, instances.shape[0]):
        terapia_osteoprotettiva_orm = instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA']
        if terapia_osteoprotettiva_orm != '':
            # print(terapia_osteoprotettiva_orm)
            # isolo la parte di testo con il numero di anni
            anni_match_obj = re.search(r'(?<=,)[0-9]+([,.][0-9]+)?(?=\sanni)', terapia_osteoprotettiva_orm)
            if anni_match_obj is not None:
                anni = anni_match_obj.group(0)
                anni = re.sub(',', '.', anni)
                # print(anni)
            # if there's some propblem with the input format for example: 'TSEC, estrogeni coniugati equini 0,4 mg- bazedoxifene 20 mg,3 mesi anni'
            else:
                anni = np.nan
        else:
            anni = np.nan
        terapia_osteoprotettiva_ormon_anni_col.append(anni)
    # aggiungo la nuova colonna con un nome che suggerisce l'artificialità
    instances['TERAPIA_OST_ORM_ANNI'] = terapia_osteoprotettiva_ormon_anni_col
    # endregion
    # region creazione di TERAPIA_OST_SPEC_ANNI da TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA separando gli anni
    # attenzione questo paragrafo deve stare prima di sostituizione della colonna con il principio
    # la lista da trasformare poi in colonna del DataFrame
    terapia_osteoprotettiva_spec_anni_col = []
    for row_index in range(0, instances.shape[0]):
        terapia_osteoprotettiva_spec = instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA']
        if terapia_osteoprotettiva_spec != '':
            # isolo la parte di testo con il numero di anni
            anni_match_obj = re.search(r'(?<=,)[0-9]+([,.][0-9]+)?(?=\sanni)', terapia_osteoprotettiva_spec)
            # 'clodronato fiale 200 mg im,1 fl ogni 15 giorni,6  anni' per esempio
            if anni_match_obj is not None:
                anni = anni_match_obj.group(0)
                anni = re.sub(',', '.', anni)

            else:
                anni = np.nan
        else:
            anni = np.nan
        terapia_osteoprotettiva_spec_anni_col.append(anni)
    # aggiungo la nuova colonna con un nome che suggerisce l'artificialità
    instances['TERAPIA_OST_SPEC_ANNI'] = terapia_osteoprotettiva_spec_anni_col
    # endregion
    '''


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



        # vettorizato INTOLLERANZE
        #instances['INTOLLERANZE'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_INTOLLERANZE = \
            vectorize('INTOLLERANZE', instances, prefix='i', n_gram_range=(1, 1))

        # vettorizato ALLERGIE
        #instances['ALLERGIE'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_ALLERGIE = \
            vectorize('ALLERGIE', instances, prefix='a', n_gram_range=(1, 1))

        # vettorizato DISLIPIDEMIA_TERAPIA
        #instances['DISLIPIDEMIA_TERAPIA'].fillna('', inplace=True)
        # (1,1) perchè sono quasi tutte parole singole
        instances, nomi_nuove_colonne_vectorized_DISLIPIDEMIA_TERAPIA = \
            vectorize('DISLIPIDEMIA_TERAPIA', instances, prefix='dt', n_gram_range=(1, 1))

        # vettorizato NEOPLASIA_MAMMARIA_TERAPIA
        #instances['NEOPLASIA_MAMMARIA_TERAPIA'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_NEOPLASIA_MAMMARIA_TERAPIA = \
            vectorize('NEOPLASIA_MAMMARIA_TERAPIA', instances, prefix='nmt', n_gram_range=(1, 1))

        # vettorizato PATOLOGIE_UTERINE_DIAGNOSI
        #instances['PATOLOGIE_UTERINE_DIAGNOSI'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_PATOLOGIE_UTERINE_DIAGNOSI = \
            vectorize('PATOLOGIE_UTERINE_DIAGNOSI', instances, prefix='pud', n_gram_range=(1, 1))


        # vettorizato VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA
        instances, nomi_nuove_colonne_vectorized_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA = vectorize('VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA',  instances,prefix='vidtol',n_gram_range=(1, 2))

        instances, nomi_nuove_colonne_vectorized_TERAPIA_ALTRO  = vectorize('TERAPIA_ALTRO', instances,'ta')

        # vettorizzato ALTRE_PATOLOGIE
        #instances['ALTRE_PATOLOGIE'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_ALTRE_PATOLOGIE = vectorize('ALTRE_PATOLOGIE', instances, 'ap')



        # vettorizzato CAUSE_OSTEOPOROSI_SECONDARIA
        instances, nomi_nuove_colonne_vectorized_CAUSE_OSTEOPOROSI_SECONDARIA = \
            vectorize('CAUSE_OSTEOPOROSI_SECONDARIA', instances, 'cos', n_gram_range=(1, 1))

        '''
        # vettorizzo TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
        instances, nomi_nuove_colonne_vectorized_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = vectorize('TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA', instances, 'tool')
        # vettorizzo TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
        instances, nomi_nuove_colonne_vectorized_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA= vectorize('TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA', instances, 'tosl')
        '''



        l = [
                'PATIENT_KEY',
                'SCAN_DATE',
                'AGE',  # OK
                'SEX',  # OK weka trasforma in nominal
                'STATO_MENOPAUSALE',  # OK weka trasforma in nominal
                'ETA_MENOPAUSA',
                'ANNI_DALLA_MENOPAUSA',
                'TERAPIA_STATO',  # OK
                'TERAPIA_OSTEOPROTETTIVA_ORMONALE',#checkbox
                'TERAPIA_OSTEOPROTETTIVA_SPECIFICA',#ceckbox
                #'TERAPIA_OST_ORM_ANNI',  # OK numeric
                #'TERAPIA_OST_SPEC_ANNI',  # OK numeric
                'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA',  # NON lo riconosce come nominal**
                'TERAPIA_ALTRO_CHECKBOX',  # NON lo riconosce come nominal attenzione ci sono dei null**
                'TERAPIA_COMPLIANCE',  # NON lo riconosce come nominal**
                'BMI',  # OK
               # 'FRATTURE',  # NON lo riconosce come nominal**
                'FRATTURA_VERTEBRE_CHECKBOX',
                'FRATTURA_VERTEBRE',  # ok trasformato in nominal {no fratture, 1, piu di 1}
                'FRATTURA_FEMORE',  # ok trasformato in nominal {no fratture, 1, piu di 1}
                'FRATTURA_SITI_DIVERSI',  # NON lo riconosce come nominal**
                'FRATTURA_FAMILIARITA',  # NON lo riconosce come nominal**
                'ABUSO_FUMO_CHECKBOX',  # NON lo riconosce come nominal**
                'ABUSO_FUMO',  # ok  tengo cosi com'è .. al posto di null metto 'non fuma'
                'USO_CORTISONE_CHECKBOX',  # NON**
                'USO_CORTISONE',  # ok trasformato in nominal
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
                'MICI',  # NON
                'VITAMINA_D_CHECKBOX',  # NON
                'VITAMINA_D',  # ok
                'ALLERGIE_CHECKBOX',  # NON + quella sotto
                'INTOLLERANZE_CHECKBOX',
                'SITUAZIONE_COLONNA_CHECKBOX',  # NON**
                'SITUAZIONE_COLONNA',  # ok
                'SITUAZIONE_FEMORE_SN_CHECKBOX',  # NON**
                'SITUAZIONE_FEMORE_SN',  # ok
                'SITUAZIONE_FEMORE_DX_CHECKBOX',  # NON**
                'SITUAZIONE_FEMORE_DX',
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
                'FRAX_FRATTURE_MAGGIORI_INTERO',
                'FRAX_COLLO_FEMORE_INTERO',
                'TBS_COLONNA_APPLICABILE',  # NON**
                'TBS_COLONNA_VALORE',
                'DEFRA_APPLICABILE',  # NON
                'DEFRA_INTERO',
                'TOT_Tscore',
                'TOT_Zscore']+ \
            nomi_nuove_colonne_vectorized_TERAPIA_ALTRO + \
            nomi_nuove_colonne_vectorized_ALTRE_PATOLOGIE + \
            nomi_nuove_colonne_vectorized_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA + \
            nomi_nuove_colonne_vectorized_PATOLOGIE_UTERINE_DIAGNOSI + \
            nomi_nuove_colonne_vectorized_NEOPLASIA_MAMMARIA_TERAPIA + \
            nomi_nuove_colonne_vectorized_DISLIPIDEMIA_TERAPIA + \
            nomi_nuove_colonne_vectorized_ALLERGIE + \
            nomi_nuove_colonne_vectorized_INTOLLERANZE + \
            nomi_nuove_colonne_vectorized_CAUSE_OSTEOPOROSI_SECONDARIA + \
            new_column_names_for_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA+ \
            new_column_names_for_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA+ \
            new_column_names_for_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA\
            + [

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

        #l.append(class_name)

        return instances[l], col_name_to_ngram, stemmed_to_original
    else:
        return instances.T.squeeze()
class Proposizione:
    '''
    Proposition for some rule.
    operando1 contains the name of some column
    operatore can be =,<,>,>=,<= for numeric and nominal datatypes, or 'contiene', 'non contiene' for text columns
    operando2 contains some value to check operando1 against to
    '''

    def __init__(self, operatore, operando1, operando2):
        self.operatore = operatore
        self.operando1 = operando1
        self.operando2 = operando2

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
        return self.operando1 + " " + self.operatore + " " + self.operando2
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
        self.propositions = None

    def add_proposision(self, prop):
        # if it's the first time we need to create the list and then add
        if self.propositions is None:
            self.propositions = []
        # otherwise just add
        self.propositions.append(prop)


    def valuta(self, istanza):
        '''
        Given an instans of type 'Series', returns true if it satisfies the rule, false otherwise
        '''

        # the "always true" rule
        if self.propositions is None:
            return True, None
        # for a rule to be true it needs to have all its propositions true
        for prop in self.propositions:
            # if at least one proposition is False, then the whole rule is false
            if prop.valuta(istanza) is False:
                return False, prop
        return True, None

    def __str__(self):
        if self.propositions is not None:
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