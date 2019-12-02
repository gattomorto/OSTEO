import pandas as pd
import numpy as np
import re
from sklearn.feature_extraction.text import TfidfVectorizer
from sqlalchemy import create_engine
import json
from main import df_column_uniquify, remove_stopwords_and_stem, preprocessamento_nuovo3

def main():
    preprocessa_per_java2()

def preprocessa_per_java2():
    db_connection_str = 'mysql+pymysql://utente_web:CMOREL96T45@localhost/CMO2'
    db_connection = create_engine(db_connection_str)
    tabella_completa = pd.read_sql(
        'select * from Anamnesi inner join Diagnosi on Anamnesi.PATIENT_KEY = Diagnosi.PATIENT_KEY and Anamnesi.SCAN_DATE = Diagnosi.SCAN_DATE inner join PATIENT on Anamnesi.PATIENT_KEY = PATIENT.PATIENT_KEY inner join Spine on Anamnesi.PATIENT_KEY = Spine.PATIENT_KEY and Anamnesi.SCAN_DATE = Spine.SCAN_DATE inner join ScanAnalysis on Anamnesi.PATIENT_KEY = ScanAnalysis.PATIENT_KEY and Anamnesi.SCAN_DATE = ScanAnalysis.SCAN_DATE'
        #' where Anamnesi.SCAN_DATE < "2019-05-01"',
        ,
        con=db_connection)

    tabella_preprocessata, colname_to_ngram, stemmed_to_original = preprocessamento_nuovo3(tabella_completa, is_single_instance=False)

    # we don't keep instances where class is missing
    #tabella_preprocessata.dropna(subset=[class_name], inplace=True)
    #tabella_preprocessata.reset_index(drop=True, inplace=True)

    file = open('colnametongram.txt', 'wt')
    file.write(str(colname_to_ngram))
    file.close()
    file = open('/var/www/sto/stemmed_to_original.txt', 'wt')
    json.dump(stemmed_to_original, file)
    file.close()

    tabella_preprocessata.to_csv('perJAVA.csv', index = False)



if __name__ == '__main__':
    main()
