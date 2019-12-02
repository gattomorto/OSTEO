import pandas as pd
import numpy as np
import re
from nltk.stem.snowball import SnowballStemmer
from nltk.corpus import stopwords
from nltk.tokenize import RegexpTokenizer
# nltk.download('stopwords')
from sklearn.feature_extraction.text import TfidfVectorizer
from sqlalchemy import create_engine
from sqlalchemy import text
import json

#sys.stderr = open("log.txt", "a")

# todo controlla che tutti i regex che abbiano anche '_' e dove serve áéíóúàèìòùàèìòù
# todo sost. vitamina con vit
# todo fare quando per esempio sostituisci con il principio fai il controllo che se non lo trovi.. percè potrebbere aver aggiunto uno nuovo
# convertire in int le colonne tipo AGE

#todo in valuta mi arriva cause osteoporosi secondaria con Menopausa prematura\r\nM.I.C.I\r\n va bene? significa ce il preprocessamento signolo non lo ha tolto
#todo inoltre situazione femore dx = '' non va bene.. controllare ongn preprocessato nuovo
def main():
    singola_istanza()


def singola_istanza():

    class_names = [
                   'TERAPIE_ORMONALI_CHECKBOX',
                   'TERAPIE_ORMONALI_LISTA',
                   'TERAPIE_OSTEOPROTETTIVE_CHECKBOX',
                   'TERAPIE_OSTEOPROTETTIVE_LISTA',
                   'VITAMINA_D_TERAPIA_CHECKBOX',
                   'VITAMINA_D_TERAPIA_LISTA',
                   'VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',
                   'VITAMINA_D_SUPPLEMENTAZIONE_LISTA',
                   'CALCIO_SUPPLEMENTAZIONE_CHECKBOX',
                   'CALCIO_SUPPLEMENTAZIONE_LISTA']

    # pk = sys.argv[1]
    # datascan = sys.argv[2]

    # file = open("testfile.txt","w")
    # file.write("pane")

    pk = '1K2C1915ZB0681809'
    datascan = '2018-12-19'

    # print(pk)
    # print(datascan)

    db_connection_str = 'mysql+pymysql://utente_web:CMOREL96T45@localhost/CMO2'
    db_connection = create_engine(db_connection_str)
    df = pd.read_sql(
        'select * from Anamnesi inner join Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE where Anamnesi.PATIENT_KEY = "{}" and Anamnesi.SCAN_DATE= "{}"'.format(
            pk, datascan),
        con=db_connection)

    df.rename(columns={'PAROLOGIA_ESOFAGEA': 'PATOLOGIA_ESOFAGEA'}, inplace=True)

    df_column_uniquify(df)

    instance = df.T.squeeze()

    preprocessato = preprocessamento_singolo(instance)

    stemmed_to_original = json.load(open("/var/www/sto/stemmed_to_original.txt"))
    for class_name in class_names:
        t = text("SELECT * FROM regole WHERE terapia = '{}'".format(class_name))
        result = db_connection.execute(t).fetchone()
        reg = result['user_readable_not_ref']
        rules = Regole(reg)

        predicted, rule_predicted = rules.predict(preprocessato)

        if rule_predicted is not None:
            rule_predicted = rule_predicted.get_medic_readable_version(preprocessato, stemmed_to_original)

        print(predicted)
        print('\n')
        print(rule_predicted)
        print('\n')

    exit(5)

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

