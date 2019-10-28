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
from nltk.tokenize import RegexpTokenizer
from sklearn.preprocessing import PolynomialFeatures
from sklearn.linear_model import LinearRegression
import matplotlib.pyplot as plt
#nltk.download('all')
from weka.classifiers import Classifier, Evaluation
from weka.core.converters import Loader, Saver
from weka.core.classes import Random
import weka.core.jvm as jvm
from weka.filters import Filter

def main():
    jvm.start(max_heap_size="900m")

    loader = Loader(classname="weka.core.converters.ArffLoader")
    data = loader.load_file("breast-cancer.arff")
    data.class_is_last()

    cls2 = Classifier(classname="weka.classifiers.rules.PART")

    # evaluation = Evaluation(data)
    # evaluation.crossvalidate_model(cls2, data, 10, Random(42))
    cls2.build_classifier(data)

    # data un istanza ritorna la probabilità che assegnerebbe ad ogni classe
    # print(cls2.distribution_for_instance(data.get_instance(data.num_instances-1)))

    # stampa le regole
    # print(cls2)

    rules = estrai_regole(cls2)

    for r in rules:
        print(str(r)+'\n')


    jvm.stop()


def estrai_regole(classifier):

    f = open("h.txt",'r+')
    classifier = f.read()

    # straggo le regole in formato testuale dal classificatore, esattamente quelle che vengono fuori nel software Weka
    regole_formato_testo = str(classifier)
    # le regole hanno un header con scritto PART decision list con sotto una serie di lineette, allora li sostituisco
    # con la stringa vuota
    regole_formato_testo = re.sub(r'^PART decision list[\r\n][-]+[\r\n]{2}','',regole_formato_testo)
    # stessa cosa: nel footer c'è Number of Rule: n. Sostituisco con la stringa vuota
    regole_formato_testo = re.sub(r'[\r\n][\r\n]Number of Rules\s+:\s+[0-9]+$','',regole_formato_testo)

    # conterrà tutte le regole
    regole = []
    # conterrà le proposizioni di una certa regola
    proposizioni = []
    # se la regola è vera, predizione è la classe predetta
    predizione = None
    # stringa (m/n) di ogni regola
    gs = None
    # per ogni riga
    for line in str(regole_formato_testo).splitlines():
        # riga generica letta: 1 VERTEBRE_NON_ANALIZZATE_L4 <= 0 AND
        # match_obj_operando1 è un oggetto che conterrà il match: 1 VERTEBRE_NON_ANALIZZATE_L4
        # attenzione match_obj_operando1 non è una stringa ma un oggetto di tipo MatchObject, la stringa si estrae
        # in seguito
        # regex parte dall inizio della riga fino ad arrivare ad un operatore
        match_obj_operando1 = re.search(r'^.+(?=\s(=|<|>|<=|>=)\s)', line)

        # se match_obj_operando1 è None, ci sono due possibilità:
        # 1) riga vuota (non c'è il nome della colonna)
        # 2) l'ultima regola della lista del tipo: ': 0 (2.0)' (non c'è il nome della colonna)
        if match_obj_operando1 is None:
            # caso 2) if vero solo 1 volta, all'ultima riga
            if line != '':
                # : 0 (2.0) tiro fuori lo 0 che è in mezzo a due spazi
                predizione = re.search(r'(?<=:\s)\d(?=\s)', line).group(0)
                # tiro fuori (2.0) che è in mezzo tra uno spazio e la fine
                gs = re.search(r'(?<=\s)[(].*[)]$', line).group(0)
                # dato che è un caso particolare che non ha proposizioni,
                # alora questo caso è gestito trasformando la lista 'proposizioni' in variabile booleana = true
                # cioè che le proposizioni sono tutte vere
                proposizioni = True

            # caso 1) caso generico, parte quando si finisce di leggere una regola
            # qui la lista 'proposizioni' è riempita di proposizioni della regola opppure proposizioni = true se è
            # l'ultima regola. predizione letta dall'ultima riga dellA regolA
            r = Regola(predizione, proposizioni, gs)
            regole.append(r)
            # svuoto per far posto alle nuove proposizioni della regola successiva
            proposizioni = []
            # continue perchè non devo leggere niente dalla riga vuota
            continue

        # estraggo la stringa del nome della colonna (1 VERTEBRE_NON_ANALIZZATE_L4)
        operando1 = match_obj_operando1.group(0)

        # se line: 1 VERTEBRE_NON_ANALIZZATE_L4 <= 0 AND, operatore sarà '<='
        # operatore isolato da due spazi
        operatore = re.search(r'(?<=\s)(<=|>=|=|<|>)(?=\s)', line).group(0)
        # converto '=' in '==' perchè uso queste stringhe dentro eval()
        if operatore == '=': operatore = '=='

        # se line: '1 VERTEBRE_NON_ANALIZZATE_L4 <= 0 AND', operando2 sarà '0'
        # regex estrae interi o float che sono in mezzo ad un [operatore][spazio] e [AND]
        # oppure se siamo arrivati all'ultima riga della regola che è del tipo '1 NORME_PREVENZIONE > 0: 1 (13.0)'
        # regex estrae interi o float in mezzo ad un [operatore][spazio] e [:] cioè lo '0' in questo caso
        operando2 = re.search(r'(?<=\s)[+-]?([0-9]*[.])?[0-9]+((?=\sAND$)|(?=:))', line).group(0)

        # qui cerco di estrarre la predizione.
        # se line = '1 NORME_PREVENZIONE > 0: 1 (13.0)' allora estraggo '1'
        # ha senso solo se è 'line' è l'ultima proposizione della regola
        # regex prende il numero tra [:][spazio] e [spazio][(]
        # ATTENZIONE: posso solo prevedere classi numeriche
        match_obj_predizione = re.search(r'(?<=:\s)\d(?=\s\()', line)

        # non è null sole se siamo arrivari all'ultima riga della regola
        # altrimenti ignora
        if match_obj_predizione is not None:
            predizione = match_obj_predizione.group(0)

        # non è null sole se siamo arrivari all'ultima riga della regola
        # altrimenti ignora
        match_obj_gs = re.search(r'(?<=\s)[(].*[)]$', line)
        if match_obj_gs is not None:
            gs = match_obj_gs.group(0)

        # se line: '1 VERTEBRE_NON_ANALIZZATE_L4 <= 0 AND'
        # operatore: <=
        # operando1: 1 VERTEBRE_NON_ANALIZZATE_L4
        # operando2: 0
        p = Proposizione(operatore, operando1, operando2)
        proposizioni.append(p)

    return regole


