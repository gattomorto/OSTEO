from sklearn.metrics import recall_score,  precision_score, confusion_matrix
#TERAPIE ORMONALI CHECKBOX
# 0.981
'''
default: 0.981, 0.978, 0.978, 0.866, 0.979, 0.978, 0.977, 0.977
max_depth 1: 0.975
max_depth 2: 0.981, 0.981, 0.981
max_depth 3: 0.98, 0.979, 0.98, 0.979
max_depth 4: 0.979, 0.979
max_depth 5: 0.978 0.978
max_depth 7: 0.976, 0.977

max_depth 2, max_leaf_nodes 25: 0.981, 0.981
                            35: 0.981
                            45: 0.981
                            60: 0.981
                            10: 0.981
                            2:  0.975

'''
import pandas as pd
import main as mn
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn.tree import DecisionTreeClassifier

def main():
    tabella_completa = pd.read_csv("osteo.csv")
    #tabella_ridotta = mn.preprocessamento(tabella_completa)
    tabella_ridotta = pd.read_csv("osteo_r.csv")

    X = tabella_ridotta.iloc[:, :-5]
    Y = tabella_ridotta.iloc[:, -5:-4]

    skf = StratifiedKFold(n_splits=4)

    tree = DecisionTreeClassifier(max_depth=2)
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

    #mn.print_feature_importances(tree, trainX)
    Y_predicted = tree.predict(testX)
    conf_matrix = confusion_matrix(testY,Y_predicted)
    print(conf_matrix)
    #print("recall: "+str(recall_score(testY,Y_predicted)))
    #print("precision: "+str(precision_score(testY,Y_predicted)))



    print('TERAPIE_ORMONALI_CHECKBOX')
    print("avg ext: {}, {}".format(
        *[round(avg / skf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))


if __name__ == '__main__':
    main()