package com.company;

import weka.classifiers.Classifier;
import weka.classifiers.Evaluation;
import weka.classifiers.rules.JRip;
import weka.classifiers.rules.PART;
import weka.classifiers.rules.Rule;
import weka.core.Attribute;
import weka.core.Instances;
import weka.core.Instance;
import weka.core.converters.*;
import weka.filters.Filter;
import weka.filters.supervised.instance.StratifiedRemoveFolds;
import weka.filters.unsupervised.attribute.NumericToNominal;
import weka.filters.unsupervised.attribute.Remove;
import weka.filters.unsupervised.attribute.RemoveType;

import javax.swing.*;
import javax.swing.text.html.HTMLDocument;
import java.io.*;
import java.util.*;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.sql.*;
//todo le regole non devono avere apostrofi perchè non piace a mysql
public class Main {

    //attenzione se i tuoi valori differiscono dal classificatore, è perchè il classificatore si comporta diversamnte qnd vede null
    public static void main(String[] args) throws Exception
    {
        vecchiomain();
    }


    static private void vecchiomain() throws Exception
    {
        //System.out.println(args[0]);

        String[] classNames = {

                "VITAMINA_D_SUPPLEMENTAZIONE_LISTA",
                "TERAPIE_ORMONALI_CHECKBOX",
                "TERAPIE_ORMONALI_LISTA",
                "TERAPIE_OSTEOPROTETTIVE_CHECKBOX",
                "TERAPIE_OSTEOPROTETTIVE_LISTA",
                "VITAMINA_D_TERAPIA_CHECKBOX",
                "VITAMINA_D_TERAPIA_LISTA",
                "VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX",
                //"VITAMINA_D_SUPPLEMENTAZIONE_LISTA",
               "CALCIO_SUPPLEMENTAZIONE_CHECKBOX",
                "CALCIO_SUPPLEMENTAZIONE_LISTA"
        };
        //String className = classNames[9];
        //classNames = new String[]{classNames[0]};


        String[] tmp = {
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA",
                "TERAPIA_ALTRO_CHECKBOX",
                "TERAPIA_COMPLIANCE",
                //"FRATTURE",
                "FRATTURA_VERTEBRE_CHECKBOX",
                "FRATTURA_SITI_DIVERSI",
                "FRATTURA_FAMILIARITA",
                "ABUSO_FUMO_CHECKBOX",
                "USO_CORTISONE_CHECKBOX",
                "MALATTIE_ATTUALI_CHECKBOX",
                "MALATTIE_ATTUALI_ARTRITE_REUM",
                "MALATTIE_ATTUALI_ARTRITE_PSOR",
                "MALATTIE_ATTUALI_LUPUS",
                "MALATTIE_ATTUALI_SCLERODERMIA",
                "MALATTIE_ATTUALI_ALTRE_CONNETTIVITI",
                "CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX",
                "PATOLOGIE_UTERINE_CHECKBOX",
                "NEOPLASIA_CHECKBOX",
                "SINTOMI_VASOMOTORI",
                "SINTOMI_DISTROFICI",
                "DISLIPIDEMIA_CHECKBOX",
                "IPERTENSIONE",
                "RISCHIO_TEV",
                "PATOLOGIA_CARDIACA",
                "PATOLOGIA_VASCOLARE",
                "INSUFFICIENZA_RENALE",
                "PATOLOGIA_RESPIRATORIA",
                "PATOLOGIA_CAVO_ORALE_CHECKBOX",
                "PATOLOGIA_EPATICA",
                "PATOLOGIA_ESOFAGEA",
                "GASTRO_DUODENITE",
                "GASTRO_RESEZIONE",
                "RESEZIONE_INTESTINALE",
                "MICI",
                "VITAMINA_D_CHECKBOX",
                "ALLERGIE_CHECKBOX",
                "INTOLLERANZE_CHECKBOX",
                "SITUAZIONE_COLONNA_CHECKBOX",
                "SITUAZIONE_FEMORE_SN_CHECKBOX",
                "SITUAZIONE_FEMORE_DX_CHECKBOX",
                "SITUAZIONE_FEMORE_DX",
                "OSTEOPOROSI_GRAVE",
                "VERTEBRE_NON_ANALIZZATE_CHECKBOX",
                "VERTEBRE_NON_ANALIZZATE_L1",
                "VERTEBRE_NON_ANALIZZATE_L2",
                "VERTEBRE_NON_ANALIZZATE_L3",
                "VERTEBRE_NON_ANALIZZATE_L4",
                "COLONNA_NON_ANALIZZABILE",
                "COLONNA_VALORI_SUPERIORI",
                "FEMORE_NON_ANALIZZABILE",
                "FRAX_APPLICABILE",
                "TBS_COLONNA_APPLICABILE",
                "DEFRA_APPLICABILE"
        };

        List<String> nomiColDaTrasInNominal = new ArrayList<String>();
        //nomiColDaTrasInNominal.add(className);

        Collections.addAll(nomiColDaTrasInNominal,tmp);

        CSVLoader loader = new CSVLoader();
        loader.setFile(new File(String.format("/home/dadawg/PycharmProjects/untitled1/perJAVA.csv")));
        //Instances data = loader.getDataSet();
        //System.out.println(data);
        //data.setClassIndex(data.numAttributes()-1);

        //Map<String, Instances> classNameToDataset = new HashMap<>();
        for (String className: classNames)
        {
            Instances dati =  loader.getDataSet();
            nomiColDaTrasInNominal.add(className);


            int[] indiciColDaTrasInNominal= new int[nomiColDaTrasInNominal.size()];
            int i=0;
            for (String colName: nomiColDaTrasInNominal)
            {
                //System.out.println(colName);
                //System.out.println(data.attribute(colName));
                indiciColDaTrasInNominal[i]=dati.attribute(colName).index();
                i++;
            }


            Filter filter;
            filter = new NumericToNominal();
            ((NumericToNominal)filter).setAttributeIndicesArray(indiciColDaTrasInNominal);
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);


            int[] classIndexesToRemove = new int[classNames.length-1];
            i = 0;
            for(String className_: classNames)
            {
                if(!className_.equals(className)) {
                    classIndexesToRemove[i] = dati.attribute(className_).index();
                    i++;
                }
            }



            filter = new Remove();
            ((Remove)filter).setAttributeIndicesArray(classIndexesToRemove);
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);