def multilabel():
    '''
    tutto quello che c'era nel main una volta
    :return:
    '''
    tabella_completa = pd.read_csv("osteo.csv")

    num_classi = 5

    tabella_ridotta = preprocessamento(tabella_completa)
    #tabella_ridotta.to_csv('osteo_r.csv', index=False)
    # tabella_ridotta = pd.read_csv('osteo_r.csv')

    X = tabella_ridotta.iloc[:, :-num_classi]
    Y = tabella_ridotta.iloc[:, -num_classi:]

    kf = KFold(n_splits=4, shuffle=True)

    '''
    min lif split non fa niente.. 
    max_leaf_nodes = 25 aumenta tanto con maxdepth = 6
    min sample spit = 10 ok 5come10  15no
    min sample leaf=10 no, 20no, 5no

    migliore maxdept 6, max_leaf_nodes = 25 0.695, 0.701, 0.688, 0.698, 0.692, 0.691

    max_depth=7,max_leaf_nodes=35 no
                               40 ok 
                               45 no
                               10 no
                               20 come il migliore
                               25 mcome il migliore
                               30 no
                               15 ok
    '''
    tree = DecisionTreeClassifier()
    tree = DecisionTreeClassifier(max_depth=6, max_leaf_nodes=25)

    avg_ext_train_score = 0
    avg_ext_test_score = 0
    avg_int_train_score = 0
    avg_int_test_score = 0
    trainX = [];
    trainY = [];
    testX = [];
    testY = []
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
        # print("null: "+str(null_accuracy_score(testX,testY,tree)))
        # print("no null:"+str(no_null_accuracy_score(testX, testY, tree)))

    print_feature_importances(tree, trainX)

    print("avg ext: {}, {}".format(
        *[round(avg / kf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))
    # print("avg int: {}, {}".format(*[round(avg/kf.get_n_splits(), 3) for avg in [avg_int_train_score, avg_int_test_score]]))
def print_feature_importances(model, X):
    '''
    Dato il modello 'model' allenato su 'X', la funzione stampa in maniera decrescente le feautures più significative
    '''
    feature_importances = list(zip(X.columns, model.feature_importances_))
    feature_importances.sort(key = lambda tup: tup[1], reverse = True)
    for t in feature_importances:
        print(t)
def preprocessamento(tabella_completa):
    def one_hot_encode(frame, column_name, regex, prefix):
        '''
        frame:
        A               B
        cane bau        a
        gatto miao      b
        pesce blob      c

        column_name: A
        regex: "solo la prima parola"
        prefix: x

        output:
        A               B    xcane  xgatto xpesce
        cane bau        a     1      0      0
        gatto miao      b     0      1      0
        pesce blob      c     0      0      1
        gatto miao      e     0      1      0

        e la lista delle colonne aggiunte [xcane, xgatto, xpesce] (serve perchè dopo dovrò selezionare queste colonne)

        regex serve nel caso in cui si desiderasse considerare una sottostringa della riga (cioe al posto di 'cane bau'
        è come se fosse 'cane')

        il prefisso serve per evitare che ci siano colonne con lo stesso nome: questa funzione può essere chiamata
        due volte con due colonne che hanno 'na' e allora si formerà una colonna comune
        '''
        # conterrà cane, gatto, pesce
        # questa conterrà tutti i valori con cui fare one-hot-encoding
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

    def remove_stopwords_and_stem(sentence, regex):
        '''
        Data una stringa contenete una frase ritorna una stringa con parole in forma radicale e senza rumore
        es:
        sentence: ha assunto alendronato per 2 anni
        regex: come scegliere i token. di default scelgo parole e non numeri tranne 100.000/10.000/... che sono comuni
        returns: assunt alendronato
        '''

        tokenizer = RegexpTokenizer(regex)
        tokens = tokenizer.tokenize(sentence)
        tokens = [x.lower() for x in tokens]

        # libreria nltk
        stop_words = stopwords.words('italian')
        # 'non' è molto importante
        stop_words.remove('non')
        stop_words += ['.', ',', 'm','t' ,'gg','die','fa','mg','cp', 'im', 'fino', 'uno', 'due', 'tre', 'quattro', 'cinque','sei', 'ogni',
                       'alcuni', 'giorni', 'giorno', 'mesi', 'mese', 'settimana', 'settimane', 'circa', 'aa', 'gtt',
                       'poi', 'gennaio', 'febbraio', 'marzo', 'maggio', 'aprile', 'giugno', 'luglio', 'agosto',
                       'settembre', 'ottobre', 'novembre', 'dicembre', 'anno', 'anni', 'sett','pu','u']

        # trovo tutti i token da eliminare dalla frase
        to_be_removed = []
        for token in tokens:
            if token in stop_words:
                to_be_removed.append(token)

        # rimuovo i token dalla frase
        for elem in to_be_removed:
            if elem in tokens:
                tokens.remove(elem)

        #print(tokens)
        # la parte di stemming
        stemmer = SnowballStemmer("italian")
        tokens = [stemmer.stem(tok) for tok in tokens]

        # converto da lista di token in striga
        output = ' '.join(tokens)
        return output

    def vectorize(column_name, frame, prefix, regex=r'(?:[a-zA-Z]+)|(?:[0-9]+[.,][0-9]+)', n_gram_range=(2, 2)):
        '''
        dato il nome di una colonna contenente testo, per ogni riga crea una rappresentazione
        vettoriale senza stopwords e stemmed (vedi remove_stopwords_and_stem())
        dopo aver visto tutte le righe avremo un vettore per ogni riga, cioè una matrice
        questa verrà integrato al dataframe in input.

        :param column_name: nome colonna da vettorizzare
        :param frame: dataframe
        :param prefix: per differenziare i nomi delle colonne in output
        :param regex: come dividere i token
        :param n_gram_range: (2,2) se voglio solo bigrammi, (1,2) se voglio bigrammi e monogrammi...
        :return: la tabella con le nuove colonne e i nomi delle nuove colonne
        '''
        column_list = frame[column_name].tolist()
        for i in range(0, len(column_list)):
            column_list[i] = remove_stopwords_and_stem(column_list[i], regex)
        vectorizer = TfidfVectorizer(ngram_range= n_gram_range, norm=None)
        # in vectorized_matrix ogni riga è un vettore corrispondente ad una frase
        vectorized_matrix = vectorizer.fit_transform(column_list).toarray()
        # servono per filtrare tabella_completa. il suffisso serve perchè cosi se viene chiamata la funzione piu volte,
        # non si confonde i nomi delle colonne
        nomi_nuove_colonne_vectorized = [prefix + str(i) for i in range(0, vectorized_matrix.shape[1])]
        # converto in DataFrame perchè devo accostarlo alla tabella_completa
        vectorized_frame = pd.DataFrame(vectorized_matrix, columns=nomi_nuove_colonne_vectorized)
        frame = pd.concat([frame, vectorized_frame], axis=1)
        return frame, nomi_nuove_colonne_vectorized

    def polinomial_regression(col_name_x, col_name_y, frame, degree_, plt_show=False):
        '''
        esegue una regressione polinomiale univariata
        si usa per prevedere col_name_y usando col_name_x
        :param col_name_x: nome della colonna dei valori di x
        :param col_name_y: nome della colonna dei valori da modellare (la colonna da prevedere)
        :param frame: il dataframe da dove prendere le colonne
        :param degree_: il grado del polinomio
        :param plt_show: se true mostra il grafico
        :return: il modello della regressione e polynomial_features per trasformare in features quadratiche
        '''
        # questo evita il problema dei plot sovrapposti
        plt.clf()
        # xy_frame contiene solo la colonna X e la colonna Y
        xy_frame = frame[[col_name_x, col_name_y]]
        xy_frame = xy_frame.dropna()
        # tengo solo le righe che non hanno zeri
        xy_frame = xy_frame[xy_frame[col_name_x] != 0]
        xy_frame = xy_frame[xy_frame[col_name_y] != 0]

        # scatter plot di X e Y (viene mostrato solo se plt_show = true)
        plt.scatter(xy_frame[col_name_x].values, xy_frame[col_name_y].values, s=0.2, c='black')

        polynomial_features = PolynomialFeatures(degree=degree_)
        # x ritrasformato per rigressione polinomiale
        x_poly = polynomial_features.fit_transform(xy_frame[col_name_x].values.reshape(-1, 1))
        model = LinearRegression()
        model.fit(x_poly, xy_frame[col_name_y].values)
        # y_poly_pred = model.predict(x_poly)

        # da qui in poi è solo per il grafico e serve solo se plt_show = true
        # min,max per il dominio del grafico
        min_x = min(xy_frame[col_name_x].values)
        max_x = max(xy_frame[col_name_x].values)
        x_plot = np.arange(min_x, max_x, 0.1)
        x_plot_poly = polynomial_features.fit_transform(x_plot.reshape(-1, 1))
        y_plot = model.predict(x_plot_poly)
        plt.plot(x_plot, y_plot, c='red')

        if plt_show:
            plt.show()

        return model, polynomial_features

    # Tengo solo quelli che sono venuti prima di ottobre
    tabella_completa = tabella_completa.loc[tabella_completa['1 SCAN_DATE'] <= '2019-10-01', :].copy()
    tabella_completa.reset_index(drop=True, inplace=True)

    # region categorizzo USO_CORTISONE
    # 0 se non usa cortisone, 1 se compreso tra 2.5 e 5, 2 se > 5
    for row_index in range(0, tabella_completa.shape[0]):
        value = tabella_completa.loc[row_index,'1 USO_CORTISONE']
        if value == '> 2.5 mg e < 5 mg':
            tabella_completa.loc[row_index,'1 USO_CORTISONE'] = 1
        elif value == '>= 5 mg (Prednisone)':
            tabella_completa.loc[row_index,'1 USO_CORTISONE'] = 2
        else:
            tabella_completa.loc[row_index,'1 USO_CORTISONE'] = 0
    # endregion

    # region categorizzo ULTIMA_MESTRUAZIONE
    # come prima cosa sostituisco l'anno dell'ultima mestruazione con quanti anni non ha mestruazioni
    # divido in range [-inf,mean-std]=poco, [mean-std,mean+std]=medio, [mean+std,+inf]=tanto, e per gli uomini e/o per
    # le donne che hanno ancora il ciclo la categoria è 'maschio_o_attiva'
    # una volta che ho le categorie: one-hot-encode
    birthdate_col = tabella_completa['1 BIRTHDATE']
    # dalla data di nascita mi serve solo l'anno
    birthdate_year_col = [data[0:4] for data in birthdate_col]
    # nel ciclo viene fatta la differenza
    for row_index in range(0, tabella_completa.shape[0]):
        # qui sostituisco alla data dell'ultima mest. con gli anni che non ha mest.
        # la differenza lascia nan per chi ha ULTIMA_MESTRUAZIONE nan
        tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] = \
            tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] - int(birthdate_year_col[row_index])
    std = tabella_completa['1 ULTIMA_MESTRUAZIONE'].std()
    mean = tabella_completa['1 ULTIMA_MESTRUAZIONE'].mean()
    # in questa parte sostituisco gli anni in cui non ha il ciclo con una tra le categorie
    for row_index in range(0, tabella_completa.shape[0]):
        anni_senza_ciclo = tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE']
        if mean-std <= anni_senza_ciclo <= mean+std:
            tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] = 'medio'
        elif anni_senza_ciclo > mean + std:
            tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] = 'tanto'
        elif anni_senza_ciclo < mean - std:
            tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] = 'poco'
        else:
            tabella_completa.loc[row_index, '1 ULTIMA_MESTRUAZIONE'] = 'maschio_o_attiva'
    # onehotencode
    tabella_completa, nomi_colonne_onehotencoded_ULTIMA_MESTRUAZIONE =\
        one_hot_encode(tabella_completa, '1 ULTIMA_MESTRUAZIONE','^.*', 'ultima_mestr')
    #endregion

    # region null di SITUAZIONE_COLONNA vengono sostituiti con i valori SITUAZIONE_FEMORE_SN o SITUAZIONE_FEMORE_DX
    # dal momento che SITUAZIONE_COLONNA è importante ai fini di classificare i valori mancanti vengono sostituiti
    # con SITUAZIONE_FEMORE_SN. E se pure quello manca, si usa SITUAZIONE_FEMORE_DX
    # se manca sia SITUAZIONE_FEMORE_SN che SITUAZIONE_FEMORE_DX allora si mette 'na' in SITUAZIONE_COLONNA
    # one hot ecoding in fondo
    for row_index in range(0, tabella_completa.shape[0]):
        row = tabella_completa.loc[row_index, ['1 SITUAZIONE_COLONNA', '1 SITUAZIONE_FEMORE_SN', '1 SITUAZIONE_FEMORE_DX']]
        if pd.isna(row['1 SITUAZIONE_COLONNA']):
            # se SITUAZIONE_COLONNA è nulla ma il valore accanto di SITUAZIONE_FEMORE_SN non lo è, allora assegno al
            # valore mancante il valore di SITUAZIONE_FEMORE_SN
            if not pd.isna(row['1 SITUAZIONE_FEMORE_SN']):
                tabella_completa.loc[row_index,'1 SITUAZIONE_COLONNA']=row['1 SITUAZIONE_FEMORE_SN']
            # SITUAZIONE_FEMORE_SN è nullo allora uso SITUAZIONE_FEMORE_DX per sostituire
            elif not pd.isna(row['1 SITUAZIONE_FEMORE_DX']):
                tabella_completa.loc[row_index, '1 SITUAZIONE_COLONNA'] = row['1 SITUAZIONE_FEMORE_DX']
    # questo ha effetto solo se SITUAZIONE_COLONNA, SITUAZIONE_FEMORE_SN, SITUAZIONE_FEMORE_DX erano tutti null
    # tabella_completa['1 SITUAZIONE_COLONNA'].fillna('na', inplace=True)
    # endregion

    # region null di SITUAZIONE_FEMORE_SN vengono sostituiti con i valori di SITUAZIONE_FEMORE_DX o SITUAZIONE_COLONNA
    # se succede che sia SITUAZIONE_FEMORE_DX che SITUAZIONE_COLONNA sono null allora in SITUAZIONE_FEMORE_SN metto 'na'
    # one hot ecoding in fondo
    for row_index in range(0, tabella_completa.shape[0]):
        row = tabella_completa.loc[row_index, ['1 SITUAZIONE_FEMORE_SN', '1 SITUAZIONE_FEMORE_DX', '1 SITUAZIONE_COLONNA']]
        if pd.isna(row['1 SITUAZIONE_FEMORE_SN']):
            # se SITUAZIONE_FEMORE_SN è nulla ma il valore accanto di SITUAZIONE_FEMORE_DX non lo è, allora assegno al
            # valore mancante il valore di SITUAZIONE_FEMORE_SN
            if not pd.isna(row['1 SITUAZIONE_FEMORE_DX']):
                tabella_completa.loc[row_index, '1 SITUAZIONE_FEMORE_SN']=row['1 SITUAZIONE_FEMORE_DX']
            # in questo caso SITUAZIONE_FEMORE_DX è nulla allora uso SITUAZIONE_COLONNA
            elif not pd.isna(row['1 SITUAZIONE_COLONNA']):
                tabella_completa.loc[row_index, '1 SITUAZIONE_FEMORE_SN'] = row['1 SITUAZIONE_COLONNA']
    # questo ha effetto solo se SITUAZIONE_COLONNA, SITUAZIONE_FEMORE_SN, SITUAZIONE_FEMORE_DX erano tutti null
    # tabella_completa['1 SITUAZIONE_FEMORE_SN'].fillna('na', inplace=True)
    # endregion

    # region null di SITUAZIONE_FEMORE_DX vengono sostituiti con i valori di SITUAZIONE_FEMORE_SN o SITUAZIONE_COLONNA
    # 'na' nel caso in cui SITUAZIONE_FEMORE_SN, SITUAZIONE_COLONNA entrambi null
    for row_index in range(0, tabella_completa.shape[0]):
        row = tabella_completa.loc[
            row_index, ['1 SITUAZIONE_FEMORE_DX', '1 SITUAZIONE_FEMORE_SN', '1 SITUAZIONE_COLONNA']]
        if pd.isna(row['1 SITUAZIONE_FEMORE_DX']):
            if not pd.isna(row['1 SITUAZIONE_FEMORE_SN']):
                tabella_completa.loc[row_index, '1 SITUAZIONE_FEMORE_DX'] = row['1 SITUAZIONE_FEMORE_SN']
            elif not pd.isna(row['1 SITUAZIONE_COLONNA']):
                tabella_completa.loc[row_index, '1 SITUAZIONE_FEMORE_DX'] = row['1 SITUAZIONE_COLONNA']
    # tabella_completa['1 SITUAZIONE_FEMORE_DX'].fillna('na', inplace=True)
    # endregion

    #region prevedo FRAX_COLLO_FEMORE_INTERO usando FRAX_FRATTURE_MAGGIORI_INTERO con la regressione
    # non serve a molto dato che ci sono solo 37 valori mancanti
    col_name_da_prevedere = '1 FRAX_COLLO_FEMORE_INTERO'
    col_name_usando = '1 FRAX_FRATTURE_MAGGIORI_INTERO'
    # ritorna il modello che uso per predire e pol_fratures che serve per trasformare le x in formato adatto per il modello
    model, pol_features = polinomial_regression(col_name_x = col_name_usando, col_name_y = col_name_da_prevedere, frame = tabella_completa,plt_show=False, degree_= 2)
    for row_index in range(0, tabella_completa.shape[0]):
        # solo quello che mi serve
        row = tabella_completa.loc[row_index, [col_name_usando, col_name_da_prevedere]]
        # la colonna da prevedere deve essere null, ma quella che uso come supporto no
        if np.isnan(row[col_name_da_prevedere]) and not np.isnan(row[col_name_usando]):
            predicted = model.predict(pol_features.fit_transform(row[col_name_usando].reshape(1,-1)))
            tabella_completa.loc[row_index,col_name_da_prevedere]=predicted
    #endregion

    #region prevedo FRAX_FRATTURE_MAGGIORI_INTERO usando FRAX_COLLO_FEMORE_INTERO  con la regressione
    # non serve a molto dato che ci sono solo 17 valori mancanti
    col_name_da_prevedere = '1 FRAX_FRATTURE_MAGGIORI_INTERO'
    col_name_usando = '1 DEFRA_INTERO'
    # ritorna il modello che uso per predire e pol_fratures che serve per trasformare le x in formato adatto per il modello
    model, pol_features = polinomial_regression(col_name_x = col_name_usando, col_name_y = col_name_da_prevedere, frame = tabella_completa,plt_show=False, degree_= 3)
    for row_index in range(0, tabella_completa.shape[0]):
        # solo quello che mi serve
        row = tabella_completa.loc[row_index, [col_name_usando, col_name_da_prevedere]]
        # la colonna da prevedere deve essere null, ma quella che uso come supporto no
        if np.isnan(row[col_name_da_prevedere]) and not row[col_name_usando]==0:
            predicted = model.predict(pol_features.fit_transform(row[col_name_usando].reshape(1,-1)))
            tabella_completa.loc[row_index,col_name_da_prevedere]=predicted
    #endregion

    #region prevedo DEFRA_INTERO usando FRAX_FRATTURE_MAGGIORI_INTERO con la regressione
    # uso la colonna FRAX_FRATTURE_MAGGIORI_INTERO (x) per prevedere DEFRA_INTERO(y) perchè ce una dipendeneza lineare tra loro

    col_name_da_prevedere = '1 DEFRA_INTERO'
    col_name_usando = '1 FRAX_FRATTURE_MAGGIORI_INTERO'

    model, pol_features = polinomial_regression(col_name_x = col_name_usando,
                                                col_name_y = col_name_da_prevedere, frame = tabella_completa, plt_show=False,
                                                degree_=1)
    for row_index in range(0, tabella_completa.shape[0]):
        row = tabella_completa.loc[row_index, [col_name_usando, col_name_da_prevedere]]
        # se DEFRA_INTERO è 0 allora uso il modello lineare.. ma solo se FRAX_FRATTURE_MAGGIORI_INTERO non è null
        if row[col_name_da_prevedere] == 0 and not np.isnan(row[col_name_usando]):
            # pol_features serve qui per trasformare l'input x in un formato adatto per il modello
            predicted = model.predict(pol_features.fit_transform(row[col_name_usando].reshape(1,-1)))
            tabella_completa.loc[row_index,col_name_da_prevedere]=predicted
    #endregion

    tabella_completa['1 SITUAZIONE_COLONNA'].fillna('na', inplace=True)
    tabella_completa['1 SITUAZIONE_FEMORE_SN'].fillna('na', inplace=True)
    tabella_completa['1 SITUAZIONE_FEMORE_DX'].fillna('na', inplace=True)

    # vettorizato INDAGINI_APPROFONDIMENTO_LISTA
    tabella_completa['1 INDAGINI_APPROFONDIMENTO_LISTA'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_INDAGINI_APPROFONDIMENTO_LISTA =\
        vectorize('1 INDAGINI_APPROFONDIMENTO_LISTA', tabella_completa, prefix='ial')

    # vettorizato SOSPENSIONE_TERAPIA_FARMACO
    tabella_completa['1 SOSPENSIONE_TERAPIA_FARMACO'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_SOSPENSIONE_TERAPIA_FARMACO\
        = vectorize('1 SOSPENSIONE_TERAPIA_FARMACO', tabella_completa, prefix= 'stf', n_gram_range=(1,1))

    # vettorizato ALTRO
    tabella_completa['1 ALTRO'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_ALTRO = vectorize('1 ALTRO', tabella_completa, prefix= 'altr')

    # vettorizato INTOLLERANZE
    tabella_completa['1 INTOLLERANZE'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_INTOLLERANZE = \
        vectorize('1 INTOLLERANZE', tabella_completa, prefix='i', n_gram_range=(1, 1))

    # vettorizato ALLERGIE
    tabella_completa['1 ALLERGIE'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_ALLERGIE = \
        vectorize('1 ALLERGIE', tabella_completa, prefix='a', n_gram_range=(2, 2))

    # vettorizato DISLIPIDEMIA_TERAPIA
    tabella_completa['1 DISLIPIDEMIA_TERAPIA'].fillna('na', inplace=True)
    # (1,1) perchè sono quasi tutte parole singole
    tabella_completa, nomi_nuove_colonne_vectorized_DISLIPIDEMIA_TERAPIA = \
        vectorize('1 DISLIPIDEMIA_TERAPIA', tabella_completa, prefix='dt', n_gram_range=(1, 1))

    # vettorizato NEOPLASIA_MAMMARIA_TERAPIA
    tabella_completa['1 NEOPLASIA_MAMMARIA_TERAPIA'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_NEOPLASIA_MAMMARIA_TERAPIA = \
        vectorize('1 NEOPLASIA_MAMMARIA_TERAPIA', tabella_completa, prefix='nmt', n_gram_range=(2, 2))

    # vettorizato PATOLOGIE_UTERINE_DIAGNOSI
    tabella_completa['1 PATOLOGIE_UTERINE_DIAGNOSI'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_PATOLOGIE_UTERINE_DIAGNOSI = \
        vectorize('1 PATOLOGIE_UTERINE_DIAGNOSI', tabella_completa, prefix= 'pud', n_gram_range=(1,2))



    # vettorizato VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA
    tabella_completa['1 VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA = vectorize('1 VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA', tabella_completa, 'vdtol')

    # vettorizato TERAPIA_ALTRO
    tabella_completa['1 TERAPIA_ALTRO'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_TERAPIA_ALTRO = vectorize('1 TERAPIA_ALTRO', tabella_completa, 'ta')

    # vettorizzato ALTRE_PATOLOGIE
    tabella_completa['1 ALTRE_PATOLOGIE'].fillna('na', inplace=True)
    tabella_completa, nomi_nuove_colonne_vectorized_ALTRE_PATOLOGIE = vectorize('1 ALTRE_PATOLOGIE', tabella_completa, 'ap')

    # one hot encoding SITUAZIONE_FEMORE_DX
    tabella_completa['1 SITUAZIONE_FEMORE_DX'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_DX = one_hot_encode(tabella_completa, '1 SITUAZIONE_FEMORE_DX', '^.*', 'sfd')

    # one hot encoding SITUAZIONE_FEMORE_SN
    tabella_completa['1 SITUAZIONE_FEMORE_SN'].fillna('na', inplace=True)
    tabella_completa, nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_SN = one_hot_encode(tabella_completa, '1 SITUAZIONE_FEMORE_SN','^.*', 'sfs')

    # one hot encoding SITUAZIONE_COLONNA
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
    for row_index in range(0, tabella_completa.shape[0]):
        row = tabella_completa.loc[row_index, '1 ABUSO_FUMO']
        if pd.isnull(row):
            tabella_completa.loc[row_index, '1 ABUSO_FUMO'] = 0
        elif row[0] == '<':
            tabella_completa.loc[row_index, '1 ABUSO_FUMO'] = 1
        elif row[0] == '>':
            tabella_completa.loc[row_index, '1 ABUSO_FUMO'] = 2



    # one hot encoding di TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA
    tabella_completa['1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'].fillna("na,0 anni", inplace=True)
    tabella_completa, nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = \
        one_hot_encode(tabella_completa, "1 TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA","(^[a-zA-Z]+(([+](\s[a-zA-Z]*|[a-zA-Z]*))|(\s[+](\s[a-zA-Z]*))|(\s[+][a-zA-Z]*)){0,1})",'too')

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

    # region altro
    tabella_completa['1 FRATTURA_VERTEBRE'].fillna(0, inplace=True)
    tabella_completa['1 FRATTURA_VERTEBRE'].replace('piu di 1', 2, inplace=True)

    # sostituisco i null con 0
    tabella_completa['1 TERAPIA_ALTRO_CHECKBOX'].fillna(0, inplace=True)
    tabella_completa['1 FRATTURA_FEMORE'].fillna(0, inplace=True)
    tabella_completa['1 VITAMINA_D'].fillna(0, inplace=True)
    tabella_completa['1 TBS_COLONNA_APPLICABILE'].fillna(0, inplace=True)
    tabella_completa['1 TBS_COLONNA_VALORE'].fillna(0, inplace=True)

    # sostituisco variabili categoriali con dei numeri
    tabella_completa['1 SEX'].replace('F', 0, inplace=True)
    tabella_completa['1 SEX'].replace('M', 1, inplace=True)

    # sostituisco i null con la media della colonna
    # alcuni null di FRAX_FRATTURE_MAGGIORI_INTERO sono stati sostituiti con reg. polinomiale usando DEFRA_INTERO come supporto
    # alcuni null avevo 0 nella colonna DEFRA_INTERO e quindi non potevo applicare reg. polinomiale. dunue sostituisco con la media
    tabella_completa['1 FRAX_FRATTURE_MAGGIORI_INTERO'].fillna(
        tabella_completa['1 FRAX_FRATTURE_MAGGIORI_INTERO'].mean(), inplace=True)
    tabella_completa['1 FRAX_COLLO_FEMORE_INTERO'].fillna(tabella_completa['1 FRAX_COLLO_FEMORE_INTERO'].mean(),
                                                          inplace=True)
    # acuni valori di DEFRA_INTERO non sono riuscito a sostituirli per causa della colonna di supporto FRAX_FRATTURE_MAGGIORI_INTERO null
    # allora applico la media
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
        '1 SEX',
        '1 TERAPIA_OSTEOPROTETTIVA_ORMONALE', # quella sopra non fa un cazzo
        'XXX_TERAPIA_OST_ORM_ANNI_XXX',#fa niente e anche quella sopra
        '1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA',
        'XXX_TERAPIA_OST_SPEC_ANNI_XXX',# fa niente e anche quella sopra
        '1 VITAMINA_D_TERAPIA_OSTEOPROTETTIVA',
        '1 TERAPIA_ALTRO_CHECKBOX',
        '1 TERAPIA_COMPLIANCE',
        '1 BMI',
        '1 FRATTURE',
        '1 USO_CORTISONE',
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
        '1 CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX',
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
        '1 ALLERGIE_CHECKBOX',
        '1 INTOLLERANZE_CHECKBOX'] + \
        nomi_colonne_onehotencoded_STATO_MENOPAUSALE + \
        nomi_colonne_onehotencoded_CAUSE_OSTEOPOROSI_SECONDARIA+\
        nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA+\
        nomi_colonne_onehotencoded_TERAPIA_STATO + \
        nomi_colonne_onehotencode_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA+\
        nomi_colonne_onehotencoded_SITUAZIONE_COLONNA + \
        nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_SN +\
        nomi_colonne_onehotencoded_SITUAZIONE_FEMORE_DX +\
        nomi_colonne_onehotencoded_ULTIMA_MESTRUAZIONE +\
        nomi_nuove_colonne_vectorized_TERAPIA_ALTRO + \
        nomi_nuove_colonne_vectorized_ALTRE_PATOLOGIE + \
        nomi_nuove_colonne_vectorized_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA + \
        nomi_nuove_colonne_vectorized_PATOLOGIE_UTERINE_DIAGNOSI + \
        nomi_nuove_colonne_vectorized_NEOPLASIA_MAMMARIA_TERAPIA + \
        nomi_nuove_colonne_vectorized_DISLIPIDEMIA_TERAPIA + \
        nomi_nuove_colonne_vectorized_ALLERGIE+ \
        nomi_nuove_colonne_vectorized_INTOLLERANZE+ \
        nomi_nuove_colonne_vectorized_ALTRO+ \
        nomi_nuove_colonne_vectorized_SOSPENSIONE_TERAPIA_FARMACO + \
        nomi_nuove_colonne_vectorized_INDAGINI_APPROFONDIMENTO_LISTA\
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
        '1 SOSPENSIONE_TERAPIA_CHECKBOX',
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
    Ritorna il rapporto tra le righe nulle indovinate (external accuracy) e il totale delle righe nulle
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
def no_null_accuracy_score(X, true_Y, model):
    '''
    Qual è la proporzione di elementi non nulli indovinati
    X sono le istanze da prevedere del testset e Y_true sono le risposte(testset)
    '''
    predicted_Y = model.predict(X)
    tot_righe_non_nulle = 0
    tot_righe_non_nulle_indovinate = 0
    # andave bene anche true_Y.shape[0] pechè hanno lo stesso numero di righe
    for row_index in range(0, X.shape[0]):
        row_ptedicted_Y = predicted_Y[row_index, :]
        row_true_Y = true_Y.iloc[row_index, :].values
        # se l'isesima riga non è complettamente nulla (almeno un 1 in qualche posizione)
        if np.any(row_true_Y):
            tot_righe_non_nulle += 1
            # e se il modello ha indovinato correttamente la riga non nulla
            if  list(row_ptedicted_Y) == list(row_true_Y):
                tot_righe_non_nulle_indovinate += 1

    # print("tot {}, ind {}, totot {}, rapp {}".format(tot_righe_nulle,tot_righe_nulle_indovinate,X.shape[0],tot_righe_nulle/X.shape[0]))
    return tot_righe_non_nulle_indovinate / tot_righe_non_nulle

class Proposizione:
    def __init__(self, operatore, nome_variabile_operando1, valore_costante_operando2):
        self.operatore = operatore
        self.nome_variabile_operando1 = nome_variabile_operando1
        self.valore_costante_operando2 = valore_costante_operando2

    def valuta(self, istanza):
        if eval( str(istanza[self.nome_variabile_operando1])+self.operatore+self.valore_costante_operando2)==True:
            return True
        else:
            return False

    def __str__(self):
        return self.nome_variabile_operando1+" "+self.operatore+" "+self.valore_costante_operando2

class Regola:
    proposizioni = []

    def __init__(self, predizione, proposizioni, gs):
        self.predizione = predizione
        self.proposizioni = proposizioni
        self.gs = gs

    '''def add_preposizione(self, proposizione):
        self.proposizioni.append(proposizione)'''

    def valuta(self, istanza):
        for prop in self.proposizioni:
            if prop.valuta(istanza) == False:
                return False
        return self.predizione

    def __str__(self):
        if isinstance(self.proposizioni, list):
            output = ""
            for prop in self.proposizioni:
                if prop!=self.proposizioni[-1]:
                    output += str(prop)+" AND\n"
                else:
                    output+=str(prop)+": "+self.predizione+" "+self.gs
                    return output
        else:
            return ": "+self.predizione+" "+self.gs





if __name__ == '__main__':
    main()
