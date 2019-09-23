import mysql.connector
import pandas as pd
import re
import random

mydb = mysql.connector.connect(host = "localhost",user = "root", passwd = "cazzodicane",database = "ggg")
#print(mydb)

cur = mydb.cursor()

terapie = pd.read_csv("terapie.csv")

#AGGIUNGO E RIEMPIO LA TABELLA PRINCIPI ATTIVI
'''
cur.execute("CREATE TABLE IF NOT EXISTS principi_attivi("
            "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            "principio VARCHAR(255) NOT NULL UNIQUE)")
col_principio = terapie.iloc[:,1]#terapie[['Principio']] #terapie.iloc[:,1]
col_principio_set = set(col_principio)
#print(col_principio_set)
for princ in col_principio_set:
    stmnt = "INSERT INTO principi_attivi (principio) VALUES ('{}')".format(princ)
    cur.execute(stmnt)
mydb.commit()'''


#AGGIUNO E RIEMPIO LA TABELLA TERAPIE AL DB
'''
cur.execute("CREATE TABLE IF NOT EXISTS terapie("
            "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            "terapia VARCHAR(255) NOT NULL UNIQUE)")
col_terapia = terapie.iloc[:,0]
col_terapia = set(col_terapia)
for ter in col_terapia:
    stmnt = "INSERT INTO terapie (terapia) VALUES ('{}')".format(ter)
    cur.execute(stmnt)
mydb.commit()
'''


#AGGIUNGO E RIEMPIO LA TABELLA DOSE AL DB
'''
dose_col = terapie.iloc[:,3]
dose_col = list(dose_col) #trasformo in lista per usare le funzioni delle liste
dose_col = [x for x in dose_col if str(x) !='nan'] #tolgo il nan
fun = lambda s: re.sub(r'\s(UI|mg|gocce|mcg|\+\s\d*)',"",s) #lascia solo il numero
dose_col = list(map(fun, dose_col))#applico ad ogni elemento la sub
dose_col = set(dose_col) #per eliminare i doppioni attenzione fallo alla fine perchè da 5 gocce e 5 mg si formano 2 volte 5

cur.execute("CREATE TABLE IF NOT EXISTS dosi("
            "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            "dose FLOAT NOT NULL UNIQUE)")
for dose in dose_col:
    stmnt = "INSERT INTO dosi (dose) VALUES ({})".format(dose)
    cur.execute(stmnt)
mydb.commit()'''


# AGGIUNGO LA TABELLA CURE AL DB
'''
cur.execute("CREATE TABLE IF NOT EXISTS cure("
            "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            "id_terapia INT NOT NULL,"
            "id_principio_attivo INT NOT NULL,"
            "id_dose INT NOT NULL,"
            "FOREIGN KEY (id_terapia) REFERENCES terapie (id) ON UPDATE CASCADE ON DELETE CASCADE,"
            "FOREIGN KEY (id_dose) REFERENCES dosi (id) ON UPDATE CASCADE ON DELETE CASCADE,"
            "FOREIGN KEY (id_principio_attivo) REFERENCES principi_attivi (id) ON UPDATE CASCADE ON DELETE CASCADE)")
mydb.commit()'''


#AGGIUNGO LA TABELLA DIAGNOSI_CURA
'''
cur.execute("CREATE TABLE IF NOT EXISTS diagnosi_cura("
            "PATIENT_KEY VARCHAR(24) NOT NULL,"
            "SCAN_DATE DATE NOT NULL,"
            "id_cura INT NOT NULL,"
            "PRIMARY KEY (PATIENT_KEY,SCAN_DATE,id_cura))")
            #"FOREIGN KEY (id_cura) REFERENCES cure (id) ON UPDATE CASCADE ON DELETE CASCADE,"
            #"FOREIGN KEY (PATIENT_KEY, SCAN_DATE) REFERENCES diagnosi (PATIENT_KEY,SCAN_DATE))")
mydb.commit()'''


#POPULO LA TABELLA CURE
'''
tups = [ ( random.randrange(1,5),  random.randrange(35,50),   random.randrange(1,21) ) for i in range(10) ]
x = "select {}".format(str(tups[0]))

for tup in tups:
    stmnt = "INSERT INTO cure (id_terapia,id_principio_attivo,id_dose) VALUES {}".format(str(tup))
    cur.execute(stmnt)
mydb.commit()'''




