import pandas as pd
import numpy as np
import re
from nltk.stem.snowball import SnowballStemmer
from nltk.corpus import stopwords
from nltk.tokenize import RegexpTokenizer
# nltk.download('stopwords')
from sklearn.feature_extraction.text import TfidfVectorizer
from sqlalchemy import create_engine
from sqlalchemy import text
import json
from lib import preprocess, Regole

#sys.stderr = open("log.txt", "a")

# todo controlla che tutti i regex che abbiano anche '_' e dove serve áéíóúàèìòùàèìòù
# todo sost. vitamina con vit
# todo fare quando per esempio sostituisci con il principio fai il controllo che se non lo trovi.. percè potrebbere aver aggiunto uno nuovo
# convertire in int le colonne tipo AGE

#todo in valuta mi arriva cause osteoporosi secondaria con Menopausa prematura\r\nM.I.C.I\r\n va bene? significa ce il preprocessamento signolo non lo ha tolto
#todo inoltre situazione femore dx = '' non va bene.. controllare ongn preprocessato nuovo
def main():
    singola_istanza()


def singola_istanza():

    class_names = [
                   'TERAPIE_ORMONALI_CHECKBOX',
                   'TERAPIE_ORMONALI_LISTA',
                   'TERAPIE_OSTEOPROTETTIVE_CHECKBOX',
                   'TERAPIE_OSTEOPROTETTIVE_LISTA',
                   'VITAMINA_D_TERAPIA_CHECKBOX',
                   'VITAMINA_D_TERAPIA_LISTA',
                   'VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',
                   'VITAMINA_D_SUPPLEMENTAZIONE_LISTA',
                   'CALCIO_SUPPLEMENTAZIONE_CHECKBOX',
                   'CALCIO_SUPPLEMENTAZIONE_LISTA']

    # pk = sys.argv[1]
    # datascan = sys.argv[2]

    # file = open("testfile.txt","w")
    # file.write("pane")

    pk = '1K2C1915ZB0681809'
    datascan = '2018-12-19'

    # print(pk)
    # print(datascan)

    db_connection_str = 'mysql+pymysql://utente_web:CMOREL96T45@localhost/CMO2'
    db_connection = create_engine(db_connection_str)
    df = pd.read_sql(
        'select * from Anamnesi inner join Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE where Anamnesi.PATIENT_KEY = "{}" and Anamnesi.SCAN_DATE= "{}"'.format(
            pk, datascan),
        con=db_connection)


    preprocessato = preprocess(df,is_single_instance=True)

    for class_name in class_names:
        t = text("SELECT * FROM regole WHERE terapia = '{}'".format(class_name))
        result = db_connection.execute(t).fetchone()
        reg = result['user_readable_not_ref']
        rules = Regole(reg)

        predicted, rule_predicted, _ = rules.predict(preprocessato)

        if rule_predicted is not None:
            rule_predicted = rule_predicted.get_medic_readable_version(preprocessato, None)

        print(predicted)
        print('\n')
        print(rule_predicted)
        print('\n')

    exit(5)




if __name__ == '__main__':
    main()