def remove_stopwords_and_stem(sentence, regex):
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
    instances.reset_index(drop=True, inplace=True)
    # per il doppio bmi
    instances = df_column_uniquify(instances)
    instances.rename(columns={'PAROLOGIA_ESOFAGEA': 'PATOLOGIA_ESOFAGEA'}, inplace=True)
    instances.replace('NULL', value='', inplace=True)
    instances.replace(r"'", value='', inplace=True, regex=True)

    global stemmed_to_original
    stemmed_to_original = {}

    global col_name_to_ngram
    col_name_to_ngram = {}

    def vectorize(column_name, frame, prefix,
                  regex=r'[a-z]{2,}|100000|2000|25000|10000',
                  n_gram_range=(2, 2)):

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
            column_list[i], stemmed_to_original_locale = remove_stopwords_and_stem(column_list[i], regex)
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

    # commento per il momento perchè devo fare riferimento a valori vecchi
    '''# Tengo solo quelli che sono venuti prima di ottobre
    instances = tabella_completa.loc[instances['SCAN_DATE'] <= '2019-10-01', :].copy()
    instances.reset_index(drop=True, inplace=True)'''


    # creates a new column with the difference between the current year and last period year
    # all nans in ULTIMA_MESTRUAZIONE transfer to ANNI_SENZA_MESTRUAZIONI
    for row_index in range(0, instances.shape[0]):
        scan =  instances.loc[row_index, 'SCAN_DATE'].year
        um =  instances.loc[row_index, 'ULTIMA_MESTRUAZIONE']
        adm = scan - um

        instances.loc[row_index, 'ANNI_DALLA_MENOPAUSA'] = adm

    # sostituisco a 10000UI 10.000UI e 25000 UI con 25.000UI in VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'10.000', r'10000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'25.000', r'25000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'100.000', r'100000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])


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

    # tolgo tutti \t\t\t\\t\n\n\
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'] = re.sub(
            r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$', '',
            instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub(
            r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$', '',
            instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub(
            r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$', '',
            instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])
        instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'] = re.sub(
            r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$', '',
            instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'])
        instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'] = re.sub(
            r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$', '',
            instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = re.sub(
            r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$', '',
            instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'] = re.sub(r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$',
                                                                           '', instances.loc[
                                                                               row_index, 'TERAPIE_ORMONALI_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_LISTA'] = re.sub(r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$',
                                                                             '', instances.loc[
                                                                                 row_index, 'TERAPIE_ORMONALI_LISTA'])
        # elimino il secondo elemento (tutto ciò che è dopo lacapo) #todo controlla uesta cosa anche per gli altri
        # attenzione lascia un \s ('colecalciferolo 10.000UI, 30gocce alla settimana ') 194591351EMASTROGIR
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = re.sub(r'\n.*', '', instances.loc[
            row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'] = re.sub(r'\n.*', '', instances.loc[
            row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'])


    # region fillna
    instances['FRATTURA_VERTEBRE'].replace('', 'no fratture', inplace=True)
    instances['FRATTURA_FEMORE'].replace('','no fratture', inplace=True)
    instances['ABUSO_FUMO'].replace('','non fuma', inplace=True)
    instances['USO_CORTISONE'].replace('','non usa cortisone', inplace=True)
    instances['TERAPIA_ALTRO_CHECKBOX'].fillna(0, inplace=True)
    instances['STATO_MENOPAUSALE'].replace('', np.nan, inplace=True)
    instances['TERAPIA_STATO'].replace('', np.nan, inplace=True)
    instances['TERAPIE_ORMONALI_LISTA'].replace('', np.nan, inplace=True)
    instances['VITAMINA_D_TERAPIA_LISTA'].replace('', np.nan, inplace=True)
    instances['SITUAZIONE_FEMORE_SN'].replace('', np.nan, inplace=True)
    instances['SITUAZIONE_COLONNA'].replace('', np.nan, inplace=True)



    # endregion

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

    # tolgo le informazioni sugli anni da TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA, TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
    # perche sono gia state salvate in un altra colonna
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub(
            r',[0-9]+([,.][0-9]+)?\sanni$', '',
            instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub(
            r',[0-9]+([,.][0-9]+)?\sanni$', '',
            instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])


    if not is_single_instance:
        # vettorizato INTOLLERANZE
        #instances['INTOLLERANZE'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_INTOLLERANZE = \
            vectorize('INTOLLERANZE', instances, prefix='i', n_gram_range=(1, 1))
        print("ok4")

        # vettorizato ALLERGIE
        #instances['ALLERGIE'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_ALLERGIE = \
            vectorize('ALLERGIE', instances, prefix='a', n_gram_range=(1, 1))
        print("ok5")

        # vettorizato DISLIPIDEMIA_TERAPIA
        #instances['DISLIPIDEMIA_TERAPIA'].fillna('', inplace=True)
        # (1,1) perchè sono quasi tutte parole singole
        instances, nomi_nuove_colonne_vectorized_DISLIPIDEMIA_TERAPIA = \
            vectorize('DISLIPIDEMIA_TERAPIA', instances, prefix='dt', n_gram_range=(1, 1))
        print("ok6")

        # vettorizato NEOPLASIA_MAMMARIA_TERAPIA
        #instances['NEOPLASIA_MAMMARIA_TERAPIA'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_NEOPLASIA_MAMMARIA_TERAPIA = \
            vectorize('NEOPLASIA_MAMMARIA_TERAPIA', instances, prefix='nmt', n_gram_range=(1, 1))
        print("ok7")

        # vettorizato PATOLOGIE_UTERINE_DIAGNOSI
        #instances['PATOLOGIE_UTERINE_DIAGNOSI'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_PATOLOGIE_UTERINE_DIAGNOSI = \
            vectorize('PATOLOGIE_UTERINE_DIAGNOSI', instances, prefix='pud', n_gram_range=(1, 1))
        print("ok8")


        # vettorizato VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA
        instances, nomi_nuove_colonne_vectorized_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA = \
            vectorize('VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA',
                      instances,
                      prefix='vidtol',
                      n_gram_range=(1, 2))

        instances, \
        nomi_nuove_colonne_vectorized_TERAPIA_ALTRO \
            = vectorize('TERAPIA_ALTRO',
                        instances,
                        'ta')

        # vettorizzato ALTRE_PATOLOGIE
        #instances['ALTRE_PATOLOGIE'].fillna('', inplace=True)
        instances, nomi_nuove_colonne_vectorized_ALTRE_PATOLOGIE = vectorize('ALTRE_PATOLOGIE', instances,
                                                                                    'ap')



        # vettorizzato CAUSE_OSTEOPOROSI_SECONDARIA
        instances, nomi_nuove_colonne_vectorized_CAUSE_OSTEOPOROSI_SECONDARIA = \
            vectorize('CAUSE_OSTEOPOROSI_SECONDARIA', instances, 'cos', n_gram_range=(1, 1))

        # vettorizzo TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
        instances, nomi_nuove_colonne_vectorized_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = vectorize('TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA', instances, 'tool')

        # vettorizzo TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
        instances, nomi_nuove_colonne_vectorized_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA= vectorize('TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA', instances, 'tosl')



        # region sostituisco solo con il principio TERAPIE_OSTEOPROTETTIVE_LISTA (da prevedere)
        for row_index in range(0, instances.shape[0]):
            row = instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA']
            if row!='':
                # esempio: se row = 'alendronato 70 mg, 1cpr/settimana', poi diventa 'alendronato'
                x = re.sub(r'^([a-zA-Z]+).*', r'\1', row)
                instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'] = x
            else:
                instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA']=np.nan
        # endregion
        print("ok15")

        # region sostituisco solo con il principio CALCIO_SUPPLEMENTAZIONE_LISTA (da prevedere)
        for row_index in range(0, instances.shape[0]):
            row = instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA']
            if row != '':
                # perchè ce 'calcio carbonato' e 'Calcio carbonato' e vogliamo trattarla come la stessa stringa
                row = row.lower()
                # esempio: 'calcio carbonato 600 mg per 2 / die' diventa 'calcio carbonato'
                x = re.sub(r'^([a-zA-Z]*\s[a-zA-Z]*).*', r'\1', row)
                instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'] = x
            else:
                instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'] = np.nan
        # endregion
        print("ok16")

        # region sitemo VITAMINA_D_SUPPLEMENTAZIONE_LISTA (quella da prevedere)
        # sostituisco a 10000UI 10.000UI e 25000 UI con 25.000UI in VITAMINA_D_SUPPLEMENTAZIONE_LISTA
        for row_index in range(0, instances.shape[0]):
            instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] =re.sub(r'10000UI', r'10.000UI', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
            instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = re.sub(r'25000\sUI', r'25.000UI', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])


        # sostituisco alla cura solo il principio e la quantità
        # esempio: 'colecalciferolo 25.000UI, 1 flacone monodose 1 volta al mese' va sostituita con 'calciferolo 25.000UI'
        for row_index in range(0, instances.shape[0]):
            row = instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA']
            if row != '':
                # faccio regex piu semplice dato che ho gia fatto delle sostituzioni nel paragrafo prec.
                x = re.sub(r'^(colecalciferolo\s[0-9]*[.][0-9]*UI).*|^(Calcifediolo\scpr\smolli).*|'
                           r'^(Calcifediolo\sgocce).*|^(Supplementazione\sgiornaliera\sdi\sVit\sD3).*', r'\1\2\3\4',
                           row)
                instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = x
            else:
                instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = np.nan


        # endregion
        print("ok17")

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
                'TERAPIA_OST_ORM_ANNI',  # OK numeric
                'TERAPIA_OST_SPEC_ANNI',  # OK numeric
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
            nomi_nuove_colonne_vectorized_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA + \
            nomi_nuove_colonne_vectorized_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA \
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
    Classe per modellare una proposizione di una certa regola di un classificatore weka
    se una proposizione di una regola è:
    XXX_TERAPIA_OST_ORM_ANNI_XXX <= 0.5 AND
    operatore diventerà: <=
    nome_variabile_operando1 diventerà: XXX_TERAPIA_OST_ORM_ANNI_XXX
    valore_costante_operando2 diventerà: 0.5

    ATTENZIONE:
    Questa classe non può modellare la preposizione dell'ultima regola (: 0 (2.0))
    cioè una prposizione sempre vera. Questo caso è gestito dentro la calsse Regola
    '''

    def __init__(self, operatore, nome_variabile_operando1, valore_costante_operando2):
        self.operatore = operatore
        self.nome_variabile_operando1 = nome_variabile_operando1
        self.valore_costante_operando2 = valore_costante_operando2

    def valuta(self, istanza):
        def is_number(s):
            try:
                float(s)
                return True
            except ValueError:
                return False

        def is_sub(frase1, frase2):
            '''
            se  frase1 è sotto frase di  frase2
            :param frase1:
            :param frase2:
            :return:
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

        '''
        Valuta se questa proposizione è vera o falsa.
        esempio:
            questa proposizione=XXX_TERAPIA_OST_ORM_ANNI_XXX <= 0.5
            data un istanza si va nella colonna XXX_TERAPIA_OST_ORM_ANNI_XXX dell'istanza e si controlla se è <=0.5
            se sì, si ritorna true, altrimenti no
        '''

        try:
            a = istanza["'{}'".format(self.nome_variabile_operando1)]
        except Exception:
            a = istanza[self.nome_variabile_operando1]

        b = self.valore_costante_operando2
        c = self.operatore

        if 'contiene' not in c:

            if pd.isna(a):
                return False

            if not is_number(a) or not is_number(b):
                a = re.sub("'", '', a)
                b = re.sub("'", '', b)

                a = "'{}'".format(a)
                b = "'{}'".format(self.valore_costante_operando2)
            else:
                a = float(a)
                b = float(b)
                a = str(a)
                b = str(b)

            if c == '=':
                c = '=='

            # vado nella colonna con il nome 'nome_variabile_operando1' e controllo se la condizione vale
            s = a + c + b
            if eval(s) == True:
                return True
            else:
                return False

        else:
            a = a.lower()
            allosso, _ = remove_stopwords_and_stem(a, r'[a-z]{2,}|100000|2000|25000|10000')
            if is_sub(b, allosso):
                if c == 'contiene':
                    return True
                elif c == 'non contiene':
                    return False
            else:
                if c == 'non contiene':
                    return True
                elif c == 'contiene':
                    return False

    def __str__(self):
        return self.nome_variabile_operando1 + " " + self.operatore + " " + self.valore_costante_operando2
class Regola:
    '''
    La classe contiene una regola un classificatore PART
    una regola è una lista di proposizioni
    ATTENZIONE: la lista deve essere ordinata perchè le proposizioni e le regole vanno lette
    dall'alto al basso.
    L'ultima regola del classeificatore è una regola sempre vera e che non contiene proposizioni. In questa classe
    viene modellata impostando 'proposizioni' = true.
    Ogni regola ha anche una predizione (intero), cioè la predizione della classe se la regola è vera
    Ogni regola ha 'm' e 'n' che rappresenta la precisione di ogni regola ((m/n))
    '''

    def __init__(self):
        self.m = None
        self.n = None
        self.prediction = None
        self.propositions = None

    def add_proposision(self, prop):
        if self.propositions == None:
            self.propositions = []
        self.propositions.append(prop)

    # todo fare i commetni
    def valuta(self, istanza):
        '''
        Data un istanza(vettore) da classificare, la funzione ritorna True se l'istanza soddisfa la regola.
        '''
        # TODO: non sembra molto chiaro
        # caso particore di una regola sempre vera
        if self.propositions is None:
            return True
        # una regola per essere vera, deve avere tutte le sue proposizioni vere
        for prop in self.propositions:
            # se almeno una prop. è falsa, tutta la regola è falsa
            if prop.valuta(istanza) == False:
                return False
        return True

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

    def get_medic_readable_version(self, instance, stemm_dict):
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
        # todo questo è lo stesso di vectorize.. fare una var. globale
        # prova
        tokenizer = RegexpTokenizer(r'[a-z]{2,}|100000|2000|25000|10000')
        stop_words = stopwords.words('italian')
        stop_words.remove('non')
        stemmer = SnowballStemmer("italian")

        # todo fare comune?
        stop_words += ['.', ',', 'gg', 'die', 'fa', 'im', 'fino', 'uno', 'due', 'tre', 'quattro', 'cinque',
                       'sei', 'ogni',
                       'alcuni', 'giorni', 'giorno', 'mesi', 'mese', 'settimana', 'settimane', 'circa', 'aa', 'gtt',
                       'poi', 'gennaio', 'febbraio', 'marzo', 'maggio', 'aprile', 'giugno', 'luglio', 'agosto',
                       'settembre', 'ottobre', 'novembre', 'dicembre', 'anno', 'anni', 'sett', 'pu', 'dx', 'sn',
                       'nel']

        output = ''
        # the else rule
        if self.propositions is None:
            return 'None'

        for prop in self.propositions:
            new_perando1 = new_perando1.replace('_', ' ')

            if prop != self.propositions[-1]:
                punctuation = ",\n"
            else:
                punctuation = '.'

            if 'contiene' not in prop.operatore:
                output += new_perando1 + ' ' + prop.operatore + ' ' + prop.valore_costante_operando2 + punctuation
            else:
                try:
                    original_sentence = instance["'{}'".format(prop.nome_variabile_operando1)]
                except Exception:
                    original_sentence = instance[prop.nome_variabile_operando1]

                original_tokens = tokenizer.tokenize(original_sentence)

                to_be_removed = []
                for token in original_tokens:
                    if token in stop_words:
                        to_be_removed.append(token)
                for elem in to_be_removed:
                    if elem in original_tokens:
                        original_tokens.remove(elem)

                # da stemmed a token (biettiva)
                d = {}
                for tok in original_tokens:
                    d[stemmer.stem(tok)] = tok

                operando2 = prop.valore_costante_operando2
                toks_operando2 = operando2.split(' ')

                # new_toks_operando2 = [d[t] for t in toks_operando2]

                new_toks_operando2 = []
                for t in toks_operando2:
                    if t in d:
                        tok_original_operando2 = d[t]
                    else:
                        tok_original_operando2 = stemm_dict[t]

                    new_toks_operando2.append(tok_original_operando2)

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

    def __iter__(self):
        self.current_index_rule = 0
        return self

    def __next__(self):
        if self.current_index_rule < len(self.regole):
            to_be_returned = self.regole[self.current_index_rule]
            self.current_index_rule += 1
            return to_be_returned
        else:
            raise StopIteration

    def get_num_of_rules(self):
        return len(self.regole)

    # todo fare i commenti
    def predict(self, istanza):
        '''
        Se una regola è vera, ritorno la predizione di quella regola
        '''
        for r in self.regole:
            # non ce bisogno dell'else perchè ce l'ultima regola che è sempre vera (nel caso di not refined)
            if r.valuta(istanza) == True:
                return r.prediction, r

        # questo è stato aggiunto in seguito perchè le regole nor refined potrebbero non avere un else...
        # so then i return null which means 'i dont know'
        return None, None

    def add_rule(self, rule):
        self.regole.append(rule)

    def extract_rules(self, string_rules):
        '''
        '''
        rules = []
        for rule in string_rules.split('\n\n'):
            r = Regola()
            for prop in rule.split('\n'):

                match_obj_operand1 = re.search(
                    r'(^\w+(?=\s(=|<|>|<=|>=|non contiene)\s))|(^\w+(?=\s(=|<|>|<=|>=|contiene)\s))',
                    prop)

                # generic case
                if match_obj_operand1 is not None:
                    operand1 = match_obj_operand1.group(0)
                    operator = re.search(r'(?<=\s)(<=|>=|=|<|>|contiene|(non\scontiene))(?=\s)', prop).group(0)
                    operand2 = re.search(
                        r'((> 2.5 mg e < 5 mg)|(>= 5 mg \(Prednisone\))|(<= 10 sigarette/di)|(> 10 sigarette/di))|((?<=#)[\s\w.áéíóúàèìòùàèìòù]+((?=#\s)|(?=#:)))|((?<=\s)[\w\s.,+-]+((?=\sAND$)|(?=:)))',
                        prop).group(0)
                    p = Proposizione(operator, operand1, operand2)
                    r.add_proposision(p)

                match_obj_mn = re.search(r'(?<=\s)[(].*[)]$', prop)
                if match_obj_mn is not None:
                    # contiene solo la strunga di tipo (m/n) oppure (m)
                    mn = match_obj_mn.group(0)
                    # nell prima posizione m, e nella seconda n
                    mn = re.findall(r'(?:\d+(?:[.]\d+)?)', mn)
                    m = mn[0]
                    n = mn[1]
                    # todo capisce solo i numeri
                    prediction = re.search(r'(?<=:\s)[\d\w\s.,+-/]+(?=\s\()', prop).group(0)
                    r.prediction = prediction
                    r.m = float(m)
                    r.n = float(n)

                    rules.append(r)
        return rules


if __name__ == '__main__':
    main()