#QUESTA PARTE SI OCCUPA DI LEGGERE LA TABELLA diagnosi E CONVERTIRE TUTTE LE CURE IN diagnosi_cura
'''
#data una cura in formato stringa la converte in id della cura corrispondente della tabella 'cure'
def get_id_cura(s):
    length_s = len(s)
    return length_s


#quando ho identificato che un paziente è sottoposto ad una cura allora aggiungo a 'diagnosi_cura'
#questa funzione puo scattare piu volte per un paziente
def add_to_diagnosi_cura(patient_key,scan_date,id_cura):
    tup = (patient_key,scan_date,id_cura)
    stmnt = "INSERT INTO diagnosi_cura (PATIENT_KEY, SCAN_DATE, id_cura) VALUES {}".format(str(tup))
    cur.execute(stmnt)
    #print(stmnt)
    
#mi interessa solo ciò che ha prescritto il medico
sttmnt = "SELECT PATIENT_KEY, SCAN_DATE, " \
         "TERAPIE_ORMONALI_CHECKBOX, TERAPIE_ORMONALI_LISTA, " \
         "TERAPIE_OSTEOPROTETTIVE_CHECKBOX, TERAPIE_OSTEOPROTETTIVE_LISTA, " \
         "VITAMINA_D_TERAPIA_CHECKBOX, VITAMINA_D_TERAPIA_LISTA, " \
         "VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX, VITAMINA_D_SUPPLEMENTAZIONE_LISTA, " \
         "CALCIO_SUPPLEMENTAZIONE_CHECKBOX, CALCIO_SUPPLEMENTAZIONE_LISTA " \
         "FROM diagnosi"
cur.execute(sttmnt)

results = cur.fetchmany(size=1000)
#per ogni paziente controllo che cure sono state prescitte ad esso
for row in results:
   patient_key = row[0]
   scan_date = str(row[1])
   
   if(row[2]==1):#TERAPIE_ORMONALI_CHECKBOX
       cura_string = row[3]#TERAPIE_ORMONALI_LISTA
       id_cura = 3#get_id_cura(cura_string)
       add_to_diagnosi_cura(patient_key,scan_date,id_cura)
   if(row[4] == 1):#TERAPIE_OSTEOPROTETTIVE_CHECKBOX
       cura_string = row[5]#TERAPIE_OSTEOPROTETTIVE_LISTA
       id_cura = 5#get_id_cura(cura_string)
       add_to_diagnosi_cura(patient_key, scan_date, id_cura)
   if (row[6] == 1):  # VITAMINA_D_TERAPIA_CHECKBOX
       cura_string = row[7]  # VITAMINA_D_TERAPIA_LISTA
       id_cura = 7#get_id_cura(cura_string)
       add_to_diagnosi_cura(patient_key, scan_date, id_cura)
   if (row[8] == 1):  #VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX
       cura_string = row[9]  # VITAMINA_D_SUPPLEMENTAZIONE_LISTA
       id_cura = 9#get_id_cura(cura_string)
       add_to_diagnosi_cura(patient_key, scan_date, id_cura)
   if (row[10] == 1):  # CALCIO_SUPPLEMENTAZIONE_CHECKBOX
       cura_string = row[11]  #CALCIO_SUPPLEMENTAZIONE_LISTA
       id_cura = 11#get_id_cura(cura_string)
       add_to_diagnosi_cura(patient_key, scan_date, id_cura)
mydb.commit()
'''

