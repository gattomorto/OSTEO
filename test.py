import pandas as pd
from sqlalchemy import create_engine
from sqlalchemy import text
from lib import preprocess, Regole, df_column_uniquify
import json

def risorgi_test(test_di_java):
    db_connection_str = 'mysql+pymysql://utente_web:CMOREL96T45@localhost/CMO2'
    db_connection = create_engine(db_connection_str)
    tabella_completa = pd.read_sql(
        'select * from Anamnesi inner join Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE'
        #' where Anamnesi.SCAN_DATE < "2019-05-01"',
        ,
        con=db_connection)
    tabella_completa.replace(r"'", value='', inplace=True, regex=True)
    tabella_completa.rename(columns={'PAROLOGIA_ESOFAGEA': 'PATOLOGIA_ESOFAGEA'}, inplace=True)

    tabella_completa = df_column_uniquify(tabella_completa)

    instances_ok = []
    for index_test in range(0, test_di_java.shape[0]):
        # qui ce scritto PRIMARY_KEY
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
    print("test_da_java: {}, risorto: {}".format(test_di_java.shape[0],output.shape[0]))
    return output
def leggo_regole_dal_db_e_verifico_accuracy():
    class_names = [
        #'CALCIO_SUPPLEMENTAZIONE_CHECKBOX',
        'TERAPIE_ORMONALI_CHECKBOX',#[0]
        'TERAPIE_ORMONALI_LISTA',#[1]
        'TERAPIE_OSTEOPROTETTIVE_CHECKBOX',#[2]
        'TERAPIE_OSTEOPROTETTIVE_LISTA',#[3]
        'VITAMINA_D_TERAPIA_CHECKBOX',#[4]
        'VITAMINA_D_TERAPIA_LISTA',#[5]
        'VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',#[6]
        'VITAMINA_D_SUPPLEMENTAZIONE_LISTA',#[7]
         'CALCIO_SUPPLEMENTAZIONE_CHECKBOX',#[8]
        'CALCIO_SUPPLEMENTAZIONE_LISTA'] #[9]

    #class_names = [class_names[0]]


    for class_name in class_names:
        db_connection_str = 'mysql+pymysql://utente_web:CMOREL96T45@localhost/CMO2'
        db_connection = create_engine(db_connection_str)

        t = text("SELECT * FROM regole where terapia = '{}'".format(class_name))
        result = db_connection.execute(t).fetchone()
        reg_ref = result['regola_refined']
        reg_not_ref = result['regola_not_refined']
        reg_user_read_not_ref = result['user_readable_not_ref']
        reg_user_read_ref = result['user_readable_ref']

        rules_ref = Regole(reg_ref)
        rules_not_ref = Regole(reg_not_ref)
        rules_user_not_ref = Regole(reg_user_read_not_ref)
        rules_user_ref = Regole(reg_user_read_ref)

        # print(rules_user_not_ref)

        # print(rules)

        # ATTENZIONE: this is not a random testset, it's a stratified one, bases on a particular class
        # it is produced by java, so first run java
        #todo capire perchè qui mi da errore se cella contiene testo con virgola
        # e perchè nel preprocessamento no... forse pechè npn cerecano virgole
        test = pd.read_csv('/home/dadawg/PycharmProjects/untitled1/{}_perpython.csv'.format(class_name), quotechar="'")
        #test_x = test.iloc[:, :-1]
        #test_y = test.iloc[:, -1]
        # aggiungo le colonne con il testo a test_x
        #test_x_text = add_text_columns(test_x)

        risorto = risorgi_test(test)

        print(class_name)
        print(accuracy_rules4(risorto, test[class_name], rules_user_not_ref))
        print(accuracy_rules4(risorto, test[class_name], rules_user_ref))
        print('\n')
def  accuracy_rules4(test_x, test_y, rules):
    #test_x.reset_index(drop=True, inplace=True)

    does_know = 0
    predicted_right = 0
    doesnt_know = 0

    num_instances = test_x.shape[0]
    stemmed_to_original = json.load(open("/var/www/sto/stemmed_to_original.txt"))
    for row_index in range(0, num_instances):

        if row_index == 172:
            c = -0

        instance_x = test_x.iloc[row_index, :].copy()
        true_Y = test_y.values[row_index]
        #preprocessed_instance_x = preprocessamento_singolo(instance_x)
        preprocessed_instance_x = preprocess(instance_x.to_frame().transpose(), is_single_instance=True)
        predicted_y, golden_rule, props_not_satisfied = rules.predict(preprocessed_instance_x)


        print(golden_rule.get_medic_readable_version(preprocessed_instance_x,stemmed_to_original))

        #print('{}: {}'.format(row_index,predicted_y))

        if predicted_y is None:
            doesnt_know += 1
        else:
            does_know += 1
            if str(predicted_y) == str(true_Y):
                predicted_right += 1

    return predicted_right / does_know, doesnt_know / num_instances


def main():
    leggo_regole_dal_db_e_verifico_accuracy()

if __name__ == '__main__':
    main()