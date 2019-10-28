# VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX
from sklearn.metrics import recall_score,  precision_score, confusion_matrix

import pandas as pd
import main as mn
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn.tree import DecisionTreeClassifier
'''
default: 852, 846, 85
max_depth 2: 812, 815
max_depth 4: 877, 875, 875, 877, 875
max_depth 7: 867
max_depth 6: 864
max_depth 5: 878, 878, 873, 878, 868, 875
max_depth 3: 0.88, 0.879, 878, 873*20

max_depth 3, max_leaf_nodes 20: 873
                           40: 873
                           50: 873
                           80: 873
                           10: 873
                           5: 875
                           4: 878*3
                           3: 816

'''
def main():

    #tabella_completa = pd.read_csv("osteo.csv")
    #tabella_ridotta = mn.preprocessamento(tabella_completa)

    tabella_ridotta = pd.read_csv("osteo_r.csv")

    X = tabella_ridotta.iloc[:, :-5]
    Y = tabella_ridotta.iloc[:, -2:-1]


    tree = DecisionTreeClassifier(max_depth=4)
    avg_ext_train_score = 0
    avg_ext_test_score = 0

    trainX = []
    trainY = []
    testX = []
    testY = []
    skf = StratifiedKFold(n_splits=4)
    for train_indexes, test_indexes in skf.split(X, Y):
        trainX = X.iloc[train_indexes, :]
        trainY = Y.iloc[train_indexes, :]
        testX = X.iloc[test_indexes, :]
        testY = Y.iloc[test_indexes, :]
        tree.fit(trainX, trainY)
        avg_ext_test_score += tree.score(testX, testY)
        avg_ext_train_score += tree.score(trainX, trainY)

    Y_predicted = tree.predict(testX)
    conf_matrix = confusion_matrix(testY, Y_predicted)
    print(conf_matrix)

    print('VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX')
    print("avg ext: {}, {}".format(
        *[round(avg / skf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))


if __name__ == '__main__':
    main()