            dati.setClassIndex(dati.numAttributes()-1);

            dati.removeIf(instance -> (instance.classIsMissing()==true));


            //This is because sometimes the training set is so small that an entire column has missing values, SITUAZIONE_FEMORE_DX,
            //for example.
            //If this happens, weka assigns string type to that column. The proplem is that JRip or PART can't operate with
            //string type attributes.
            //Therefore we use this filter to remove such type columns.
            //What happens if we have string attributes which don't have all missing values? Doesn't the filter remove those
            //as well?
            //We do not have string attributes, so no problem
            filter = new RemoveType();
            filter.setOptions(new String[]{"-T","string"});
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);



            filter = new StratifiedRemoveFolds();
            filter.setOptions(new String[]{"-S", "0", "-N", "4", "-F", "1"});
            filter.setInputFormat(dati);
            Instances test =  Filter.useFilter(dati, filter);

            filter.setOptions(new String[]{"-S", "0", "-V", "-N", "4", "-F", "1"});
            filter.setInputFormat(dati);
            Instances train = Filter.useFilter(dati, filter);


            //test set per python, percè vogliamo verificare che il classificatore fittizio fatto solo di regole s python
            //si comporta come uelllo vero
            Saver saver = new CSVSaver();
            saver.setInstances(test);
            saver.setFile(new File(String.format("/home/dadawg/PycharmProjects/untitled1/%s_perpython.csv",className)));
            saver.writeBatch();


            //questa parte è per la storia che devo recuperare le colonne con il testo su python sul test set
            //lo faccio usando pk.. allora salvo il test set e poi la rimuovo subito

            filter = new Remove();
            ((Remove)filter).setAttributeIndicesArray(new int[] {dati.attribute("SCAN_DATE").index()});
            filter.setInputFormat(train);
            train = Filter.useFilter(train,filter);
            filter.setInputFormat(test);
            test = Filter.useFilter(test,filter);

            filter = new Remove();
            ((Remove)filter).setAttributeIndicesArray(new int[] {dati.attribute("PATIENT_KEY").index()});
            filter.setInputFormat(train);
            train = Filter.useFilter(train,filter);
            filter.setInputFormat(test);
            test = Filter.useFilter(test,filter);


            //this is not very useful, unless you want to see what weka GUI says
            saver = new ArffSaver();
            saver.setInstances(test);
            saver.setFile(new File(String.format("%s_test.arff",className)));
            saver.writeBatch();
            saver = new ArffSaver();
            saver.setInstances(train);
            saver.setFile(new File(String.format("%s_train.arff",className)));
            saver.writeBatch();

            PART cls = new PART();
            //JRip cls = new JRip();

            cls.buildClassifier(train);

            Evaluation evl = new Evaluation(train);
            evl.evaluateModel(cls,test);
            //System.out.println(evl.toSummaryString());


            Rules rules = new Rules(cls);
            String not_refined = rules.toString();

            Rules rules2 = new Rules(cls);
            rules2.refineRules(train.numInstances(),0.8,0.1);
            String refined_rules = rules2.toString();

            Rules rules3 = new Rules(cls);
            rules3.generateUserReadableRules(getColNameToNgram());
            String user_readable_rules_not_ref = rules3.toString();

            Rules rules4 = new Rules(cls);
            //0.8 0.1
            rules4.refineRules(train.numInstances(),0.8,0.1);
            rules4.generateUserReadableRules(getColNameToNgram());
            String user_readable_rules_ref = rules4.toString();

            System.out.println(className);
            System.out.println(rules.getAccuracy(test));
            System.out.println(rules2.getAccuracy(test));
            System.out.println();


            String qry = String.format("replace into regole values('%s','%s','%s', '%s', '%s')",className,refined_rules,not_refined,user_readable_rules_not_ref,user_readable_rules_ref);
            Connection myConn = DriverManager.getConnection("jdbc:mysql://localhost:3306/CMO2","utente_web","CMOREL96T45");
            Statement myStmt = myConn.createStatement();
            // todo attenzione se devi modificare questa, assicurati di cancellare la tabella dal terminale
            myStmt.executeUpdate( "create table if not exists regole (terapia VARCHAR(256) PRIMARY KEY, regola_refined TEXT, regola_not_refined TEXT, user_readable_not_ref TEXT, user_readable_ref TEXT)");

            myStmt.executeUpdate(qry);

        }

    }

    public static Map<String,String> getColNameToNgram() throws IOException
    {
        BufferedReader bf = new BufferedReader(new FileReader("/home/dadawg/PycharmProjects/untitled1/colnametongram.txt"));
        String pyDic = bf.readLine();
        String[] keyVals = pyDic.split(",");
        //System.out.println(keyVals);
        Map<String,String> colnameToNgram = new HashMap<>();
        Pattern keyValueRegex = Pattern.compile("(?<=')[a-zA-Z0-9\\s_áéíóúàèìòùàèìòù]+(?=')");
        Matcher matcher;
        for (String keyVal : keyVals)
        {
            matcher = keyValueRegex.matcher(keyVal);
            matcher.find();
            String key = matcher.group();
            matcher.find();
            String value = matcher.group();
            //System.out.println(key+": "+value);
            colnameToNgram.put(key,value);

        }
        return colnameToNgram;
    }
}


