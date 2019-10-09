import pandas as pd
from sklearn.tree import DecisionTreeClassifier
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn import datasets
from sklearn.metrics import confusion_matrix
from sklearn.model_selection import train_test_split

'''

'''

def main():
    tabella_completa = pd.read_csv("osteo.csv")

    #seleziono le colonne da usare per la predizione
    l = [
         '1 AGE',
         '1 TERAPIA_OSTEOPROTETTIVA_ORMONALE',
         '1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA',
         '1 VITAMINA_D_TERAPIA_OSTEOPROTETTIVA',
         '1 BMI',
         '1 FRATTURE',
         '1 FRATTURA_FEMORE',
         '1 FRATTURA_SITI_DIVERSI',
         '1 FRATTURA_FAMILIARITA',
         '1 ABUSO_FUMO_CHECKBOX',
         '1 USO_CORTISONE_CHECKBOX',
         '1 MALATTIE_ATTUALI_CHECKBOX',
         '1 MALATTIE_ATTUALI_ARTRITE_REUM',
         '1 MALATTIE_ATTUALI_ARTRITE_PSOR',
         '1 MALATTIE_ATTUALI_LUPUS',
         '1 MALATTIE_ATTUALI_SCLERODERMIA',
         '1 MALATTIE_ATTUALI_ALTRE_CONNETTIVITI',
         '1 CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX',
         #'1 CAUSE_OSTEOPOROSI_SECONDARIA', (da formattare)
         '1 PATOLOGIE_UTERINE_CHECKBOX',
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
        '1 VITAMINA_D_CHECKBOX', #diminuito forse
        '1 VITAMINA_D', #aumentato tanto
        '1 INTOLLERANZE_CHECKBOX',
        '1 OSTEOPOROSI_GRAVE', #aumentato tanto
        '1 FRAX_APPLICABILE',
        '1 FRAX_FRATTURE_MAGGIORI_INTERO',#aumento discreto
        '1 FRAX_COLLO_FEMORE_INTERO',#aumento discreto
        '1 TBS_COLONNA_APPLICABILE',#nessun aumento
        '1 TBS_COLONNA_VALORE',#nessun aumento
        '1 DEFRA_INTERO',


         '1 TERAPIE_ORMONALI_CHECKBOX',
         '1 TERAPIE_OSTEOPROTETTIVE_CHECKBOX',
         '1 VITAMINA_D_TERAPIA_CHECKBOX',
        #'1 VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',
        #'1 CALCIO_SUPPLEMENTAZIONE_CHECKBOX'
    ]
    num_classi = 3


    # sostituisco i null con 0
    tabella_completa['1 FRATTURA_FEMORE'].fillna(0, inplace=True)
    tabella_completa['1 VITAMINA_D'].fillna(0, inplace=True)
    tabella_completa['1 TBS_COLONNA_APPLICABILE'].fillna(0, inplace=True)
    tabella_completa['1 TBS_COLONNA_VALORE'].fillna(0, inplace=True)

    # sostituisco i null con la media della colonna
    tabella_completa['1 FRAX_FRATTURE_MAGGIORI_INTERO'].fillna(tabella_completa['1 FRAX_FRATTURE_MAGGIORI_INTERO'].mean(), inplace=True)
    tabella_completa['1 FRAX_COLLO_FEMORE_INTERO'].fillna(tabella_completa['1 FRAX_COLLO_FEMORE_INTERO'].mean(), inplace=True)
    tabella_completa['1 DEFRA_INTERO'].fillna(tabella_completa['1 DEFRA_INTERO'].mean(), inplace=True)


    tabella_ridotta = tabella_completa[l]
    X = tabella_ridotta.iloc[:, :-num_classi]
    Y = tabella_ridotta.iloc[:, -num_classi:]


    kf = StratifiedShuffleSplit(n_splits=50, test_size=0.25)
    tree = DecisionTreeClassifier()
    avg_train_score = 0
    avg_test_score = 0
    for train_indexes, test_indexes in kf.split(X, Y):
        # print(train_indexes,test_indexes)
        trainX = X.iloc[train_indexes, :]
        trainY = Y.iloc[train_indexes, :]
        testX = X.iloc[test_indexes, :]
        testY = Y.iloc[test_indexes, :]
        tree.fit(trainX, trainY)
        train_score = tree.score(trainX, trainY)
        test_score = tree.score(testX, testY)
        avg_test_score += test_score
        avg_train_score += train_score
        print("{}, {}".format(round(train_score,2),round(test_score ,2)))

    print("avg: {}, {}".format(*[round(avg/kf.get_n_splits(), 3) for avg in [avg_train_score, avg_test_score]]))


    '''tabella = pd.read_csv("film2.csv")
    X = tabella.iloc[:, :4]
    Y = tabella.iloc[:, 4:]

    kf = KFold(n_splits=tabella.shape[0]-8)
    tree = DecisionTreeClassifier(max_depth=5, splitter="best",min_samples_split=4)
    for train_indexes, test_indexes in kf.split(tabella):
        #print(train_indexes,test_indexes)
        trainX = X.iloc[train_indexes,:]
        trainY = Y.iloc[train_indexes,:]
        testX = X.iloc[test_indexes,:]
        testY = Y.iloc[test_indexes,:]
        tree.fit(trainX, trainY)
        print("accuracy on training subset: {}".format(tree.score(trainX, trainY)))
        print("accuracy on test subset: {}".format(tree.score(testX, testY)))
        p = tree.predict(testX)
        #print(p)
        #print(testY)'''



    '''#print(tabella)
 

    trainX, testX, trainY, testY = train_test_split(X,Y,test_size=0.25)
    print(testX)
    print(testY)
    tree = DecisionTreeClassifier()
    tree.fit(trainX, trainY)
    print("accuracy on training subset: {}".format(tree.score(trainX, trainY)))
    print("accuracy on test subset: {}".format(tree.score(testX, testY)))
    dtree_predictions = tree.predict(testX)
    print(dtree_predictions)'''




if __name__ == '__main__':
    main()