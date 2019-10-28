from sklearn.metrics import recall_score,  precision_score, confusion_matrix
#TERAPIE_OSTEOPROTETTIVE_CHECKBOX
# 0.897
import pandas as pd
import main as mn
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn.tree import DecisionTreeClassifier
'''
default: 0.875, 0.883, 0.871, 0.873, 0.883, 0.881
max_deapth 5: 0.895, 0.891, 0.892, 0.892
max_depth 4: 0.888, 0.889
max_deapth 6: 0.882
        
max_depth 5, max_leaf_nodes = 25: 0.895, 0.888, 0.891
                              15: 0.896, 0.895, 0.894, 0.895, 95
                              10: 0.891, 0.891, 
                              20: 0.893, 0.893
                              17: 0.893
                              14: 0.896, 0.897, 97 
                              13: 95, 96, 97
                              12: 95, 94
                              
max_depth 5, max_leaf_nodes=14, min_samples_split=9: 0.898, 898, 898, 897, 897 ***
                              
'''
def main():
    #tabella_completa = pd.read_csv("osteo.csv")
    #tabella_ridotta = mn.preprocessamento(tabella_completa)
    tabella_ridotta = pd.read_csv("osteo_r.csv")

    X = tabella_ridotta.iloc[:, :-5]
    Y = tabella_ridotta.iloc[:, -4:-3]

    skf = StratifiedKFold(n_splits=4)

    tree = DecisionTreeClassifier(max_depth=5, max_leaf_nodes=14, min_samples_split=9)
    avg_ext_train_score = 0
    avg_ext_test_score =  0

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

    Y_predicted = tree.predict(testX)
    conf_matrix = confusion_matrix(testY,Y_predicted)
    print(conf_matrix)
    #print("recall: "+str(recall_score(testY,Y_predicted)))
    #print("precision: "+str(precision_score(testY,Y_predicted)))

    print('TERAPIE_OSTEOPROTETTIVE_CHECKBOX')
    print("avg ext: {}, {}".format(
        *[round(avg / skf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))


if __name__ == '__main__':
    main()