class Proposizione
{
    private String operando1;
    private String operando2;
    private String operatore;
    public Proposizione(String operando1, String operatore, String operando2)
    {
        this.operando1 = operando1;
        this.operando2 = operando2;
        this.operatore = operatore;
    }

    public boolean valuta(Instance inst)
    {
        Attribute attOfoperando1 = inst.dataset().attribute(this.operando1);

        // se incontro una regola che non valutare allora dico che è falsa procedo alla successiva
        // TODO: meglio mettere a null il tipo di ritorno cosi diciamo che non sappiamo perchè ho paura che se sia è
        //  sia corretto e poi perchè i risualtati sembrano dimostrare che sia meglio

        //System.out.println(this.operando1+" ** "+attOfoperando1);
        if(inst.isMissing(attOfoperando1))
        {
           return false;
        }

        //ricrodati che le ceckbox non sono numeric, ma nominal
        if(attOfoperando1.isNumeric())
        {
            double operando1 = inst.value(attOfoperando1);
            double operando2 = Double.parseDouble(this.operando2);

            if(this.operatore.equals("="))
            {
                if(operando1==operando2)
                    return true;
            }
            else if(this.operatore.equals("<"))
            {
                if(operando1<operando2)
                    return true;
            }
            else if(this.operatore.equals(">"))
            {
                if(operando1>operando2)
                    return true;
            }
            else if(this.operatore.equals("<="))
            {
                if(operando1<=operando2)
                    return true;
            }
            else if(this.operatore.equals(">="))
            {
                if(operando1>=operando2)
                    return true;
            }
        }
        else
        {
            String operando1 = inst.stringValue(attOfoperando1);

            if(this.operatore.equals("="))
            {
                if(operando1.equals(operando2))
                    return true;
            }
        }






        return false;

    }

