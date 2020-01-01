import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from sqlalchemy import text
from lib import preprocess, Formulae, df_column_uniquify, password, user, db_name, class_columns
import copy

def risorgi_test(test_di_java):
    db_connection_str = 'mysql+pymysql://{}:{}@localhost/{}'.format(user,password,db_name)
    db_connection = create_engine(db_connection_str)
    tabella_completa = pd.read_sql(
        'select * from Anamnesi inner join Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE'
        #' where Anamnesi.SCAN_DATE < "2019-05-01"'
        ,
        con=db_connection)
    tabella_completa.replace(r"'", value='', inplace=True, regex=True)
    tabella_completa.rename(columns={'PAROLOGIA_ESOFAGEA': 'PATOLOGIA_ESOFAGEA'}, inplace=True)

    tabella_completa = df_column_uniquify(tabella_completa)

    instances_ok = []
    for index_test in range(0, test_di_java.shape[0]):
        # qui ce scritto "PRIMARY_KEY"
        pk = list(test_di_java)[0]
        pk_test = test_di_java.loc[index_test, pk]

        sd = list(test_di_java)[1]
        sd_test = test_di_java.loc[index_test, sd]


        for index_completa in range(0, tabella_completa.shape[0]):
            pk_completa = tabella_completa.loc[index_completa, 'PATIENT_KEY']
            sd_completa = tabella_completa.loc[index_completa, 'SCAN_DATE']
            sd_completa = str(sd_completa)

            pk_test = pk_test.replace('\n', '')
            pk_completa = pk_completa.replace('\n', '')
            sd_test = sd_test.replace('\n', '')
            sd_completa = sd_completa.replace('\n', '')

            if pk_test == pk_completa and sd_test == sd_completa:
                instances_ok.append(tabella_completa.loc[index_completa])

    output = pd.DataFrame(instances_ok)

    output.reset_index(drop=True, inplace=True)
    print("num instances in test_test: {}, number of instances revived: {}".format(test_di_java.shape[0],output.shape[0]))
    return output

def leggo_regole_dal_db_e_verifico_accuracy():

    for class_name in class_columns:
        db_connection_str = 'mysql+pymysql://{}:{}@localhost/{}'.format(user, password, db_name)
        db_connection = create_engine(db_connection_str)

        t = text("SELECT * FROM regole_test where terapia = '{}'".format(class_name))
        result = db_connection.execute(t).fetchone()
        reg_user_read_not_ref = result['not_refined']
        reg_user_read_ref = result['refined']

        formulaes_not_refined = Formulae(reg_user_read_not_ref)
        formulaes_refined = Formulae(reg_user_read_ref)


        # ATTENZIONE: this is not a random testset, it's a stratified one, bases on a particular class
        # it is produced by java, so first run java
        test = pd.read_csv('/home/dadawg/PycharmProjects/untitled1/{}_perpython.csv'.format(class_name), quotechar="'")

        risorto = risorgi_test(test)

        print(class_name)
        print(accuracy_rules(risorto, test[class_name], formulaes_not_refined))
        print(accuracy_rules(risorto, test[class_name], formulaes_refined))
        print('\n')

def accuracy_rules(test_x, test_y, formulaes):
    #test_x.reset_index(drop=True, inplace=True)

    does_know = 0
    predicted_right = 0
    doesnt_know = 0

    num_instances = test_x.shape[0]
    for row_index in range(0, num_instances):
        formulaes_copy = copy.deepcopy(formulaes)

        instance_x = test_x.iloc[row_index, :].copy()
        true_Y = test_y.values[row_index]
        preprocessed_instance_x = preprocess(instance_x.to_frame().transpose())
        y = preprocessed_instance_x.T.squeeze()
        true_formula = formulaes_copy.predict(y)

        if true_formula is None:
            doesnt_know += 1
            #print('{}: {}'.format(row_index,'null'))

        else:
            predicted_y = true_formula.prediction
            #print(true_formula.get_user_readable_version(preprocessed_instance_x))
            #print()
            #print('{}: {}'.format(row_index,predicted_y))

            does_know += 1
            if str(predicted_y) == str(true_Y):
                predicted_right += 1

    # in case every prediction is an 'idk', a division by zero happens
    if does_know == 0:
        return np.nan, doesnt_know / num_instances

    return predicted_right / does_know, doesnt_know / num_instances

def main():
    leggo_regole_dal_db_e_verifico_accuracy()

if __name__ == '__main__':
    main()