package com.company;

import weka.classifiers.Classifier;
import weka.classifiers.Evaluation;
import weka.classifiers.rules.PART;
import weka.classifiers.rules.Rule;
import weka.core.Attribute;
import weka.core.Instances;
import weka.core.Instance;
import weka.core.converters.ArffLoader;
import weka.core.converters.ArffSaver;
import weka.core.converters.CSVLoader;
import weka.core.converters.CSVSaver;
import weka.filters.Filter;
import weka.filters.supervised.instance.StratifiedRemoveFolds;
import weka.filters.unsupervised.attribute.NumericToNominal;
import javax.swing.*;
import javax.swing.text.html.HTMLDocument;
import java.io.File;
import java.io.IOException;
import java.util.*;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.sql.*;

public class Main {

    public static void main(String[] args) throws Exception
    {
        /*Connection myConn = DriverManager.getConnection("jdbc:mysql://localhost:3306/CMO","utente_web","CMOREL96T45");
        Statement myStmt = myConn.createStatement();

        ResultSet myRs = myStmt.executeQuery("select * from GestioneInterna");
        while(myRs.next())
        {
            System.out.println(myRs.getString("TIPO"));//CMOREL96T45
        }*/


         //Connection myConn = DriverManager.getConnection("jdbc:mysql://localhost:3306/CMO","utente_web","CMOREL96T45");
        // Statement myStmt = myConn.createStatement();

         //myStmt.executeUpdate( "create table if not exists regole( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, terapia VARCHAR(256) NOT NULL UNIQUE, regola_refined VARCHAR(1024), regola_not_refined VARCHAR(1024))");

         //myStmt.executeUpdate("insert into regole (terapia,regola_refined, regola_not_refined) values ('TERAPIE_OSTEOPROTETTIVE_CHECKBOX','a','b' )");
         //myStmt.executeUpdate("update regole set regola_refined = 'nnn', regola_not_refined = 'lll' where terapia = 'TERAPIE_OSTEOPROTETTIVE_CHECKBOX'");

        vecchiomain();

    }

