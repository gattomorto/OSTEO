import pandas as pd
import numpy as np
import re
from sklearn.tree import DecisionTreeClassifier
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn import datasets
from sklearn.metrics import confusion_matrix, accuracy_score, confusion_matrix
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, OneHotEncoder
from sklearn.feature_extraction.text import CountVectorizer, TfidfVectorizer
from nltk.stem.snowball import SnowballStemmer
from nltk.corpus import stopwords
from nltk.tokenize import sent_tokenize, word_tokenize
import nltk
#nltk.download('all')
'''
0.562  0.578-0.627

        list(nomi_colonne_onehotencoded_SITUAZIONE_COLONNA)  qualcosiiina
        list(nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_SN) qualcosiina
        list(nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_DX) qualcosiina
'''
def main():
    tabella_completa = pd.read_csv("osteo.csv")
    num_classi = 5

    tabella_ridotta = preprocessamento(tabella_completa)
    X = tabella_ridotta.iloc[:, :-num_classi]
    Y = tabella_ridotta.iloc[:, -num_classi:]

    kf = KFold(n_splits=4, shuffle=True)
    tree = DecisionTreeClassifier()  # 6 non male
    avg_ext_train_score = 0
    avg_ext_test_score = 0
    avg_int_train_score = 0
    avg_int_test_score = 0
    for train_indexes, test_indexes in kf.split(X):
        trainX = X.iloc[train_indexes, :]
        trainY = Y.iloc[train_indexes, :]
        testX = X.iloc[test_indexes, :]
        testY = Y.iloc[test_indexes, :]
        tree.fit(trainX, trainY)
        avg_ext_test_score += tree.score(testX, testY)
        avg_ext_train_score += tree.score(trainX, trainY)
        # avg_int_train_score += inernal_acc_score(trainX,trainY,tree)
        # avg_int_test_score += inernal_acc_score(testX,testY,tree)

    print("avg ext: {}, {}".format(
        *[round(avg / kf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))
    # print("avg int: {}, {}".format(*[round(avg/kf.get_n_splits(), 3) for avg in [avg_int_train_score, avg_int_test_score]]))

    # print("avg2_test_score: {}".format(round(avg_test2_score/num_somme,3)))


def inernal_acc_score(X, true_Y, model):
    '''
    Riceve le istanze da classificare (X) su un modello allenato (model) e i risultati corretti (true_Y).
    Ritorna l'accuratezza interna del modello: il rapporto tra tutti i bit e i bit indovinati di tutto il set
    Differenza tra internal_acc_score() e model.score():
        Se predicted_y = [0, 1, 1] e true_y = [0, 1, 0]
        per model.score() questa istanza è 0
        per internal_acc_score() è 0.66
    La funzione calcola la media di tutte le accuracy di ogni riga
    '''
    avg_score = 0
    # andava bene fare anche true_Y.shape[0] perchè hanno dimensione uguale
    for row_index in range(0, X.shape[0]):
        # i-esima riga di true_Y
        y_true = true_Y.iloc[row_index, :].values
        # il modello predice data l'iesima riga di X
        y_predicted = model.predict(X.iloc[row_index, :].values.reshape(1, -1))
        avg_score += accuracy_score(y_true, y_predicted[0, :])
    return avg_score / X.shape[0]

def preprocessamento(tabella_completa):
    def one_hot_encode(frame, column_name, regex, prefix):
        '''
        frame:
        A               B
        cane bau        x
        gatto miao      y
        pesce blob      z

        column_name: A
        regex: "solo la prima parola"
        prefix: x

        output:
        A               B    xcane  xgatto xpesce
        cane bau        x     1      0      0
        gatto miao      y     0      1      0
        pesce blob      z     0      0      1

        e la lista delle colonne aggiunte [cane, gatto, pesce]

        regex serve nel caso in cui si desiderasse considerare una sottostringa della riga
        il prefisso serve per evitare che ci siano colonne con lo stesso nome: questa funzione può essere chiamata
        due volte con due colonne che hanno 'na' e allora si formerà una colonna comune
        '''
        # conterrà cane, gatto, pesce
        valori_nominali = []
        for row_index in range(0, frame.shape[0]):
            # iesima riga del frame, sarà 'cane bau', 'gatto miao', 'pesce blob'
            row = frame.loc[row_index, column_name]
            # la sottostringa di interesse. sarà 'cane', 'gatto', 'pesce'
            value_of_interest = re.search(regex, row)
            valori_nominali.append(value_of_interest[0])
        # trasformo la lista in DataFrame
        valori_nominali = pd.Series(valori_nominali)
        valori_nominali = valori_nominali.to_frame()
        one_hot_encoder = OneHotEncoder()
        one_hot_encoder.fit(valori_nominali)
        # valori one-hot-encoded, però è una matrice, bisogna trasformare in DataFrame
        valori_nominali_encoded_ndarray = one_hot_encoder.transform(valori_nominali).toarray()
        # servono per la creazione del DataFrame
        nomi_colonne_nuove = one_hot_encoder.get_feature_names()
        # aggiungo il prefisso
        nomi_colonne_nuove = [prefix+col_name for col_name in nomi_colonne_nuove]
        # DataFrame creato, ora lo aggiungere al DataFrame passato
        valori_nominali_encoded_dataframe = pd.DataFrame(valori_nominali_encoded_ndarray, columns=nomi_colonne_nuove)
        frame = pd.concat([frame, valori_nominali_encoded_dataframe], axis=1)
        # ritorno i nomi perchè poi bisogna selezionarli per il modello
        return frame, nomi_colonne_nuove

    tabella_completa['1 TERAPIA_ALTRO'].fillna('na', inplace=True)
    terapia_altro_lista = tabella_completa['1 TERAPIA_ALTRO'].tolist()
    print(sent_tokenize(terapia_altro_lista[4]))




    exit(8)

    # one hot encoding SITUAZIONE_FEMORE_DX
    tabella_completa['1 SITUAZIONE_FEMORE_DX'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_DX = one_hot_encode(tabella_completa, '1 SITUAZIONE_FEMORE_DX', '^.*', 'sfd')

    # one hot encoding SITUAZIONE_FEMORE_SN
    tabella_completa['1 SITUAZIONE_FEMORE_SN'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_SN = one_hot_encode(tabella_completa, '1 SITUAZIONE_FEMORE_SN','^.*', 'sfs')

    # one hot encoding SITUAZIONE_COLONNA
    tabella_completa['1 SITUAZIONE_COLONNA'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_SITUAZIONE_COLONNA = one_hot_encode(tabella_completa, '1 SITUAZIONE_COLONNA','^.*', 'sc')

    # one hot encoding di CAUSE_OSTEOPOROSI_SECONDARIA
    tabella_completa['1 CAUSE_OSTEOPOROSI_SECONDARIA'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_CAUSE_OSTEOPOROSI_SECONDARIA=one_hot_encode(tabella_completa,'1 CAUSE_OSTEOPOROSI_SECONDARIA', '^.*','cos')

    # alcuni hanno -1
    tabella_completa['1 BMI'].replace(-1,tabella_completa['1 BMI'].mean() , inplace=True)

    # one hot encoding di TERAPIA_STATO
    tabella_completa['1 TERAPIA_STATO'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_TERAPIA_STATO = one_hot_encode(tabella_completa, '1 TERAPIA_STATO', '^.*', 'ts')

    # one hot encoding di STATO_MENOPAUSALE
    tabella_completa['1 STATO_MENOPAUSALE'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_STATO_MENOPAUSALE = one_hot_encode(tabella_completa,'1 STATO_MENOPAUSALE','^.*','sm')

    # ABUSO_FUMO: 0 se non fuma, 1 se fuma meno di 10, 2 se piu di 10
    tabella_completa['1 ABUSO_FUMO'].fillna('na', inplace=True)
    for row_index in range(0, tabella_completa.shape[0]):
        row = tabella_completa.loc[row_index, '1 ABUSO_FUMO']
        if row[0] == 'n':
            tabella_completa.loc[row_index, '1 ABUSO_FUMO'] = 0
        elif row[0] == '<':
            tabella_completa.loc[row_index, '1 ABUSO_FUMO'] = 1
        elif row[0] == '>':
            tabella_completa.loc[row_index, '1 ABUSO_FUMO'] = 2

    # one hot encoding di TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
    tabella_completa['1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'].fillna("na,0 anni", inplace=True)
    tabella_completa, nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = \
        one_hot_encode(tabella_completa, "1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA","(^[a-zA-Z]+(([+](\s[a-zA-Z]*|[a-zA-Z]*))|(\s[+](\s[a-zA-Z]*))|(\s[+][a-zA-Z]*)){0,1})",'too')
    '''# region one-hot-encode TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
    # da TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA estraggi solamente il principio attivo e one-hot-encode
    # questa lista contiene solo il principio attivo (se una riga ha due terapie, prendo solo la prima)
    principio_attivo_col = []
    # metto 'na,0 anni' perchè la colonna TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA servirà dopo per tirare fuori le date
    # per le righe vuote: na per il nome del principio e 0 anni per la durata
    tabella_completa['1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'].fillna("na,0 anni", inplace=True)
    for row_index in range(0, tabella_completa.shape[0]):
        # iesima riga del tipo 'TSEC, estrogeni coniugati equini 0,4 mg- bazedoxifene 20 m'
        terapia_osteoprotettiva_orm = tabella_completa.loc[row_index, '1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA']
        # estraggo il principio attivo dalla riga (TSEC)
        princ_att = re.findall('(^[a-zA-Z]+(([+](\s[a-zA-Z]*|[a-zA-Z]*))|(\s[+](\s[a-z]*))|(\s[+][a-z]*)){0,1})', terapia_osteoprotettiva_orm)
        # per qualche strana ragione princ_att è una lista di tuple, allora prendo il primo elemento della prima tupla
        principio_attivo_col.append(princ_att[0][0])
    # trasformo principio_attivo_col in dataframe
    principio_attivo_col = pd.Series(principio_attivo_col)
    principio_attivo_col = principio_attivo_col.to_frame()
    one_hot_encoder = OneHotEncoder()
    one_hot_encoder.fit(principio_attivo_col)
    # questa è una matrice ndarray encoded
    principio_attivo_encoded = one_hot_encoder.transform(principio_attivo_col).toarray()
    # lista dei nomi delle nuove colonne (dovresti cambiare nome perchè potrebbero confondersi)
    nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = one_hot_encoder.get_feature_names()
    # trasformo la matrice in DataFrame e do anche i nomi alle colonne corrispondenti al principio
    principio_attivo_encoded_frame = pd.DataFrame(principio_attivo_encoded, columns=nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA)
    # unisco la tabella appena creata con la tabella iniziale
    tabella_completa = pd.concat([tabella_completa, principio_attivo_encoded_frame], axis=1)
    # endregion
    '''
    # region separazione anni TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
    # qui rifaccio la stessa identica cosa che ho fatto per TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA(separo le date)
    # solo che adesso lo faccio per TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
    terapia_osteoprotettiva_ormon_anni_col = []
    # se è null allora metto 0 anni
    for row_index in range(0, tabella_completa.shape[0]):
        terapia_osteoprotettiva_orm = tabella_completa.loc[row_index, '1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA']
        # isolo la parte di testo con il numero di anni
        anni = re.findall('[a-z\s],[0-9]+(?:[,.][0-9]+)*\sanni$', terapia_osteoprotettiva_orm)
        # ottengo il numero senza altri caratteri
        anni = re.findall("[0-9]+(?:[.,][0-9]*)*", anni[0])
        # se il numero ha una virgola, si sostituisce con il punto
        anni = re.sub(",", ".", anni[0])
        terapia_osteoprotettiva_ormon_anni_col.append(anni)
    # aggiungo la nuova colonna con un nome che suggerisce l'artificialità
    tabella_completa['XXX_TERAPIA_OST_ORM_ANNI_XXX'] = terapia_osteoprotettiva_ormon_anni_col
    # endregion

    # one hot encoding di TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
    tabella_completa['1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'].fillna("na,0 anni", inplace=True)
    tabella_completa, nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA = \
        one_hot_encode(tabella_completa, '1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA','(^[a-zA-Z]+(([+](\s[a-zA-Z]*|[a-zA-Z]*))|(\s[+](\s[a-zA-Z]*))|(\s[+][a-zA-Z]*)){0,1})','tos')
    '''
    # region one-hot-encode TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
    # questa lista contiene solo il principio attivo (se una riga ha due terapie, prendo solo la prima)
    principio_attivo_col = []
    # metto 'na,0 anni' perchè la colonna TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA servirà dopo anche per tirare fuori le date
    # per le righe vuote: na per il nome del principio e 0 anni per la durata (na2 perchè altrimenti si confonde con na di TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA)
    tabella_completa['1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'].fillna("naa,0 anni", inplace=True)
    for row_index in range(0, tabella_completa.shape[0]):
        # abbiamo un problema alla riga 15 alendronato +vidD.. findall trova solo alendronato+ bisogna sistemare dopo
        if row_index==14:
            ggg= 'per il debugg'
        # iesima riga del tipo 'Bazedoxifene cpr 20 mg, 1 cpr/di in continua,2 anni'
        terapia_osteoprotettiva_spec = tabella_completa.loc[row_index, '1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA']
        # estraggo il principio attivo dalla riga (Bazedoxifene)
        princ_att = re.findall('(^[a-zA-Z]+(([+](\s[a-zA-Z]*|[a-zA-Z]*))|(\s[+](\s[a-z]*))|(\s[+][a-z]*)){0,1})',
                               terapia_osteoprotettiva_spec)
        # per qualche strana ragione princ_att è una lista di tuple, allora prendo il primo elemento della prima tupla
        principio_attivo_col.append(princ_att[0][0])
    # trasformo principio_attivo_col in dataframe
    principio_attivo_col = pd.Series(principio_attivo_col, name='principio_attivo_col')
    principio_attivo_col = principio_attivo_col.to_frame()
    one_hot_encoder = OneHotEncoder()
    one_hot_encoder.fit(principio_attivo_col)
    # questa è una matrice ndarray encoded
    principio_attivo_encoded = one_hot_encoder.transform(principio_attivo_col).toarray()
    # lista dei nomi delle nuove colonne (dovresti cambiare nome perchè potrebbero confondersi)
    nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA = one_hot_encoder.get_feature_names()
    # trasformo la matrice in DataFrame e do anche i nomi alle colonne corrispondenti al principio
    principio_attivo_encoded_frame = pd.DataFrame(principio_attivo_encoded,
                                                  columns=nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA)
    # unisco la tabella appena creata con la tabella iniziale
    tabella_completa = pd.concat([tabella_completa, principio_attivo_encoded_frame], axis=1)
    # endregion
    '''

    # region separazione anni TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
    # separo le date da TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA
    # nuova colonna da aggiungere alla tabella
    terapia_osteoprotettiva_spec_anni_col = []
    # se è null allora metto 0 anni
    #tabella_completa['1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'].fillna("na,0 anni", inplace=True)
    for row_index in range(0, tabella_completa.shape[0]):
        terapia_osteoprotettiva = tabella_completa.loc[row_index, '1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA']
        # isolo la parte di testo con il numero di anni
        anni = re.findall('[a-z\s],[0-9]+(?:[,.][0-9]+)*\sanni$', terapia_osteoprotettiva)
        # ottengo il numero senza altri caratteri
        anni = re.findall("[0-9]+(?:[.,][0-9]*)*",anni[0])
        # se il numero ha una virgola, si sostituisce con il punto
        anni = re.sub(",", ".", anni[0])
        terapia_osteoprotettiva_spec_anni_col.append(anni)
    # aggiungo la nuova colonna con un nome che suggerisce l'artificialità
    tabella_completa['XXX_TERAPIA_OST_SPEC_ANNI_XXX'] = terapia_osteoprotettiva_spec_anni_col
    # endregion

    # region sostituisco a ULTIMA_MESTRUAZIONE: ULTIMA_MESTRUAZIONE - BIRTHDATE
    # sostituisco a ULTIMA_MESTRUAZIONE: ULTIMA_MESTRUAZIONE - BIRTHDATE
    birthdate_col = tabella_completa['1 BIRTHDATE']
    # dalla data di nascita mi serve solo l'anno
    birthdate_year_col = [data[0:4] for data in birthdate_col]
    # nel ciclo viene fatta la differenza
    for row_index in range(0, tabella_completa.shape[0]):
        tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] = \
            tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] - int(birthdate_year_col[row_index])
    # per gli uomini e le donne che hanno ancora il ciclo, viene fatta la media
    tabella_completa['1 ULTIMA_MESTRUAZIONE'].fillna(tabella_completa['1 ULTIMA_MESTRUAZIONE'].mean(), inplace=True)
    # endregion

    # region altro
    tabella_completa['1 FRATTURA_VERTEBRE'].fillna(0, inplace=True)
    tabella_completa['1 FRATTURA_VERTEBRE'].replace('piu di 1', 2, inplace=True)

    # sostituisco i null con 0
    tabella_completa['1 FRATTURA_FEMORE'].fillna(0, inplace=True)
    tabella_completa['1 VITAMINA_D'].fillna(0, inplace=True)
    tabella_completa['1 TBS_COLONNA_APPLICABILE'].fillna(0, inplace=True)
    tabella_completa['1 TBS_COLONNA_VALORE'].fillna(0, inplace=True)

    # sostituisco variabili categoriali con dei numeri
    tabella_completa['1 SEX'].replace('F', 0, inplace=True)
    tabella_completa['1 SEX'].replace('M', 1, inplace=True)

    # sostituisco i null con la media della colonna
    tabella_completa['1 FRAX_FRATTURE_MAGGIORI_INTERO'].fillna(
        tabella_completa['1 FRAX_FRATTURE_MAGGIORI_INTERO'].mean(), inplace=True)
    tabella_completa['1 FRAX_COLLO_FEMORE_INTERO'].fillna(tabella_completa['1 FRAX_COLLO_FEMORE_INTERO'].mean(),
                                                          inplace=True)
    tabella_completa['1 DEFRA_INTERO'].fillna(tabella_completa['1 DEFRA_INTERO'].mean(), inplace=True)
    tabella_completa['1 L1_AREA'].fillna(tabella_completa['1 L1_AREA'].mean(), inplace=True)
    tabella_completa['1 L2_AREA'].fillna(tabella_completa['1 L2_AREA'].mean(), inplace=True)
    tabella_completa['1 L3_AREA'].fillna(tabella_completa['1 L3_AREA'].mean(), inplace=True)
    tabella_completa['1 L4_AREA'].fillna(tabella_completa['1 L4_AREA'].mean(), inplace=True)
    tabella_completa['1 TOT_AREA'].fillna(tabella_completa['1 TOT_AREA'].mean(), inplace=True)
    tabella_completa['1 L1_BMC'].fillna(tabella_completa['1 L1_BMC'].mean(), inplace=True)
    tabella_completa['1 L2_BMC'].fillna(tabella_completa['1 L2_BMC'].mean(), inplace=True)
    tabella_completa['1 L3_BMC'].fillna(tabella_completa['1 L3_BMC'].mean(), inplace=True)
    tabella_completa['1 L4_BMC'].fillna(tabella_completa['1 L4_BMC'].mean(), inplace=True)
    tabella_completa['1 TOT_BMC'].fillna(tabella_completa['1 TOT_BMC'].mean(), inplace=True)
    tabella_completa['1 L1_BMD'].fillna(tabella_completa['1 L1_BMD'].mean(), inplace=True)
    tabella_completa['1 L2_BMD'].fillna(tabella_completa['1 L2_BMD'].mean(), inplace=True)
    tabella_completa['1 L3_BMD'].fillna(tabella_completa['1 L3_BMD'].mean(), inplace=True)
    tabella_completa['1 L4_BMD'].fillna(tabella_completa['1 L4_BMD'].mean(), inplace=True)
    tabella_completa['1 TOT_BMD'].fillna(tabella_completa['1 TOT_BMD'].mean(), inplace=True)
    tabella_completa['1 L1_Tscore'].fillna(tabella_completa['1 L1_Tscore'].mean(), inplace=True)
    tabella_completa['1 L2_Tscore'].fillna(tabella_completa['1 L2_Tscore'].mean(), inplace=True)
    tabella_completa['1 L3_Tscore'].fillna(tabella_completa['1 L3_Tscore'].mean(), inplace=True)
    tabella_completa['1 L4_Tscore'].fillna(tabella_completa['1 L4_Tscore'].mean(), inplace=True)
    tabella_completa['1 TOT_Tscore'].fillna(tabella_completa['1 TOT_Tscore'].mean(), inplace=True)
    tabella_completa['1 L1_Zscore'].fillna(tabella_completa['1 L1_Zscore'].mean(), inplace=True)
    tabella_completa['1 L2_Zscore'].fillna(tabella_completa['1 L2_Zscore'].mean(), inplace=True)
    tabella_completa['1 L3_Zscore'].fillna(tabella_completa['1 L3_Zscore'].mean(), inplace=True)
    tabella_completa['1 L4_Zscore'].fillna(tabella_completa['1 L4_Zscore'].mean(), inplace=True)
    tabella_completa['1 TOT_Zscore'].fillna(tabella_completa['1 TOT_Zscore'].mean(), inplace=True)
    tabella_completa['1 NECK_AREA'].fillna(tabella_completa['1 NECK_AREA'].mean(), inplace=True)
    tabella_completa['1 TROCH_AREA'].fillna(tabella_completa['1 TROCH_AREA'].mean(), inplace=True)
    tabella_completa['1 INTER_AREA'].fillna(tabella_completa['1 INTER_AREA'].mean(), inplace=True)
    tabella_completa['1 HTOT_AREA'].fillna(tabella_completa['1 HTOT_AREA'].mean(), inplace=True)
    tabella_completa['1 WARDS_AREA'].fillna(tabella_completa['1 WARDS_AREA'].mean(), inplace=True)
    tabella_completa['1 NECK_BMC'].fillna(tabella_completa['1 NECK_BMC'].mean(), inplace=True)
    tabella_completa['1 TROCH_BMC'].fillna(tabella_completa['1 TROCH_BMC'].mean(), inplace=True)
    tabella_completa['1 INTER_BMC'].fillna(tabella_completa['1 INTER_BMC'].mean(), inplace=True)
    tabella_completa['1 HTOT_BMC'].fillna(tabella_completa['1 HTOT_BMC'].mean(), inplace=True)
    tabella_completa['1 WARDS_BMC'].fillna(tabella_completa['1 WARDS_BMC'].mean(), inplace=True)
    tabella_completa['1 NECK_BMD'].fillna(tabella_completa['1 NECK_BMD'].mean(), inplace=True)
    tabella_completa['1 TROCH_BMD'].fillna(tabella_completa['1 TROCH_BMD'].mean(), inplace=True)
    tabella_completa['1 INTER_BMD'].fillna(tabella_completa['1 INTER_BMD'].mean(), inplace=True)
    tabella_completa['1 HTOT_BMD'].fillna(tabella_completa['1 HTOT_BMD'].mean(), inplace=True)
    tabella_completa['1 WARDS_BMD'].fillna(tabella_completa['1 WARDS_BMD'].mean(), inplace=True)
    # endregion

    # seleziono le colonne da usare per la predizione
    l = [
        '1 AGE',
        '1 SEX'
        ] +\
        list(nomi_colonne_onehotencoded_STATO_MENOPAUSALE) \
        + [
        '1 ULTIMA_MESTRUAZIONE' #fa niente e anche quella sopra
        ]+\
        list(nomi_colonne_onehotencoded_TERAPIA_STATO) \
        + [
        '1 TERAPIA_OSTEOPROTETTIVA_ORMONALE' # quella sopra non fa un cazzo
        ] + \
        list(nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA) \
        + [
        'XXX_TERAPIA_OST_ORM_ANNI_XXX',#fa niente e anche quella sopra
        '1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA'
        ] + \
        list(nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA) \
        + [
        'XXX_TERAPIA_OST_SPEC_ANNI_XXX',# fa niente e anche quella sopra
        '1 VITAMINA_D_TERAPIA_OSTEOPROTETTIVA',
        '1 TERAPIA_COMPLIANCE',
        '1 BMI',
        '1 FRATTURE',
        '1 FRATTURA_VERTEBRE',
        '1 FRATTURA_FEMORE',
        '1 FRATTURA_SITI_DIVERSI',
        '1 FRATTURA_FAMILIARITA',
        '1 ABUSO_FUMO_CHECKBOX',
        '1 ABUSO_FUMO', #fa niente
        '1 USO_CORTISONE_CHECKBOX',
        '1 MALATTIE_ATTUALI_CHECKBOX',
        '1 MALATTIE_ATTUALI_ARTRITE_REUM',
        '1 MALATTIE_ATTUALI_ARTRITE_PSOR',
        '1 MALATTIE_ATTUALI_LUPUS',
        '1 MALATTIE_ATTUALI_SCLERODERMIA',
        '1 MALATTIE_ATTUALI_ALTRE_CONNETTIVITI',
        '1 CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX'
        ] +\
        list(nomi_colonne_onehotencoded_CAUSE_OSTEOPOROSI_SECONDARIA) \
        +[
        '1 PATOLOGIE_UTERINE_CHECKBOX',# quello sopra non fa niente
        '1 NEOPLASIA_CHECKBOX',
        '1 SINTOMI_VASOMOTORI',
        '1 SINTOMI_DISTROFICI',
        '1 DISLIPIDEMIA_CHECKBOX',
        '1 IPERTENSIONE',
        '1 RISCHIO_TEV',
        '1 PATOLOGIA_CARDIACA',
        '1 PATOLOGIA_VASCOLARE',
        '1 INSUFFICIENZA_RENALE',
        '1 PATOLOGIA_RESPIRATORIA',
        '1 PATOLOGIA_CAVO_ORALE_CHECKBOX',
        '1 PATOLOGIA_EPATICA',
        '1 PATOLOGIA_ESOFAGEA',
        '1 GASTRO_DUODENITE',
        '1 GASTRO_RESEZIONE',
        '1 RESEZIONE_INTESTINALE',
        '1 MICI',
        '1 VITAMINA_D_CHECKBOX',  # diminuito forse
        '1 VITAMINA_D',  # aumentato tanto
        '1 INTOLLERANZE_CHECKBOX'] +\
        list(nomi_colonne_onehotencoded_SITUAZIONE_COLONNA) + \
        list(nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_SN) +\
        list(nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_DX) \
         +[
        '1 OSTEOPOROSI_GRAVE',  # aumentato tanto

        '1 VERTEBRE_NON_ANALIZZATE_CHECKBOX', #niente sembra
        '1 VERTEBRE_NON_ANALIZZATE_L1', #niente sembra
        '1 VERTEBRE_NON_ANALIZZATE_L2', #niente sembra
        '1 VERTEBRE_NON_ANALIZZATE_L3', #niente sembra
        '1 VERTEBRE_NON_ANALIZZATE_L4', #niente sembra
        '1 COLONNA_NON_ANALIZZABILE', #niente sembra
        '1 COLONNA_VALORI_SUPERIORI', #niente sembra
        '1 FEMORE_NON_ANALIZZABILE', #niente sembra
        
        '1 FRAX_APPLICABILE',
        '1 FRAX_FRATTURE_MAGGIORI_INTERO',  # aumento discreto
        '1 FRAX_COLLO_FEMORE_INTERO',  # aumento discreto
        '1 TBS_COLONNA_APPLICABILE',  # nessun aumento
        '1 TBS_COLONNA_VALORE',  # nessun aumento
        '1 DEFRA_INTERO',
        '1 NORME_PREVENZIONE', #aumento discreto
        '1 NORME_COMPORTAMENTALI',  #diminuisce??
        '1 ATTIVITA_FISICA',
        '1 INDAGINI_APPROFONDIMENTO_CHECKBOX',#fa nulla
        '1 SOSPENSIONE_FUMO',
        '1 CONTROLLO_DENSITOMETRICO_CHECKBOX',#fa nulla
        '1 L1_AREA',
        '1 L2_AREA',
        '1 L3_AREA',
        '1 L4_AREA',
        '1 TOT_AREA',
        '1 L1_BMC',
        '1 L2_BMC',
        '1 L3_BMC',
        '1 L4_BMC',
        '1 TOT_BMC',
        '1 L1_BMD',
        '1 L2_BMD',
        '1 L3_BMD',
        '1 L4_BMD',
        '1 TOT_BMD',
        '1 L1_Tscore',
        '1 L2_Tscore',
        '1 L3_Tscore',
        '1 L4_Tscore',
        '1 TOT_Tscore',
        '1 L1_Zscore',
        '1 L2_Zscore',
        '1 L3_Zscore',
        '1 L4_Zscore',
        '1 TOT_Zscore',
        '1 NECK_AREA',
        '1 TROCH_AREA',
        '1 INTER_AREA',
        '1 HTOT_AREA',
        '1 WARDS_AREA',
        '1 NECK_BMC',
        '1 TROCH_BMC',
        '1 INTER_BMC',
        '1 HTOT_BMC',
        '1 WARDS_BMC',
        '1 NECK_BMD',
        '1 TROCH_BMD',
        '1 INTER_BMD',
        '1 HTOT_BMD',
        '1 WARDS_BMD',

        '1 TERAPIE_ORMONALI_CHECKBOX',
        '1 TERAPIE_OSTEOPROTETTIVE_CHECKBOX',
        '1 VITAMINA_D_TERAPIA_CHECKBOX',
        '1 VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',
        '1 CALCIO_SUPPLEMENTAZIONE_CHECKBOX'
    ]

    return tabella_completa[l]

def null_accuracy_score(X, true_Y, model):
    '''
    Ritorna il rapporto tra le righe nulle indovinare e il totale delle righe nulle (external accuracy)
    :param X: le istanze da predire (in genere il X_test)
    :param true_Y: la predizione corretta (in genere Y_test)
    :param model: il modello (in genere DecisionTree)
    :return: righe nulle indovinate / righe nulle totali
    '''
    predicted_Y = model.predict(X)
    tot_righe_nulle = 0
    tot_righe_nulle_indovinate = 0
    # andave bene anche true_Y.shape[0] pechè hanno lo stesso numero di righe
    for row_index in range(0, X.shape[0]):
        row_ptedicted_Y = predicted_Y[row_index, :]
        row_true_Y = true_Y.iloc[row_index, :].values
        # se l'isesima riga è nulla
        if not np.any(row_true_Y):
            tot_righe_nulle += 1
            # e se il modello ha indovinato correttamente la riga nulla
            if not np.any(row_ptedicted_Y):
                tot_righe_nulle_indovinate += 1

    # print("tot {}, ind {}, totot {}, rapp {}".format(tot_righe_nulle,tot_righe_nulle_indovinate,X.shape[0],tot_righe_nulle/X.shape[0]))
    return tot_righe_nulle_indovinate / tot_righe_nulle


if __name__ == '__main__':
    main()
