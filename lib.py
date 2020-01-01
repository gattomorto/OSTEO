import pandas as pd
import numpy as np
import re

from nltk.tokenize import RegexpTokenizer
from sklearn.preprocessing import MultiLabelBinarizer
from collections import Counter


user = 'utente_web'
password = 'CMOREL96T45'
db_name   = 'CMO'


# TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA(beginning)/TERAPIE_ORMONALI_LISTA(class)/ORM.SOST./C.O.(site)
ter_orm_kinds = ['tibolone', 'estradiolo + drospirenone', 'tsec, estrogeni coniugati equini 0,4 mg- bazedoxifene 20 mg', 'terapia ormonale sostitutiva per via transdermica']
# TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA(beginnig)/TERAPIE_OSTEOPROTETTIVE_LISTA(class)/OSTEOPROTETTIVA SPECIFICA(site)
ter_osteo_kinds = ['alendronato', 'risedronato', 'ibandronato', 'clodronato', 'raloxifene', 'bazedoxifene', 'denosumab', 'teriparatide', 'zoledronato']
# VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA(beginning)/VITAMINA_D_SUPPLEMENTAZIONE_LISTA(class)/VITAMINA D SUPPLEMENTAZIONE(site)
vit_d_sup_kinds = ['colecalciferolo 300.000ui', 'colecalciferolo 100.000ui', 'colecalciferolo 25.000ui', 'colecalciferolo 10.000ui', 'calcifediolo', 'supplementazione giornaliera di vit d3 (colecalciferolo) a dose  2000ui /die']

# VITAMINA_D_TERAPIA_LISTA(class)/VITAMINA D TERAPIA(site)
vit_d_ter_kinds = ['colecalciferolo', 'calcifediolo']
# CALCIO_SUPPLEMENTAZIONE_LISTA(class)/CALCIO SUPPLEMENTAZIONE(site)
calcio_supp_kinds = ['calcio citrato', 'calcio carbonato']

commons_PATOLOGIE_UTERINE_DIAGNOSI = ['isterectomia', 'fibromi', 'cisti', 'endometriosi']
commons_ALTRE_PATOLOGIE = ['vit d', 'eutirox']
commons_NEOPLASIA_MAMMARIA_TERAPIA = ['quadrantectomia/mastectomia', 'radioterapia', 'tamoxifene', 'anastrozolo', 'chemioterapia', 'letrozolo']
commons_DISLIPIDEMIA_TERAPIA = ['statine', 'integratori', 'dieta', 'ezetimibe']
commons_INTOLLERANZE = ['lattosio', 'bisfosfonati', 'alendronato', 'glutine', 'clodronato', 'risedronato', 'tos', 'colecalciferolo']
commons_ALLERGIE = ['nichel', 'asa', 'penicillina', 'fans', 'acetilsalicilico', 'paracetamolo', 'graminacee', 'augmentin', 'lattosio']
commons_TERAPIA_ALTRO = ['alendronato', 'vit d', '100.000ui','2.000ui','tos', 'natecal', 'clodronato', 'prolia', 'colecalciferolo', 'dxa', 'dibase', 'forsteo', 'fosavance', 'risedronato', '10.000ui', 'bisfosfonati', '25.000ui']
CAUSE_OSTEOPOROSI_SECONDARIA_kinds = ['diabete-insulino dipendente', 'osteogenesi imperfecta', 'ipertiroidismo non trattato per lungo tempo', 'ipogonadismo', 'menopausa prematura', 'malnutrizione cronica', 'm.i.c.i.', 'malattia cronica epatica come cirrosi/epatite cronica']

binary_columns = [
            'TERAPIA_OSTEOPROTETTIVA_ORMONALE',
            'TERAPIA_OSTEOPROTETTIVA_SPECIFICA',
            'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA',
            'TERAPIA_ALTRO_CHECKBOX',
            'TERAPIA_COMPLIANCE',
            'FRATTURA_SITI_DIVERSI',
            'FRATTURA_FAMILIARITA',
            'MALATTIE_ATTUALI_CHECKBOX',
            'MALATTIE_ATTUALI_ARTRITE_REUM',
            'MALATTIE_ATTUALI_ARTRITE_PSOR',
            'MALATTIE_ATTUALI_LUPUS',
            'MALATTIE_ATTUALI_SCLERODERMIA',
            'MALATTIE_ATTUALI_ALTRE_CONNETTIVITI',
            'CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX',
            'PATOLOGIE_UTERINE_CHECKBOX',
            'NEOPLASIA_CHECKBOX',
            'SINTOMI_VASOMOTORI',
            'SINTOMI_DISTROFICI',
            'DISLIPIDEMIA_CHECKBOX',
            'IPERTENSIONE',
            'RISCHIO_TEV',
            'PATOLOGIA_CARDIACA',
            'PATOLOGIA_VASCOLARE',
            'INSUFFICIENZA_RENALE',
            'PATOLOGIA_RESPIRATORIA',
            'PATOLOGIA_CAVO_ORALE_CHECKBOX',
            'PATOLOGIA_EPATICA',
            'PATOLOGIA_ESOFAGEA',
            'GASTRO_DUODENITE',
            'GASTRO_RESEZIONE',
            'RESEZIONE_INTESTINALE',
            'MICI',
            'VITAMINA_D_CHECKBOX',
            'ALLERGIE_CHECKBOX',
            'INTOLLERANZE_CHECKBOX',
            'OSTEOPOROSI_GRAVE',
            'VERTEBRE_NON_ANALIZZATE_CHECKBOX',
            'VERTEBRE_NON_ANALIZZATE_L1',
            'VERTEBRE_NON_ANALIZZATE_L2',
            'VERTEBRE_NON_ANALIZZATE_L3',
            'VERTEBRE_NON_ANALIZZATE_L4',
            'COLONNA_NON_ANALIZZABILE',
            'COLONNA_VALORI_SUPERIORI',
            'FEMORE_NON_ANALIZZABILE',
            'FRAX_APPLICABILE',
            'TBS_COLONNA_APPLICABILE',
            'DEFRA_APPLICABILE']