    static private void vecchiomain() throws Exception
    {
        //System.out.println(args[0]);

        String className = "1 TERAPIE_OSTEOPROTETTIVE_CHECKBOX";
        //String className= "1 TERAPIE_ORMONALI_CHECKBOX";
        //String className="1 VITAMINA_D_TERAPIA_CHECKBOX" ;
        //String className="1 VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX" ;
        //String className = "1 CALCIO_SUPPLEMENTAZIONE_CHECKBOX";

        String[] tmp = {
                "1 TERAPIA_OSTEOPROTETTIVA_ORMONALE",
                "1 TERAPIA_OSTEOPROTETTIVA_SPECIFICA",
                "1 VITAMINA_D_TERAPIA_OSTEOPROTETTIVA",
                "1 TERAPIA_ALTRO_CHECKBOX",
                "1 TERAPIA_COMPLIANCE",
                "1 FRATTURE",
                "1 FRATTURA_SITI_DIVERSI",
                "1 FRATTURA_FAMILIARITA",
                "1 ABUSO_FUMO_CHECKBOX",
                "1 USO_CORTISONE_CHECKBOX",
                "1 MALATTIE_ATTUALI_CHECKBOX",
                "1 MALATTIE_ATTUALI_ARTRITE_REUM",
                "1 MALATTIE_ATTUALI_ARTRITE_PSOR",
                "1 MALATTIE_ATTUALI_LUPUS",
                "1 MALATTIE_ATTUALI_SCLERODERMIA",
                "1 MALATTIE_ATTUALI_ALTRE_CONNETTIVITI",
                "1 CAUSE_OSTEOPOROSI_SECONDARIA_CHECKBOX",
                "1 PATOLOGIE_UTERINE_CHECKBOX",
                "1 NEOPLASIA_CHECKBOX",
                "1 SINTOMI_VASOMOTORI",
                "1 SINTOMI_DISTROFICI",
                "1 DISLIPIDEMIA_CHECKBOX",
                "1 IPERTENSIONE",
                "1 RISCHIO_TEV",
                "1 PATOLOGIA_CARDIACA",
                "1 PATOLOGIA_VASCOLARE",
                "1 INSUFFICIENZA_RENALE",
                "1 PATOLOGIA_RESPIRATORIA",
                "1 PATOLOGIA_CAVO_ORALE_CHECKBOX",
                "1 PATOLOGIA_EPATICA",
                "1 PATOLOGIA_ESOFAGEA",
                "1 GASTRO_DUODENITE",
                "1 GASTRO_RESEZIONE",
                "1 RESEZIONE_INTESTINALE",
                "1 MICI",
                "1 VITAMINA_D_CHECKBOX",
                "1 ALLERGIE_CHECKBOX",
                "1 INTOLLERANZE_CHECKBOX",
                "1 SITUAZIONE_COLONNA_CHECKBOX",
                "1 SITUAZIONE_FEMORE_SN_CHECKBOX",
                "1 SITUAZIONE_FEMORE_DX_CHECKBOX",
                "1 SITUAZIONE_FEMORE_DX",
                "1 OSTEOPOROSI_GRAVE",
                "1 VERTEBRE_NON_ANALIZZATE_CHECKBOX",
                "1 VERTEBRE_NON_ANALIZZATE_L1",
                "1 VERTEBRE_NON_ANALIZZATE_L2",
                "1 VERTEBRE_NON_ANALIZZATE_L3",
                "1 VERTEBRE_NON_ANALIZZATE_L4",
                "1 COLONNA_NON_ANALIZZABILE",
                "1 COLONNA_VALORI_SUPERIORI",
                "1 FEMORE_NON_ANALIZZABILE",
                "1 FRAX_APPLICABILE",
                "1 TBS_COLONNA_APPLICABILE",
                "1 DEFRA_APPLICABILE",
                "1 NORME_PREVENZIONE",
                "1 ALTRO_CHECKBOX",
                "1 NORME_COMPORTAMENTALI",
                "1 ATTIVITA_FISICA",
                "1 SOSPENSIONE_TERAPIA_CHECKBOX",
                "1 INDAGINI_APPROFONDIMENTO_CHECKBOX",
                "1 SOSPENSIONE_FUMO",
                "1 CONTROLLO_DENSITOMETRICO_CHECKBOX"
        };

        List<String> nomiColDaTrasInNominal = new ArrayList<String>();
        nomiColDaTrasInNominal.add(className);

        Collections.addAll(nomiColDaTrasInNominal,tmp);

        CSVLoader loader = new CSVLoader();
        loader.setFile(new File("/home/dadawg/PycharmProjects/untitled1/perwekacsv.csv"));
        Instances data = loader.getDataSet();
        //System.out.println(data);
        data.setClassIndex(data.numAttributes()-1);



        int[] indiciColDaTrasInNominal= new int[nomiColDaTrasInNominal.size()];
        int i=0;
        for (String colName: nomiColDaTrasInNominal)
        {
            //System.out.println(colName);
            //System.out.println(data.attribute(colName));
            indiciColDaTrasInNominal[i]=data.attribute(colName).index();
            i++;
        }

        Filter filter = new NumericToNominal();
        ((NumericToNominal)filter).setAttributeIndicesArray(indiciColDaTrasInNominal);
        filter.setInputFormat(data);
        data = Filter.useFilter(data,filter);

        filter = new StratifiedRemoveFolds();
        filter.setOptions(new String[]{"-S", "0", "-N", "4", "-F", "1"});
        filter.setInputFormat(data);
        Instances test =  Filter.useFilter(data, filter);

        filter.setOptions(new String[]{"-S", "0", "-V", "-N", "4", "-F", "1"});
        filter.setInputFormat(data);
        Instances train = Filter.useFilter(data, filter);

        CSVSaver saver = new CSVSaver();
        saver.setInstances(test);
        saver.setFile(new File("/home/dadawg/PycharmProjects/untitled1/perpython.csv"));
        saver.writeBatch();

        /*ArffSaver saver = new ArffSaver();
        saver.setInstances(test);
        saver.setFile(new File("test.arff"));
        saver.writeBatch();

        saver = new ArffSaver();
        saver.setInstances(train);
        saver.setFile(new File("train.arff"));
        saver.writeBatch();*/

        PART cls = new PART();
        cls.buildClassifier(train);

        Evaluation evl = new Evaluation(train);
        evl.evaluateModel(cls,test);
        //System.out.println(evl.toSummaryString());

        //System.out.println(cls);

        Rules rules = new Rules(cls);
        String not_refined = rules.toString();
        System.out.println(not_refined);
        System.out.println(rules.getAccuracy(test));
        rules.refineRules(train.numInstances(),0.8,0.1);
        System.out.println(rules.getAccuracy(test));
        String refined_rules = rules.toString();
        //System.out.println(refined_rules);


        //String qry = String.format("update regole set regola_refined = '%s', regola_not_refined = '%s' where terapia = 'TERAPIE_OSTEOPROTETTIVE_CHECKBOX'",refined_rules,not_refined);
        String qry = String.format("replace into regole values('%s','%s','%s')",className,refined_rules,not_refined);
        //System.out.println(qry);


        Connection myConn = DriverManager.getConnection("jdbc:mysql://localhost:3306/CMO","utente_web","CMOREL96T45");
        Statement myStmt = myConn.createStatement();
        myStmt.executeUpdate( "create table if not exists regole(terapia VARCHAR(256) PRIMARY KEY, regola_refined VARCHAR(20000), regola_not_refined VARCHAR(20000))");

        myStmt.executeUpdate(qry);


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

        if(inst.isMissing(attOfoperando1))
        {
           return false;
        }

        if(attOfoperando1.isNumeric())
        {
            double operando1 = inst.value(attOfoperando1);
            double operando2 = Float.parseFloat(this.operando2);

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

        Enumeration instances = testset.enumerateInstances();
        while (instances.hasMoreElements())
        {

            Instance inst = (Instance) instances.nextElement();
            String predicted_y = this.predict(inst);

            String right_y = inst.stringValue(testset.classIndex());

            if(predicted_y == null)
                doesntKnow++;
            else
            {
                doesKnow++;
                if(predicted_y.equals(right_y))
                    predictedRight++;
            }
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
        Pattern predictionPattern = Pattern.compile("(?<=:\\s)\\d(?=\\s\\()");
        Pattern mnPattern = Pattern.compile("(?<=\\s)[(].*[)]$");
        Pattern mnPattern2 = Pattern.compile("(?:\\d+(?:[.]\\d+)?)");
        Matcher matcher;

        for (String string_rule: stringArray_rules)
        {

            //se non è l'ultima regola
            if(string_rule != stringArray_rules[stringArray_rules.length-1]) {
                Regola r = new Regola();

                String[] stringArray_props = string_rule.split("\n");
                Pattern colNamePattern = Pattern.compile("^.+(?=\\s(=|<|>|<=|>=)\\s)");
                Pattern operatorPattern = Pattern.compile("(?<=\\s)(<=|>=|=|<|>)(?=\\s)");
                Pattern operand2Pattern = Pattern.compile("(?<=\\s)[\\w\\d\\s.,+-]+((?=\\sAND$)|(?=:))");


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