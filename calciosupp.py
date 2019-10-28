# CALCIO_SUPPLEMENTAZIONE_CHECKBOX
# ok avg ext: 1.0, 0.866
import pandas as pd
import main as mn
from sklearn.model_selection import train_test_split, KFold, StratifiedKFold, StratifiedShuffleSplit
from sklearn.tree import DecisionTreeClassifier
from sklearn.metrics import recall_score,  precision_score, confusion_matrix

'''
default: 871,87
max_depth 2: 87*2
          3: 879*2
          1: 873*2
          4: 884, 885*2
          5: 881
          6: 881
          10: 881
          20: 875
          
(max_depth=4, max_leaf_nodes=2: 873
                            10: 883
                            15: 885 883
          

'''
def main():
    #tabella_completa = pd.read_csv("osteo.csv")
    #tabella_ridotta = mn.preprocessamento(tabella_completa)
    tabella_ridotta = pd.read_csv("osteo_r.csv")
    X = tabella_ridotta.iloc[:, :-5]
    Y = tabella_ridotta.iloc[:, -1].to_frame()

    skf = StratifiedKFold(n_splits=4)

    tree = DecisionTreeClassifier(max_depth=4)
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

    Y_predicted = tree.predict(testX)
    conf_matrix = confusion_matrix(testY, Y_predicted)
    print(conf_matrix)

    print('CALCIO_SUPPLEMENTAZIONE_CHECKBOX')
    print("avg ext: {}, {}".format(
        *[round(avg / skf.get_n_splits(), 3) for avg in [avg_ext_train_score, avg_ext_test_score]]))


if __name__ == '__main__':
    main()