numeric_columns = [
    'PATIENT_KEY',
    'SCAN_DATE',
    'AGE',
    'ETA_MENOPAUSA',
    'ANNI_IN_MENOPAUSA',
    'BMI',
    'VITAMINA_D',
    'FRAX_FRATTURE_MAGGIORI_INTERO',
    'FRAX_COLLO_FEMORE_INTERO',
    'TBS_COLONNA_VALORE',
    'DEFRA_INTERO',
    'TOT_Tscore',
    'TOT_Zscore']
class_columns = [
                #'TERAPIE_ORMONALI_LISTA',  # ***************************
                'TERAPIE_ORMONALI_CHECKBOX',  # [0]
                'TERAPIE_ORMONALI_LISTA',  # [1]
                'TERAPIE_OSTEOPROTETTIVE_CHECKBOX',  # [2]
                'TERAPIE_OSTEOPROTETTIVE_LISTA',  # [3]
                'VITAMINA_D_TERAPIA_CHECKBOX',  # [4]
                'VITAMINA_D_TERAPIA_LISTA',  # [5]
                'VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX',  # [6]
                'VITAMINA_D_SUPPLEMENTAZIONE_LISTA',  # [7]
                'CALCIO_SUPPLEMENTAZIONE_CHECKBOX',  # [8]
                'CALCIO_SUPPLEMENTAZIONE_LISTA'
            ]
nominal_columns = [
                'STATO_MENOPAUSALE',
                'TERAPIA_STATO',
                'FRATTURA_VERTEBRE',
                'FRATTURA_FEMORE',
                'ABUSO_FUMO',
                'USO_CORTISONE',
                'SITUAZIONE_COLONNA',
                'SITUAZIONE_FEMORE_SN',
                'SITUAZIONE_FEMORE_DX']


def df_column_uniquify(df):
    df_columns = df.columns
    new_columns = []
    for item in df_columns:
        counter = 0
        newitem = item
        while newitem in new_columns:
            counter += 1
            newitem = "{}_{}".format(item, counter)
        new_columns.append(newitem)
    df.columns = new_columns
    return df


def word_frequency(instances):
    words = []
    tokenizer = RegexpTokenizer(r'[a-z]+')
    for _, item in instances['INTOLLERANZE'].iteritems():
        t =  tokenizer.tokenize(item.lower())
        if 'tollerato' in t:
            print(item)
        words += t
    counts = Counter(words)
    print(counts)


