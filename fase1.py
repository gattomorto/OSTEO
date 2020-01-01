import pandas as pd
from sqlalchemy import create_engine
from lib import preprocess, user, password, db_name

def main():
    preprocessa_per_java()

def preprocessa_per_java():
    db_connection_str = 'mysql+pymysql://{}:{}@localhost/{}'.format(user,password,db_name)
    db_connection = create_engine(db_connection_str)
    tabella_completa = pd.read_sql(
        'select * from Anamnesi inner join '
        'Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join '
        'PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join '
        'Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join '
        'ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE'
        #' where Anamnesi.SCAN_DATE < "2018-12-01"'
        ,
        con=db_connection)

    tabella_preprocessata = preprocess(tabella_completa)
    tabella_preprocessata.to_csv('perJAVA.csv', index = False)

if __name__ == '__main__':
    main()