def test():
    '''
    PATIENT_KEY  SCAN_DATE  id_cura
    199BU        3/9/4        8
    199BU        3/9/4        1
    199BU        3/9/4        5
    521ZZ        1/8/8        1
    521ZZ        1/8/8        9

    Lo scopo è quello di ricostruire la tabella 'diagnosi'
    a partire da 'dianosi_cura' per poi confrontare quella ricostruita con l'originale

    risultato:
    PATIENT_KEY  SCAN_DATE  TERAPIE_ORMONALI_CHECKBOX  TERAPIE_ORMONALI_LISTA  TERAPIE_OSTEOPROTETTIVE_CHECKBOX  TERAPIE_OSTEOPROTETTIVE_LISTA ...
    199BU        3/9/4                 1               calciferolo 55mg 2/die               1                    desonumab e.v. 50000 UI
    521ZZ        1/8/8                 0               NULL                                 1                    estradiolo 6 gocce al giorno

    '''

    #inversa di get_id_cura(s)
    def get_string_cura(id):
        cure = {
            0 : "colecalciferolo 30 gocce 3 volte a sett",
            1 : "alendronato in pastiglie",
            2 : "risendronato 50mcg",
            3 : "flacone di ibandronato sotto cute 2/sett",
            4 : "iniezioni sotto cutanee di tibolone 1 volta",
            5 : "colecalciferolo 25000 UI 1 volta al mese",
            6 : "gocce orali di calcifediolo"
        }
        if id not in cure.keys():
            return cure[random.randrange(0,6)]
        else:
            return cure[id]

    #dato l'id di una terapia ritorna il nome. (in stile vecchio del DB)
    def get_string_terapia(id):
        terapie = {
            1 : "TERAPIE_ORMONALI",
            2 : "TERAPIE_OSTEOPROTETTIVE",
            3 : "VITAMINA_D_TERAPIA",
            4 : "VITAMINA_D_SUPPLEMENTAZIONE",
            5 : "CALCIO_SUPPLEMENTAZIONE"
        }
        return terapie[id];

    #creo la tabella test:
    statement= "CREATE TABLE IF NOT EXISTS diagnosi_test(" \
               "PATIENT_KEY VARCHAR(24) NOT NULL," \
               "SCAN_DATE DATE NOT NULL," \
               "TERAPIE_ORMONALI_CHECKBOX TINYINT(1)," \
               "TERAPIE_ORMONALI_LISTA VARCHAR(512)," \
               "TERAPIE_OSTEOPROTETTIVE_CHECKBOX TINYINT(1)," \
               "TERAPIE_OSTEOPROTETTIVE_LISTA VARCHAR(512)," \
               "VITAMINA_D_TERAPIA_CHECKBOX TINYINT(1)," \
               "VITAMINA_D_TERAPIA_LISTA VARCHAR(512)," \
               "VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX TINYINT(1)," \
               "VITAMINA_D_SUPPLEMENTAZIONE_LISTA VARCHAR(512)," \
               "CALCIO_SUPPLEMENTAZIONE_CHECKBOX TINYINT(1)," \
               "CALCIO_SUPPLEMENTAZIONE_LISTA VARCHAR(512)," \
               "PRIMARY KEY (PATIENT_KEY,SCAN_DATE))"
    #print(statement)
    cur.execute(statement); mydb.commit()

    #per ogni diagnosi voglio avere le cure prescritte
    statement = "SELECT PATIENT_KEY, SCAN_DATE FROM diagnosi_cura"; cur.execute(statement);
    results = cur.fetchall()
    results = list(set(results))#qui ho le diagnosi univoche
    #prima di inserire cancello la tabella, altrimenti se lancio più volte la funzione da errore di PK duplicata
    statement = "DELETE FROM diagnosi_test"; cur.execute(statement); mydb.commit()
    #per ogni diagnosi
    for diagnosi in results:
        patient_key = diagnosi[0]
        scan_date = diagnosi[1]
        #inserisco la diagnosi univoca con tutte le checkbox = 0 e tutte le liste = null (ancora da riempire con le cure)
        statement = "INSERT INTO diagnosi_test VALUES('{}','{}',0,NULL,0,NULL,0,NULL,0,NULL,0,NULL)"\
            .format(patient_key, scan_date); cur.execute(statement); mydb.commit()
        #data una particolare diagnosi, ottengo gli id delle cure prescritte (per riempire la diagnosi appena creata)
        statement = "SELECT id_cura FROM diagnosi_cura WHERE PATIENT_KEY = '{}' AND SCAN_DATE = '{}'"\
            .format(patient_key,scan_date); cur.execute(statement)
        #se diagnosi = (199BU, 3/9/4) allora results2 = (8,1,5)
        results2 = cur.fetchall()
        #ogni cura andra a selezionare un particolare checkbox nella riga diagnosi
        for id_cura in results2:
            #print(patient_key, scan_date, id_cura[0])
            #id_cura è una tupla di un valore
            #mi serve sapere che terapia è stata scelta per la cura 'id_cura' per selezionare il checkbox corrispondente
            statement = "SELECT id_terapia FROM cure WHERE id = {}".format(id_cura[0]); cur.execute(statement);
            results3 = cur.fetchone() #ho agito sull'id (PK) perciò ce solo un risultato

def main():
    test()



if __name__ == "__main__":
    main()