def preprocess(instances):
    one_hot_encoded_columns = []

    def one_hot_encode(instances, col_name, classes, sep = '\n'):
        # x is a list of tuples, each tuple contains classes present in some row of the column
        x = []
        # item is some row of the column
        for i ,item in instances[col_name].iteritems():
            #print('{}:{}'.format(i,item))
            # the tuple for the current row
            t = ()
            if item != '':
                # bear in mind that a row may contain multiple classes. For example the TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA column
                # might contain 'ibandronato capsule\nRaloxifene 20mg'
                # first of all we split the sentences and get: ['ibandronato capsule', 'Raloxifene 20mg']
                # for each sentence:
                for line in item.split(sep):
                    line = line.lower()
                    # we check if the sentence contains some class
                    for cl in classes:
                        # each sentence should contain one and only one class, so the it should fire once
                        if cl in line:
                            # add the class to the tuple
                            t = t + (cl,)
                # t for this example is ('ibandronato','raloxifene')
                x.append(t)
            else:
                x.append(t)

            '''print(i)
            print(item)
            print(t)'''


        # one column for each class
        new_columns = MultiLabelBinarizer(classes = classes).fit_transform(x)
        new_columns_names = ['{}_{}'.format(col_name,i) for i in range(0,len(classes)) ]
        new_columns= pd.DataFrame(new_columns, columns=new_columns_names)
        instances = pd.concat([instances, new_columns], axis=1)
        return instances, new_columns_names

    def one_hot_encode2(instances, col_name, classes, sep):
        tokenizer = RegexpTokenizer(sep)
        # x is a list of tuples, each tuple contains classes present in some row of the column
        x = []
        # item is some row of the column
        for i ,item in instances[col_name].iteritems():
            #print('{}:{}'.format(i,item))
            # the tuple for the current row
            t = ()
            item = item.lower()
            if item != '':
                for token in tokenizer.tokenize(item):
                    # we check if the sentence contains some class
                    for cl in classes:
                        # each sentence should contain one and only one class, so the if shoul fire once
                        if cl == token:
                            # add the class to the tuple
                            t = t + (cl,)
                # t for this example is ('ibandronato','raloxifene')
                x.append(t)
            else:
                x.append(t)


            '''print(i)
            print(item)
            print(t)'''


        # one column for each class
        new_columns = MultiLabelBinarizer(classes = classes).fit_transform(x)
        new_columns_names = ['{}_{}'.format(col_name,i) for i in range(0,len(classes)) ]
        new_columns= pd.DataFrame(new_columns, columns=new_columns_names)
        instances = pd.concat([instances, new_columns], axis=1)
        return instances, new_columns_names

    # region initial cleaning
    instances.reset_index(drop=True, inplace=True)
    # reading one row from db places None where values are missing while reading more than one places nan
    instances.replace(to_replace=[None], value=np.nan, inplace=True)
    # per il doppio bmi
    instances = df_column_uniquify(instances)
    instances.rename(columns={'PAROLOGIA_ESOFAGEA': 'PATOLOGIA_ESOFAGEA'}, inplace=True)
    instances.replace('NULL', value='', inplace=True)
    instances.replace(r"'", value='', inplace=True, regex=True)

    '''
    # this is bc the most recient patients might have not still recieved a diagnosis
    # to my understanding it takes at least a week for the medic to give a diagnosis
    x = datetime.today().date() - timedelta(days=15)
    instances = instances.loc[instances['SCAN_DATE'] <= x, :].copy()
    instances.reset_index(drop=True, inplace=True)
    '''
    # endregion

    # region fixing DEFRA_INTERO
    # this db is such a mess: TBS_COLONNA_APPLICABILE sometimes is null instead of 0, and where it's 0, TBS_COLONNA_VALORE = 0
    DEFRA_INTERO = 'DEFRA_INTERO'
    for row_index in range(0, instances.shape[0]):
        if instances.loc[row_index, DEFRA_INTERO] == 0:
            instances.loc[row_index, DEFRA_INTERO] = np.nan
    # endregion


    # region fixing TBS
    # this db is such a mess: TBS_COLONNA_APPLICABILE sometimes is null instead of 0, and where it's 0, TBS_COLONNA_VALORE = 0
    TBS_COLONNA_APPLICABILE = 'TBS_COLONNA_APPLICABILE'
    for row_index in range(0, instances.shape[0]):
        if pd.isna(instances.loc[row_index, TBS_COLONNA_APPLICABILE]):
            instances.loc[row_index, TBS_COLONNA_APPLICABILE] = 0

        if instances.loc[row_index, TBS_COLONNA_APPLICABILE] == 0:
            instances.loc[row_index, 'TBS_COLONNA_VALORE'] = np.nan
    # endregion

    # region fixing TERAPIA_ALTRO_CHECKBOX
    # TERAPIA_ALTRO_CHECKBOX is null instead of 0
    TERAPIA_ALTRO_CHECKBOX = 'TERAPIA_ALTRO_CHECKBOX'
    for row_index in range(0, instances.shape[0]):
        if pd.isna(instances.loc[row_index, TERAPIA_ALTRO_CHECKBOX]):
            instances.loc[row_index, TERAPIA_ALTRO_CHECKBOX] = 0
    # endregion

    # region ANNI_IN_MENOPAUSA
    # creates a new column with the difference between the current year and last period year
    # all nans in ULTIMA_MESTRUAZIONE transfer to ANNI_SENZA_MESTRUAZIONI
    for row_index in range(0, instances.shape[0]):
        scan = instances.loc[row_index, 'SCAN_DATE'].year
        um = instances.loc[row_index, 'ULTIMA_MESTRUAZIONE']
        adm = scan - um
        instances.loc[row_index, 'ANNI_IN_MENOPAUSA'] = adm
    # endregion

    # region unconfortable values for reg exp
    # some text has uncomfortable values for regular expressions, such as: <,=,>,(,...
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'ABUSO_FUMO'] = re.sub('> 10 sigarette/di', 'piu di 10 sigarette', instances.loc[row_index, 'ABUSO_FUMO'])
        instances.loc[row_index, 'ABUSO_FUMO'] = re.sub('<= 10 sigarette/di', 'meno di 10 sigarette', instances.loc[row_index, 'ABUSO_FUMO'])
        instances.loc[row_index, 'USO_CORTISONE'] = re.sub(r'>=\s5\smg\s\(Prednisone\)', 'piu di 5 mg', instances.loc[row_index, 'USO_CORTISONE'])
        instances.loc[row_index, 'USO_CORTISONE'] = re.sub('> 2.5 mg e < 5 mg', 'tra 2.5 e 5 mg', instances.loc[row_index, 'USO_CORTISONE'])
    # endregion

    # region removing junk
    '''
    many sentences taken from the html select list have the this form: \t\t\r\n\telement1\r\n\t\t\selement2\t\t\t\t\s\s
    we remove the stuff in front, in the back, and for some in the middle.
    '''
    back_front_junk_regex = r'^[\n\s\r\t]*(?=\w)|(?<=\w)[\s\t\n\r]*$'
    middle_junk = r'[\r\n\s\t]*(\r\n)+[\r\n\s\t]*'
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub( back_front_junk_regex, '', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'])

        if row_index == 93:
            i=0
        x = instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA']
        y = re.sub( back_front_junk_regex, '', x)
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = y

        instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'] = re.sub(back_front_junk_regex, '',instances.loc[row_index, 'CAUSE_OSTEOPOROSI_SECONDARIA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub( back_front_junk_regex, '', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])
        instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'] = re.sub(back_front_junk_regex, '',  instances.loc[row_index, 'TERAPIE_OSTEOPROTETTIVE_LISTA'])
        instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'CALCIO_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'] = re.sub(back_front_junk_regex, '', instances.loc[row_index, 'TERAPIE_ORMONALI_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_LISTA'] = re.sub(back_front_junk_regex,'', instances.loc[row_index, 'VITAMINA_D_TERAPIA_LISTA'])
        # removing the junk in middle and substituting it with \n
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'] = re.sub(middle_junk, '\n', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA'])
        instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'] = re.sub(middle_junk, '\n', instances.loc[row_index, 'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] = re.sub(middle_junk,'\n', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
    # endregion

    # region preprocessing VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA, VITAMINA_D_SUPPLEMENTAZIONE_LISTA
    # sostituisco a 10000UI 10.000UI e 25000 UI con 25.000UI in VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA(beginning)/VITAMINA_D_SUPPLEMENTAZIONE_LISTA(class)
    # perchè non voglio avere robe diverse
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'10000', r'10.000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'25000', r'25.000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'100000', r'100.000', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'] \
            = re.sub(r'\sUI', r'UI', instances.loc[row_index, 'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA'])

        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'10000', r'10.000', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'25000', r'25.000', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'100000', r'100.000', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
        instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'] \
            = re.sub(r'\sUI', r'UI', instances.loc[row_index, 'VITAMINA_D_SUPPLEMENTAZIONE_LISTA'])
    # endregion

    # region one hot encoding TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA, TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA, VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA
    instances, new_column_names_for_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA = one_hot_encode(instances,'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA', ter_orm_kinds)
    instances, new_column_names_for_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA = one_hot_encode(instances,'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA', ter_osteo_kinds)
    instances, new_column_names_for_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA = one_hot_encode(instances,'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA', vit_d_sup_kinds)
    one_hot_encoded_columns.extend(new_column_names_for_TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA)
    one_hot_encoded_columns.extend(new_column_names_for_TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA)
    one_hot_encoded_columns.extend(new_column_names_for_VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA)
    # endregion
    # region one hot encoding TERAPIA_ALTRO
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'10000', '10.000', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'25000', '25.000', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'100000', '100.000', instances.loc[row_index, 'TERAPIA_ALTRO'])
        # no 2000 on purpose, bc it can be mistaken for year 2000

        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'10\.000', '10.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'25\.000', '25.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'100\.000', '100.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'2\.000', '2.000ui', instances.loc[row_index, 'TERAPIA_ALTRO'])

        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VITAMINA\sD', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VIT\.\sD', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VIT\.D', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])
        instances.loc[row_index, 'TERAPIA_ALTRO'] = re.sub(r'VITD', 'VIT D', instances.loc[row_index, 'TERAPIA_ALTRO'])

    instances, new_column_names_for_TERAPIA_ALTRO = one_hot_encode2(instances, 'TERAPIA_ALTRO', commons_TERAPIA_ALTRO, sep=r'vit\sd|[a-z]+|100.000ui|2.000ui|25.000ui|10.000ui')
    one_hot_encoded_columns.extend(new_column_names_for_TERAPIA_ALTRO)
    # endregion
    # region one hot encoding ALTRE_PATOLOGIE
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'ALTRE_PATOLOGIE'] = re.sub(r'VITD', 'VIT D', instances.loc[row_index, 'ALTRE_PATOLOGIE'])
        instances.loc[row_index, 'ALTRE_PATOLOGIE'] = re.sub(r'VITAMINA D', 'VIT D ', instances.loc[row_index, 'ALTRE_PATOLOGIE'])

    instances, new_column_names_for_ALTRE_PATOLOGIE = one_hot_encode2(instances, 'ALTRE_PATOLOGIE', commons_ALTRE_PATOLOGIE, sep=r'vit\sd|eutirox')
    one_hot_encoded_columns.extend(new_column_names_for_ALTRE_PATOLOGIE)

    # endregion
    # region one hot encoding NEOPLASIA_MAMMARIA_TERAPIA
    instances['NEOPLASIA_MAMMARIA_TERAPIA'] = instances['NEOPLASIA_MAMMARIA_TERAPIA'].str.lower()
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = re.sub(r'radiotp', 'radioterapia', instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'])
        instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = re.sub(r'chemiotp', 'chemioterapia', instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'])
        instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = re.sub(r'anastrazolo', 'anastrozolo', instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'])

        x = instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA']
        # quadrantectomia and mastectomia are basically the same thing
        if 'quadrantectomia' in x or 'mastectomia' in x:
            x += ' quadrantectomia/mastectomia'
            instances.loc[row_index, 'NEOPLASIA_MAMMARIA_TERAPIA'] = x
    instances, new_column_names_for_NEOPLASIA_MAMMARIA_TERAPIA = one_hot_encode2(instances, 'NEOPLASIA_MAMMARIA_TERAPIA', commons_NEOPLASIA_MAMMARIA_TERAPIA, sep=r'[a-z/]+')
    one_hot_encoded_columns.extend(new_column_names_for_NEOPLASIA_MAMMARIA_TERAPIA)
    # endregion
    # region one hot encoding PATOLOGIE_UTERINE_DIAGNOSI
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ISTEROANNESSIECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ISTEROANNESSECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ISTEROENNESSIECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'ANNESSIECTOMIA', 'ISTERECTOMIA', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'FIBROMATOSO', 'FIBROMI', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'FIBROMATOSI', 'FIBROMI', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
        instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'] = re.sub(r'FIBROMA', 'FIBROMI', instances.loc[row_index, 'PATOLOGIE_UTERINE_DIAGNOSI'])
    instances, new_column_names_for_PATOLOGIE_UTERINE_DIAGNOSI = one_hot_encode2(instances, 'PATOLOGIE_UTERINE_DIAGNOSI', commons_PATOLOGIE_UTERINE_DIAGNOSI, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_PATOLOGIE_UTERINE_DIAGNOSI)
    # endregion
    # region one hot encoding DISLIPIDEMIA_TERAPIA
    instances['DISLIPIDEMIA_TERAPIA'] = instances['DISLIPIDEMIA_TERAPIA'].str.lower()
    for row_index in range(0, instances.shape[0]):
        instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'] = re.sub(r'statina', 'statine', instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'])
        instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'] = re.sub(r'integratore', 'integratori', instances.loc[row_index, 'DISLIPIDEMIA_TERAPIA'])

    instances, new_column_names_for_DISLIPIDEMIA_TERAPIA = one_hot_encode2(instances, 'DISLIPIDEMIA_TERAPIA', commons_DISLIPIDEMIA_TERAPIA, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_DISLIPIDEMIA_TERAPIA)

    # endregion
    # region one hot encoding ALLERGIE
    instances['ALLERGIE'] = instances['ALLERGIE'].str.lower()
    for row_index in range(0, instances.shape[0]):
        # aspirin and asa are the same thing
        instances.loc[row_index, 'ALLERGIE'] = re.sub(r'aspirina', 'asa', instances.loc[row_index, 'ALLERGIE'])
        instances.loc[row_index, 'ALLERGIE'] = re.sub(r'penicilline', 'penicillina', instances.loc[row_index, 'ALLERGIE'])

        x = instances.loc[row_index, 'ALLERGIE']
        # aspirin(asa) is contained in the class of fans, so if someone is allergic to fans, is also allergic to aspirin(asa)
        if 'fans' in x:
            x += ' asa'
            instances.loc[row_index, 'ALLERGIE'] = x
    instances, new_column_names_for_ALLERGIE = one_hot_encode2(instances, 'ALLERGIE', commons_ALLERGIE, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_ALLERGIE)

    # endregion
    # region one hot encoding INTOLLERANZE
    instances['INTOLLERANZE'] = instances['INTOLLERANZE'].str.lower()
    instances, new_column_names_for_INTOLLERANZE = one_hot_encode2(instances, 'INTOLLERANZE', commons_INTOLLERANZE, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_INTOLLERANZE)

    # endregion
    # region one hot encoding CAUSE_OSTEOPOROSI_SECONDARIA
    instances, new_column_names_for_CAUSE_OSTEOPOROSI_SECONDARIA = one_hot_encode(instances, 'CAUSE_OSTEOPOROSI_SECONDARIA', CAUSE_OSTEOPOROSI_SECONDARIA_kinds, sep=r'[a-z]+')
    one_hot_encoded_columns.extend(new_column_names_for_CAUSE_OSTEOPOROSI_SECONDARIA)

    # endregion

    # region fixing BMI
    # alcuni hanno -1
    instances['BMI'].replace(-1, np.nan, inplace=True)
    # endregion

    # region fillna
    instances['FRATTURA_VERTEBRE'].replace('', 'no fratture', inplace=True)
    instances['FRATTURA_FEMORE'].replace('','no fratture', inplace=True)
    instances['ABUSO_FUMO'].replace('','non fuma', inplace=True)
    instances['USO_CORTISONE'].replace('','non usa cortisone', inplace=True)
    instances['TERAPIA_ALTRO_CHECKBOX'].fillna(0, inplace=True)
    instances['STATO_MENOPAUSALE'].replace('', np.nan, inplace=True)
    instances['TERAPIA_STATO'].replace('', np.nan, inplace=True)
    instances['SITUAZIONE_FEMORE_SN'].replace('', np.nan, inplace=True)
    instances['SITUAZIONE_COLONNA'].replace('', np.nan, inplace=True)
    # classes
    instances['TERAPIE_ORMONALI_LISTA'].replace('', np.nan, inplace=True)
    instances['VITAMINA_D_TERAPIA_LISTA'].replace('', np.nan, inplace=True)
    instances['VITAMINA_D_SUPPLEMENTAZIONE_LISTA'].replace('', np.nan, inplace=True)
    instances['TERAPIE_OSTEOPROTETTIVE_LISTA'].replace('', np.nan, inplace=True)
    instances['CALCIO_SUPPLEMENTAZIONE_LISTA'].replace('', np.nan, inplace=True)



    # endregion

    # region class list preprocessing
    def class_list_preprocess(class_name, class_kinds):
        i = 0
        for row_index in range(0, instances.shape[0]):
            row = instances.loc[row_index, class_name]
            #print('{}:{}'.format(i, row))

            if not pd.isna(row):
                row = row.lower()
                row_assigned = False
                for kind in class_kinds:
                    if kind in row:
                        instances.loc[row_index, class_name] = kind
                        #print('\t' + kind)
                        row_assigned = True
                        break
                if row_assigned is False:
                    raise Exception
            i = i + 1
    class_list_preprocess('CALCIO_SUPPLEMENTAZIONE_LISTA',calcio_supp_kinds)
    class_list_preprocess('TERAPIE_OSTEOPROTETTIVE_LISTA',ter_osteo_kinds)
    class_list_preprocess('VITAMINA_D_SUPPLEMENTAZIONE_LISTA',vit_d_sup_kinds)
    class_list_preprocess('VITAMINA_D_TERAPIA_LISTA',vit_d_ter_kinds)
    class_list_preprocess('TERAPIE_ORMONALI_LISTA',ter_orm_kinds)
    # endregion

    final_columns = numeric_columns + binary_columns + nominal_columns + one_hot_encoded_columns + class_columns

    return instances[final_columns]


class Proposizione:
    def __init__(self, prop_string):
        self.operatore =re.search(r'(?<=\s)(<=|>=|=|<|>|!=|contiene|(non\scontiene))(?=\s)', prop_string).group(0)
        self.operando1 = re.search(r'(^\w+(?=\s(=|<|>|<=|>=|!=|non contiene)\s))|(^\w+(?=\s(=|<|>|<=|>=|!=|contiene)\s))', prop_string).group(0)
        self.operando2 = re.search(r'((?<=([=><])\s)[\w\s.,+-]+$)|((?<=contiene\sle\sparole\s#)[\w\s.]+(?=#$))', prop_string).group(0)

    def valuta(self, istanza):
        '''
        checks wether some proposition is true or false, and returns in accordance
        '''
        def is_number(s):
            try:
                float(s)
                return True
            except ValueError:
                return False

        # operando1 now contains the value of a column, not the column name
        operando1 = istanza[self.operando1]
        operando2 = self.operando2
        operatore = self.operatore

        # if a missing value is present, the proposision is false
        if pd.isna(operando1):
            return False

        if not is_number(operando1) or not is_number(operando2):
            operando1 = re.sub("'", '', operando1)
            operando2 = re.sub("'", '', operando2)
            operando1 = "'{}'".format(operando1)
            operando2 = "'{}'".format(operando2)
        else:
            operando1 = float(operando1)
            operando2 = float(operando2)
            operando1 = str(operando1)
            operando2 = str(operando2)

        if operatore == '=':
            operatore = '=='

        # some examples of what s might be
        # 1.0 >= 0.0
        # 'non fuma' == '>10 sigarette'
        s = operando1 + operatore + operando2

        if eval(s) is True:
            return True
        else:
            return False


    def __str__(self):
        if self.operatore == ':':
            return self.operando1 + "" + self.operatore + " " + self.operando2
        else:
            return self.operando1 + " " + self.operatore + " " + self.operando2

    def __eq__(self, other_prop):
        return self.operando1 == other_prop.operando1 and self.operatore == other_prop.operatore and self.operando2 == other_prop.operando2


class Clause:
    def __init__(self, clause_string):
        self.propositions, self.logical_operator = self.extract_propositions(clause_string)

    def evaluate(self,instance):
        # conjunctive clause
        if self.logical_operator == 'AND':
            for p in self.propositions:
                if p.valuta(instance) == False:
                    return False
            return True
        # disunctive clause
        elif self.logical_operator == 'OR':
            for p in self.propositions:
                if p.valuta(instance) == True:
                    return True
            return False

    def extract_propositions(self, clause_string):
        output = []

        if re.search('\sOR\s',clause_string)is not None:
            logical_operator = 'OR'
        else:
            logical_operator = 'AND'

        props = clause_string.split(' '+logical_operator+' ')

        for prop in props:
            output.append(Proposizione(prop))

        return output, logical_operator

    def __eq__(self, other_clause):
        if len(self) != len(other_clause) or self.logical_operator != other_clause.logical_operator:
            return False

        for i in range(0,len(self)):
            if self.propositions[i] != other_clause.propositions[i]:
                return False

        return True

    def __len__(self):
        return len(self.propositions)

    def __str__(self):
        output = '('
        for prop in self.propositions:
            if prop != self.propositions[len(self.propositions)-1]:
                output += str(prop) +' ' +self.logical_operator+' '
            else:
                output+=str(prop)+')'
        return output

class ConjunctiveFormula:
    def __init__(self,formula):
        self.mn = self.extract_mn(formula)
        self.prediction = self.extract_prediction(formula)
        # only keeping the clauses
        formula = re.sub(r'\s::.*','',formula)
        self.clauses=self.extract_clauses(formula)

    def evaluate(self,inst):
        for cl in self.clauses:
            if cl.evaluate(inst)==False:
                return False
        return True

    def extract_clauses(self, formula):
        output = []
        clauses_list = formula.split(') AND (')
        for cl in clauses_list:
            if cl != '':
                cl = re.sub(r'(^\()|(\).*)','',cl)
                output.append(Clause(cl))

        return output

    def extract_mn(self, formula):
        mn = re.findall(r'(?:(?<=\()|(?<=/))(\d+\.\d+)(?:(?=/)|(?=\)))',formula)
        return float(mn[0]),float(mn[1])

    def extract_prediction(self, formula):
        x = re.search(r'(?<=::\s)[\w\s.+,-/()]+(?=\s\(\d)', formula).group()
        return x

    def get_user_readable_version(self, inst):
        def remove_duplicate_clauses():
            no_duplicates = []
            for cl in self.clauses:
                if cl not in no_duplicates:
                    no_duplicates.append(cl)
            self.clauses = no_duplicates

        def simplify_disjunctive_clauses(inst):
            for cl in self.clauses:
                if cl.logical_operator == 'OR':
                    for prop in cl.propositions:
                        if prop.valuta(inst) == True:
                            cl.propositions = [prop]
                            cl.logical_operator = 'AND'
                            break

        ohe_column_to_kinds = {'TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA': ter_orm_kinds,
             'TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA': ter_osteo_kinds,
             'VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA': vit_d_sup_kinds,
             'PATOLOGIE_UTERINE_DIAGNOSI': commons_PATOLOGIE_UTERINE_DIAGNOSI,
             'ALTRE_PATOLOGIE': commons_ALTRE_PATOLOGIE,
             'NEOPLASIA_MAMMARIA_TERAPIA': commons_NEOPLASIA_MAMMARIA_TERAPIA,
             'DISLIPIDEMIA_TERAPIA': commons_DISLIPIDEMIA_TERAPIA,
             'INTOLLERANZE': commons_INTOLLERANZE,
             'ALLERGIE': commons_ALLERGIE,
             'TERAPIA_ALTRO': commons_TERAPIA_ALTRO,
             'CAUSE_OSTEOPOROSI_SECONDARIA': CAUSE_OSTEOPOROSI_SECONDARIA_kinds}

        def fix_binary_columns():
            for cl in self.clauses:
                for prop in cl.propositions:
                    if prop.operando1 in binary_columns:
                        if prop.operatore =='!=':
                            prop.operatore = '='
                            if prop.operando2 == '0':
                                prop.operando2 = '1'
                            elif prop.operando2 == '1':
                                prop.operando2 = '0'

                        prop.operatore = ':'

                        if prop.operando2 == '0':
                            prop.operando2 = 'no'
                        elif prop.operando2 == '1':
                            prop.operando2 = 'si'

        def fix_one_hot_encoded_columns():
            for cl in self.clauses:
                for prop in cl.propositions:
                    # checking if operand1 is an one-hot-encoded column
                    for ohe_column in ohe_column_to_kinds:
                        # if one-hot-encoded column = ALLERGIE and operando1 = ALLERGIE_3, then the if statement matches
                        if re.match(r'{}_\d+'.format(ohe_column),prop.operando1) is not None:

                            if prop.operatore == '=':
                                if prop.operando2 == '1':
                                    prop.operatore = 'contiene'
                                elif prop.operando2 == '0':
                                    prop.operatore = 'non contiene'
                            elif prop.operatore == '!=':
                                if prop.operando2 == '1':
                                    prop.operatore = 'non contiene'
                                elif prop.operando2 == '0':
                                    prop.operatore = 'contiene'



                            # class_index = 3
                            class_index = re.search(r'(?<=_)\d+$',prop.operando1).group(0)
                            # prop.operando2 becomes the third item in commons_TERAPIA_ALTRO
                            prop.operando2 = '\'{}\''.format(ohe_column_to_kinds[ohe_column][int(class_index)])

        def fix_column_names():
            old_column_name_to_new_one = {}

            for ohe_column, ohe_kinds in ohe_column_to_kinds.items():
                # "NEOPLASIA_MAMMARIA_TERAPIA_LISTA_5" will be substituted by "Il campo NEOPLASIA MAMMARIA TERAPIA"
                for i in range(0,len(ohe_kinds)):
                    # 'Il campo NEOPLASIA_MAMMARIA_TERAPIA_LISTA'
                    new_col_name = 'Il campo {}'.format(ohe_column)
                    # 'Il campo NEOPLASIA MAMMARIA TERAPIA LISTA'
                    new_col_name = re.sub(r'_',' ',new_col_name)
                    # 'Il campo NEOPLASIA MAMMARIA TERAPIA'
                    new_col_name = re.sub(r'\sLISTA','',new_col_name)

                    old_column_name_to_new_one['{}_{}'.format(ohe_column,i)]=new_col_name

            # region old_column_name_to_new_one
            old_column_name_to_new_one['AGE']="ETA'"
            old_column_name_to_new_one['SEX']='SESSO'
            old_column_name_to_new_one['TERAPIA_ALTRO_CHECKBOX']='TERAPIE OSTEOPROTETTIVE ALTRO'
            old_column_name_to_new_one['TERAPIA_COMPLIANCE']='COMPLIANCE ALLA TERAPIA'
            old_column_name_to_new_one['MALATTIE_ATTUALI_CHECKBOX']='MALATTIE ATTUALI'
            old_column_name_to_new_one['MALATTIE_ATTUALI_ARTRITE_REUM']='ARTRITE REUMATOIDE'
            old_column_name_to_new_one['MALATTIE_ATTUALI_ARTRITE_PSOR']='ARTRITE PSORIASICA'
            old_column_name_to_new_one['MALATTIE_ATTUALI_LUPUS']='LUPUS'
            old_column_name_to_new_one['MALATTIE_ATTUALI_SCLERODERMIA']='SCLERODERMIA'
            old_column_name_to_new_one['MALATTIE_ATTUALI_ALTRE_CONNETTIVITI']='ALTRE CONNETTIVITI'
            old_column_name_to_new_one['CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX']='CAUSE OSTEOPOROSI SECONDARIA'
            old_column_name_to_new_one['PATOLOGIE_UTERINE_CHECKBOX']='PATOLOGIE UTERINE'
            old_column_name_to_new_one['NEOPLASIA_CHECKBOX']='NEOPLASIA'
            old_column_name_to_new_one['DISLIPIDEMIA_CHECKBOX']='DISLIPIDEMIA'
            old_column_name_to_new_one['PATOLOGIA_CAVO_ORALE_CHECKBOX']='PATOLOGIE CAVO ORALE'
            old_column_name_to_new_one['VITAMINA_D_CHECKBOX']='VITAMINA D'
            old_column_name_to_new_one['ALLERGIE_CHECKBOX']='ALLERGIE'
            old_column_name_to_new_one['INTOLLERANZE_CHECKBOX']='INTOLLERANZE'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_CHECKBOX']='VERTEBRE NON ANALIZZATE'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L1']='VERTEBRA L1 NON ANALIZZATA'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L2']='VERTEBRA L2 NON ANALIZZATA'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L3']='VERTEBRA L3 NON ANALIZZATA'
            old_column_name_to_new_one['VERTEBRE_NON_ANALIZZATE_L4']='VERTEBRA L4 NON ANALIZZATA'
            old_column_name_to_new_one['FRAX_FRATTURE_MAGGIORI_INTERO']='FRAX FRATTURE MAGGIORI'
            old_column_name_to_new_one['FRAX_COLLO_FEMORE_INTERO']='FRAX COLLO FEMORE'
            old_column_name_to_new_one['TBS_COLONNA_APPLICABILE']='TBS APPLICABILE'
            old_column_name_to_new_one['TBS_COLONNA_VALORE']='TBS'
            old_column_name_to_new_one['DEFRA_INTERO']='DEFRA'
            old_column_name_to_new_one['TOT_Tscore']='TOTAL T SCORE'
            old_column_name_to_new_one['TOT_Zscore']='TOTAL Z SCORE'
            # endregion

            for cl in self.clauses:
                for prop in cl.propositions:
                    # this means that the current column name is ok, just getting rid of "_"
                    if prop.operando1 not in old_column_name_to_new_one:
                        prop.operando1 = re.sub(r'_',' ',prop.operando1)
                    # the column name is not ok, so it gets assigned a new one
                    else:
                        prop.operando1 = old_column_name_to_new_one[prop.operando1]

        def remove_equivalent_propositions():
            """
            After removing duplicate propositions, rules of this kind still pass trough:

            TBS < 1.151,
            TBS < 1.054,
            BMI > 30.6323,
            Il campo CAUSE OSTEOPOROSI SECONDARIA non contiene 'malattia cronica epatica come cirrosi/epatite cronica',
            BMI > 32.0499,
            TBS < 1.086,
            Il campo VITAMINA D TERAPIA OSTEOPROTETTIVA non contiene 'colecalciferolo 25.000ui',
            VITAMINA D TERAPIA OSTEOPROTETTIVA: si,
            Il campo VITAMINA D TERAPIA OSTEOPROTETTIVA contiene 'colecalciferolo 10.000ui'

            It is not hard to see that having TBS < 1.151, TBS < 1.054, TBS < 1.086 is not visually appealing.
            Those three propositions are somewhat equivalent, therefore we can keep just one. But which one?
            The most significative one is kept. (TBS < 1.054)


            The functions simplifies the props to:

            TBS < 1.054,
            Il campo CAUSE OSTEOPOROSI SECONDARIA non contiene 'malattia cronica epatica come cirrosi/epatite cronica',
            BMI > 32.0499,
            Il campo VITAMINA D TERAPIA OSTEOPROTETTIVA non contiene 'colecalciferolo 25.000ui',
            VITAMINA D TERAPIA OSTEOPROTETTIVA: si,
            Il campo VITAMINA D TERAPIA OSTEOPROTETTIVA contiene 'colecalciferolo 10.000ui'
            """
            # props which are not going to be removed
            props_to_be_saved = []

            # grouping equivalent props:
            # key is the column name (TBS) and value is a list of props. having column name as operand1 (TBS < 1.151, TBS < 1.054, TBS < 1.086)
            operand1_to_prop = {}
            for cl in self.clauses:
                for prop in cl.propositions:
                    # this task of simplification is done only for numerical values
                    if prop.operatore == '>' or prop.operatore == '>=' or prop.operatore == '<=' or prop.operatore == '<':
                        if prop.operando1 not in operand1_to_prop:
                            operand1_to_prop[prop.operando1] = []
                        operand1_to_prop[prop.operando1].append(prop)
                    else:
                        props_to_be_saved.append(prop)


            # for each group made of equivalent props (TBS/BMI)
            for operand1 in operand1_to_prop:
                first_prop = operand1_to_prop[operand1][0]
                # the winner becomes the most significative prop
                winner_prop = None
                # in case of > or >= the most significative is that wich is greatest
                if first_prop.operatore == '>' or first_prop.operatore == '>=':
                    max = first_prop
                    for prop in operand1_to_prop[operand1]:
                        if float(prop.operando2) >float(max.operando2):
                            max = prop
                    winner_prop = max
                # same logic for < or <=
                elif first_prop.operatore == '<' or first_prop.operatore == '<=':
                    min = first_prop
                    for prop in operand1_to_prop[operand1]:
                        if float(prop.operando2) < float(min.operando2):
                            min = prop
                    winner_prop = min
                else:
                    raise Exception
                props_to_be_saved.append(winner_prop)


            # props not in props_to_be_saved are going to be removed (TBS < 1.151, TBS < 1.086, BMI > 30.6323)
            for cl in self.clauses:
                props_to_remove_from_cl = []
                for prop in cl.propositions:
                    if prop not in props_to_be_saved:
                        props_to_remove_from_cl.append(prop)

                for prop_to_remove in props_to_remove_from_cl:
                    cl.propositions.remove(prop_to_remove)


        simplify_disjunctive_clauses(inst)
        fix_one_hot_encoded_columns()
        fix_binary_columns()
        fix_column_names()
        remove_duplicate_clauses()
        remove_equivalent_propositions()

        complete_prop_set = []
        for cl in self.clauses:
            complete_prop_set.extend(cl.propositions)

        output = ''
        for prop in complete_prop_set:
            if prop is not complete_prop_set[-1]:
                output += str(prop) + ',\n'
            else:
                output += str(prop)

        return output

    def __str__(self):
        output = ''
        for cl in self.clauses:
            if cl is not self.clauses[-1]:
                output+=str(cl)+' AND '
            else:
                output+='{} :: {} ({}/{})'.format(cl,self.prediction,self.mn[0],self.mn[1])
        return output

class Formulae:
    def __init__(self,Formulae_string):
        self.Formulae =self.extract_Formulae(Formulae_string)

    def extract_Formulae(self, Formulae_string):
        output = []
        Formulae_list = Formulae_string.split('\n')
        for formula in Formulae_list:
            if formula != '':
                output.append(ConjunctiveFormula(formula))

        return output

    def predict(self,inst):
        for f in self.Formulae:
            if f.evaluate(inst) == True:
                return f
        return None



    def __str__(self):
        output = ''
        for f in self.Formulae:
            if f != self.Formulae[-1]:
                output+=str(f)+'\n'
            else:
                output+=str(f)

        return output

