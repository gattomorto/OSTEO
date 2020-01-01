import sys
import pandas as pd
from sqlalchemy import create_engine
from sqlalchemy import text
from lib import preprocess, Formulae, user, password, db_name,class_columns

#sys.stderr = open("loghh.txt", "a")

def main():
    suggest()

def suggest():
    pk = '1951727468SINISCALC'
    datascan = '2019-11-19'

    pk = sys.argv[1]
    datascan = sys.argv[2]

    db_connection_str = 'mysql+pymysql://{}:{}@localhost/{}'.format(user,password,db_name)
    db_connection = create_engine(db_connection_str)
    df = pd.read_sql(
        'select * from Anamnesi inner join '
        'Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join '
        'PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join '
        'Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join '
        'ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE where '
        'Anamnesi.PATIENT_KEY = "{}" and Anamnesi.SCAN_DATE = "{}" '.format(pk, datascan),
        con=db_connection)

    preprocessato = preprocess(df).T.squeeze()

    for class_name in class_columns:
        t = text("SELECT * FROM regole WHERE terapia = '{}'".format(class_name))
        result = db_connection.execute(t).fetchone()
        reg = result['refined']
        formulaes = Formulae(reg)

        true_formula = formulaes.predict(preprocessato)

        predicted = 'idk'
        formula_predicted = 'idk'

        if true_formula is not None:
            predicted = true_formula.prediction
            formula_predicted = true_formula.get_user_readable_version(preprocessato)

        print(predicted)
        print('\n')
        print(formula_predicted)
        print('\n')

if __name__ == '__main__':
    main()