    @Override
    public String toString() {
        return operando1+" "+this.operatore+" "+this.operando2;
    }

    public String getOperando1() {
        return operando1;
    }

    public String getOperando2() {
        return operando2;
    }

    public String getOperatore() {
        return operatore;
    }

    public void setOperando2(String operando2) {
        this.operando2 = operando2;
    }

    public void setOperatore(String operatore) {
        this.operatore = operatore;
    }

    public void setOperando1(String operando1) {
        this.operando1 = operando1;
    }
}

class Regola
{
    private String predizione;
    private float m;
    private float n;
    private List<Proposizione> proposizioni;

    public Regola(String predizione, float m, float n, List<Proposizione> proposizioni) {
        this.predizione = predizione;
        this.m = m;
        this.n = n;
        this.proposizioni = proposizioni;
    }

    public Regola()
    {
        this.predizione = "no prediction";
        this.m = -1;
        this.n = -1;
        this.proposizioni = new ArrayList<>();
    }

    public void addProposision(Proposizione p)
    {
        this.proposizioni.add(p);
    }

    public boolean valuta(Instance inst)
    {
        if(this.proposizioni==null)
            return true;

        for(Proposizione p: this.proposizioni)
        {
            if(p.valuta(inst)==false)
            {
                return false;
            }
        }

        return true;
    }

    @Override
    public String toString()
    {
        String lastPiece = ": "+this.predizione+" ("+this.m+"/"+this.n+")";
        String output = "";
        if (this.proposizioni!=null)
        {
            for(Proposizione p :this.proposizioni)
            {
                if(p != this.proposizioni.get(this.proposizioni.size()-1))
                    output += p.toString() + " AND\n";
                else
                    output += p.toString() + lastPiece;
            }

        }
        else
            output = lastPiece;

        return output;
    }

    public String getPredizione()
    {
        return this.predizione;
    }

    public void setPredizione(String predizione) {
        this.predizione = predizione;
    }

    public void setM(float m) {
        this.m = m;
    }

    public void setN(float n) {
        this.n = n;
    }

    public float getM() {
        return m;
    }

    public float getN() {
        return n;
    }

    public List<Proposizione> getProposizioni()
    {
        return this.proposizioni;
    }
}

class Rules implements Iterable<Regola>
{
    private List<Regola> rules;

    public Rules(Classifier cls)
    {
        this.rules = this.extractRules(cls);
    }

