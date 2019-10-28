from sklearn.metrics import recall_score,  precision_score, confusion_matrix

#VITAMINA_D_TERAPIA_CHECKBOX

import pandas as pd
import main as mn
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn.tree import DecisionTreeClassifier

'''
default: 0.968, 0.966, 0.971, 0.968, 0.968
max_depth 2: 0.975, 0.973, 0.975
max_depth 3: 0.975, 0.975
max_depth 4: 0.975, 0.975
max_depth 5: 0.975
max_depth 6: 0.975
max_depth 7: 975
max_depth 12: 975
max_depth 15: 973

max_depth 5, max_leaf_nodes = 20: 975
                              40: 977, 975, 975
                              50: 975
                              60: 977, 977, 976,975***
                              70: 975
                              80: 977, 975*2
                              
da provare altri 
'''
def main():
    #tabella_completa = pd.read_csv("osteo.csv")
    #tabella_ridotta = mn.preprocessamento(tabella_completa)

    tabella_ridotta = pd.read_csv("osteo_r.csv")
    X = tabella_ridotta.iloc[:, :-5]
    Y = tabella_ridotta.iloc[:, -3:-2]

    skf = StratifiedKFold(n_splits=4)

    tree = DecisionTreeClassifier(max_depth=5, max_leaf_nodes=60)
    avg_ext_train_score = 0
    avg_ext_test_score = 0

    trainX = []
    trainY = []
    testX = []
    testY = []
    for train_indexes, test_indexes in skf.split(X, Y):
        trainX = X.iloc[train_indexes, :]
        trainY = Y.iloc[train_indexes, :]
        testX = X.iloc[test_indexes, :]
        testY = Y.iloc[test_indexes, :]
        tree.fit(trainX, trainY)
        avg_ext_test_score += tree.score(testX, testY)
        avg_ext_train_score += tree.score(trainX, trainY)
        print(tree.score(testX,testY))

    Y_predicted = tree.predict(testX)
    conf_matrix = confusion_matrix(testY, Y_predicted)
    print(conf_matrix)

    print('VITAMINA_D_TERAPIA_CHECKBOX')
    print("avg ext: {}, {}".format(
        *[round(avg / skf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))


if __name__ == '__main__':
    main()