    public String getAccuracy(Instances testset) throws Exception {
        int predictedRight = 0;
        int doesKnow = 0;
        int doesntKnow = 0;
        int i =0;
        Enumeration instances = testset.enumerateInstances();
        while (instances.hasMoreElements())
        {
            if (i==76)
            {
                int x = 9;
            }
            Instance inst = (Instance) instances.nextElement();
            //System.out.println(inst);
            String predicted_y = this.predict(inst);

            /*Double predictedByCls = cls.classifyInstance(inst);
            if(Double.parseDouble(predicted_y) != predictedByCls) {
                int r = 5;
            }*/

            //System.out.println(predicted_y+" "+predictedByCls);

            //System.out.println(i+": "+predicted_y);


            String right_y = inst.stringValue(testset.classIndex());

            if(predicted_y == null)
                doesntKnow++;
            else
            {
                doesKnow++;
                if(predicted_y.equals(right_y))
                    predictedRight++;
            }

            //System.out.println(i+": "+predicted_y);
            i++;
        }

        return (double)predictedRight/doesKnow+" "+(double)doesntKnow/testset.numInstances();
    }
    public void refineRules(int numTrainInstances,double min_f1, double min_f2)
    {
        /*
        rules_to_be_removed = []
        for rule in rules:
            f1 = (1-rule.n/rule.m)
            f2 = (rule.m/num_train_instances)
            if f1 < min_f1 or f2 < min_f2:
                rules_to_be_removed.append(rule)

        rules.remove_rules(rules_to_be_removed)
         */
        List<Regola> rulesToBeRemoved = new ArrayList<>();
        for (Regola r:this.rules)
        {
            double f1 = (1-r.getN()/r.getM());
            double f2 = r.getM()/numTrainInstances;
            if(f1 < min_f1 | f2 < min_f2)
                rulesToBeRemoved.add(r);
        }

        this.removeRules(rulesToBeRemoved);
    }

    public void generateUserReadableRules(Map<String,String> colnameToNgram)
    {
        for(Regola r: this.rules)
        {
            //attenzione al caso della regola sempre vera in cui proposizioni = null
            if(r.getProposizioni()!=null) {
                for (Proposizione p : r.getProposizioni()) {
                    if (colnameToNgram.get(p.getOperando1()) != null) {
                        String newOperando1, newOperando2, newOperatore;
                        String oldOperando1 = p.getOperando1();
                        String oldOperatore = p.getOperatore();
                        String oldOperando2 = p.getOperando2();

                        String prefix = oldOperando1.replaceAll("[0-9]+$", "");
                        // nome colonna corrispondente al prefisso
                        String columnNamePrefix = colnameToNgram.get(prefix);

                        newOperando1 = columnNamePrefix;

                        // >= 0
                        String secondaParte = oldOperatore + " " + oldOperando2;


                        if (secondaParte.equals("<= 0"))
                            newOperatore = "non contiene le parole";
                        else if (secondaParte.equals("> 0"))
                            newOperatore = "contiene le parole";
                        else
                            newOperatore = "errore in generateUserReadableRules";

                        newOperando2 = colnameToNgram.get(oldOperando1);

                        newOperando2 = "#"+newOperando2+"#";

                        //System.out.println(newOperando1 + " " + newOperatore + " " + newOperando2);
                        p.setOperando1(newOperando1);
                        p.setOperatore(newOperatore);
                        p.setOperando2(newOperando2);


                    }
                }
            }
        }

    }

    public void addRule(Regola r)
    {
        this.rules.add(r);
    }
    public String toString()
    {
        String output = "";

        for(Regola r: this.rules)
            output+=r.toString() + "\n\n";

        //rimuovo \n\n dell'ultima regola
        return output.substring(0,output.length()-2);
    }

    public String predict(Instance inst)
    {
        for(Regola r :this.rules)
            if(r.valuta(inst))
                return r.getPredizione();

        return null;
    }

    public List<Regola> extractRules(Classifier cls)
    {
        String rulesInStringFormat = cls.toString();
        rulesInStringFormat=rulesInStringFormat.replaceAll("^PART decision list[\r\n][-]+[\r\n]{2}","");
        rulesInStringFormat=rulesInStringFormat.replaceAll("[\\r\\n][\\r\\n]Number of Rules\\s+:\\s+[0-9]+$","");

        List<Regola> output = new ArrayList<>();

        String[] stringArray_rules = rulesInStringFormat.split("\n\n");
        //todo approfindire il regex per gestire tutti i carattreri strani
        Pattern predictionPattern = Pattern.compile("(?<=:\\s)[\\d\\w.,\\s-+/]+(?=\\s\\()");
        Pattern mnPattern = Pattern.compile("(?<=\\s)[(].*[)]$");
        Pattern mnPattern2 = Pattern.compile("(?:\\d+(?:[.]\\d+)?)");
        Matcher matcher;

        for (String string_rule: stringArray_rules)
        {

            //se non è l'ultima regola
            if(string_rule != stringArray_rules[stringArray_rules.length-1]) {
                Regola r = new Regola();

                String[] stringArray_props = string_rule.split("\n");
                Pattern colNamePattern = Pattern.compile("^\\w+(?=\\s(=|<|>|<=|>=)\\s)");
                Pattern operatorPattern = Pattern.compile("(?<=^\\w+\\s)(<=|>=|=|<|>)(?=\\s)");
                Pattern operand2Pattern = Pattern.compile("((> 2.5 mg e < 5 mg)|(>= 5 mg \\(Prednisone\\))|(<= 10 sigarette/di)|(> 10 sigarette/di))|((?<=(<|<=|>|>=|=)\\s)[\\w\\d\\s.,+-]+((?=\\sAND$)|(?=:)))");


                for (String string_prop : stringArray_props) {
                    matcher = colNamePattern.matcher(string_prop);
                    matcher.find();
                    String colName = matcher.group();
                    matcher = operatorPattern.matcher(string_prop);
                    matcher.find();
                    String operator = matcher.group();
                    matcher = operand2Pattern.matcher(string_prop);
                    matcher.find();
                    String operand2 = matcher.group();
                    Proposizione p = new Proposizione(colName, operator, operand2);
                    r.addProposision(p);

                    // se è l'ultima proposizione
                    if (string_prop == stringArray_props[stringArray_props.length - 1]) {
                        matcher = predictionPattern.matcher(string_prop);
                        matcher.find();
                        String prediction = matcher.group();


                        matcher = mnPattern.matcher(string_prop);
                        matcher.find();
                        String mn = matcher.group();

                        mn = mn.replaceAll("(?<=^\\()(\\d+(?:[.]\\d+)?)(?=\\)$)", "$1/0)");

                        matcher = mnPattern2.matcher(mn);
                        matcher.find();

                        Float m = Float.parseFloat(matcher.group(0));
                        matcher.find();
                        Float n = Float.parseFloat(matcher.group(0));

                        r.setPredizione(prediction);
                        r.setM(m);
                        r.setN(n);

                        output.add(r);
                    }
                }

            }
            //se è l'ultima regola
            else
            {
                matcher = predictionPattern.matcher(string_rule);
                matcher.find();
                String prediction = matcher.group();


                matcher = mnPattern.matcher(string_rule);
                matcher.find();
                String mn = matcher.group();

                mn = mn.replaceAll("(?<=^\\()(\\d+(?:[.]\\d+)?)(?=\\)$)", "$1/0)");

                matcher = mnPattern2.matcher(mn);
                matcher.find();

                Float m = Float.parseFloat(matcher.group(0));
                matcher.find();
                Float n = Float.parseFloat(matcher.group(0));

                Regola r = new Regola(prediction,m,n,null);
                output.add(r);
            }

        }


        return output;
    }

    public List<Regola> extractRulesJRip(Classifier cls)
    {
        return null;
    }

    private void removeRules(List<Regola> rulesToRemove)
    {
        for (Regola r: rulesToRemove)
            this.rules.remove(r);
    }
    public Iterator<Regola> iterator()
    {
        return new RulesIterator();
    }
    private class RulesIterator implements Iterator<Regola>
    {
        private int currPos = 0;
        public boolean hasNext()
        {
            if(currPos < rules.size())
                return true;
            else
                return false;
        }

        public Regola next()
        {
            if(this.hasNext()) {
                currPos++;
                return rules.get(this.currPos);
            }
            else
                return null;
        }

        //todo: after removing it shifts subsequnt elems(if any) go left and decreceases their indexes by 1
        public void remove()
        {

        }
    }

}