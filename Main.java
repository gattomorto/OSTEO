package com.company;

import weka.classifiers.Classifier;
import weka.classifiers.Evaluation;
import weka.classifiers.rules.JRip;
import weka.core.Attribute;
import weka.core.Instances;
import weka.core.Instance;
import weka.core.converters.*;
import weka.filters.Filter;
import weka.filters.supervised.instance.StratifiedRemoveFolds;
import weka.filters.unsupervised.attribute.NumericToNominal;
import weka.filters.unsupervised.attribute.Remove;
import weka.filters.unsupervised.attribute.RemoveType;

import java.io.*;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.*;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.sql.*;
public class Main {
    //todo: capire cosa succede se refine rules produce no regole
    //attenzione se i tuoi valori differiscono dal classificatore, è perchè il classificatore si comporta diversamnte qnd vede null
    public static void main(String[] args) throws Exception
    {
        //PrintStream out = new PrintStream(new FileOutputStream("log.txt"));
        //System.setErr(out);
        go();
        //Test.test_go();
    }

    static private void go() throws Exception
    {
        String[] classNames = {
                "TERAPIE_ORMONALI_CHECKBOX",
                "TERAPIE_ORMONALI_LISTA",
                "TERAPIE_OSTEOPROTETTIVE_CHECKBOX",
                "TERAPIE_OSTEOPROTETTIVE_LISTA",
                "VITAMINA_D_TERAPIA_CHECKBOX",
                "VITAMINA_D_TERAPIA_LISTA",
                "VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX",
                "VITAMINA_D_SUPPLEMENTAZIONE_LISTA",
                "CALCIO_SUPPLEMENTAZIONE_CHECKBOX",
                "CALCIO_SUPPLEMENTAZIONE_LISTA"
        };

        String[] tmp = {
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA",
                "TERAPIA_ALTRO_CHECKBOX",
                "TERAPIA_COMPLIANCE",
                "FRATTURA_SITI_DIVERSI",
                "FRATTURA_FAMILIARITA",
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
                "DEFRA_APPLICABILE",

                "TERAPIA_ALTRO_0",
                "TERAPIA_ALTRO_1",
                "TERAPIA_ALTRO_2",
                "TERAPIA_ALTRO_3",
                "TERAPIA_ALTRO_4",
                "TERAPIA_ALTRO_5",
                "TERAPIA_ALTRO_6",
                "TERAPIA_ALTRO_7",
                "TERAPIA_ALTRO_8",
                "TERAPIA_ALTRO_9",
                "TERAPIA_ALTRO_10",
                "TERAPIA_ALTRO_11",
                "TERAPIA_ALTRO_12",
                "TERAPIA_ALTRO_13",
                "TERAPIA_ALTRO_14",
                "TERAPIA_ALTRO_15",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_0",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_1",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_2",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_3",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_0",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_1",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_2",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_3",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_4",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_5",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_6",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_7",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_8",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_0",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_1",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_2",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_3",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_4",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_5",
                "PATOLOGIE_UTERINE_DIAGNOSI_0",
                "PATOLOGIE_UTERINE_DIAGNOSI_1",
                "PATOLOGIE_UTERINE_DIAGNOSI_2",
                "PATOLOGIE_UTERINE_DIAGNOSI_3",
                "ALTRE_PATOLOGIE_0",
                "ALTRE_PATOLOGIE_1",
                "NEOPLASIA_MAMMARIA_TERAPIA_0",
                "NEOPLASIA_MAMMARIA_TERAPIA_1",
                "NEOPLASIA_MAMMARIA_TERAPIA_2",
                "NEOPLASIA_MAMMARIA_TERAPIA_3",
                "NEOPLASIA_MAMMARIA_TERAPIA_4",
                "NEOPLASIA_MAMMARIA_TERAPIA_5",
                "DISLIPIDEMIA_TERAPIA_0",
                "DISLIPIDEMIA_TERAPIA_1",
                "DISLIPIDEMIA_TERAPIA_2",
                "DISLIPIDEMIA_TERAPIA_3",
                "ALLERGIE_0",
                "ALLERGIE_1",
                "ALLERGIE_2",
                "ALLERGIE_3",
                "ALLERGIE_4",
                "ALLERGIE_5",
                "ALLERGIE_6",
                "ALLERGIE_7",
                "ALLERGIE_8",
                "INTOLLERANZE_0",
                "INTOLLERANZE_1",
                "INTOLLERANZE_2",
                "INTOLLERANZE_3",
                "INTOLLERANZE_4",
                "INTOLLERANZE_5",
                "INTOLLERANZE_6",
                "INTOLLERANZE_7",
                "CAUSE_OSTEOPOROSI_SECONDARIA_0",
                "CAUSE_OSTEOPOROSI_SECONDARIA_1",
                "CAUSE_OSTEOPOROSI_SECONDARIA_2",
                "CAUSE_OSTEOPOROSI_SECONDARIA_3",
                "CAUSE_OSTEOPOROSI_SECONDARIA_4",
                "CAUSE_OSTEOPOROSI_SECONDARIA_5",
                "CAUSE_OSTEOPOROSI_SECONDARIA_6",
                "CAUSE_OSTEOPOROSI_SECONDARIA_7"
        };

        List<String> nomiColDaTrasInNominal = new ArrayList<String>();

        Collections.addAll(nomiColDaTrasInNominal,tmp);

        CSVLoader loader = new CSVLoader();
        loader.setFile(new File(String.format("/home/dadawg/PycharmProjects/untitled1/perJAVA.csv")));

        for (String className: classNames)
        {
            Instances dati = loader.getDataSet();
            nomiColDaTrasInNominal.add(className);

            int[] indiciColDaTrasInNominal= new int[nomiColDaTrasInNominal.size()];
            int i=0;
            for (String colName: nomiColDaTrasInNominal)
            {
                indiciColDaTrasInNominal[i]=dati.attribute(colName).index();
                i++;
            }
            Filter filter;
            filter = new NumericToNominal();
            ((NumericToNominal)filter).setAttributeIndicesArray(indiciColDaTrasInNominal);
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);

            // the dataset comes with different classes at the end. The classifier can be built for one class only, so
            // we need to remove all classes apart from 'className'.
            int[] classIndexesToRemove = new int[classNames.length-1];
            i = 0;
            // finding out indices of columns to be removed
            for(String className_: classNames)
            {
                if(!className_.equals(className)) {
                    classIndexesToRemove[i] = dati.attribute(className_).index();
                    i++;
                }
            }
            // the removal itself
            filter = new Remove();
            ((Remove)filter).setAttributeIndicesArray(classIndexesToRemove);
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);

            // setting the class
            dati.setClassIndex(dati.numAttributes()-1);

            dati.removeIf(Instance::classIsMissing);

            filter = new Remove();
            ((Remove)filter).setAttributeIndicesArray(new int[] {dati.attribute("PATIENT_KEY").index(),dati.attribute("SCAN_DATE").index()});
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);

            JRip cls = new JRip();
            cls.setOptimizations(20);
            cls.buildClassifier(dati);

            Evaluation evl = new Evaluation(dati);
            evl.evaluateModel(cls,dati);
            //System.out.println(evl.toSummaryString());
            //System.out.println(cls);
            System.out.println(className);

            Rules rules = new Rules(cls);
            Formulae formulae = new Formulae(rules);
            formulae.refine(dati.numInstances(),0.0,0.0);
            String formulae_ = formulae.toString();

            Connection myConn = DriverManager.getConnection("jdbc:mysql://localhost:3306/CMO","utente_web","CMOREL96T45");
            Statement myStmt = myConn.createStatement();
            myStmt.executeUpdate( "create table if not exists regole (terapia VARCHAR(256) PRIMARY KEY, refined TEXT)");
            myStmt.executeUpdate(String.format("replace into regole values('%s','%s')",className,formulae_));

        }

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

    public Proposizione(String prop)
    {

        Pattern operand1Pattern = Pattern.compile("(?<=\\()\\w+");
        Pattern operatorPattern = Pattern.compile("(<=|>=|<|>|=)");
        Pattern operand2Pattern = Pattern.compile("(?<=(<=|>=|<|>|=)\\s)[\\w\\s.+,-/()]+(?=\\))");
        Matcher matcher;

        matcher = operand1Pattern.matcher(prop);
        matcher.find();
        this.operando1 = matcher.group();

        matcher = operatorPattern.matcher(prop);
        matcher.find();
        this.operatore = matcher.group();

        matcher = operand2Pattern.matcher(prop);
        matcher.find();
        this.operando2 = matcher.group();
    }

    public boolean valuta(Instance inst)
    {
        Attribute attOfoperando1 = inst.dataset().attribute(this.operando1);

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
            else if (this.operatore.equals("!="))
            {
                if(operando1!=operando2)
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
            else if(this.operatore.equals("!="))
            {
                if(!operando1.equals(operando2))
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

    public Proposizione negate()
    {
        String outputOperator=null;
        String outputOperand1=this.operando1;
        String outputOperand2=this.operando2;

        switch (this.operatore) {
            case "=":
                outputOperator="!=";
                break;
            case ">":
                outputOperator = "<=";
                break;
            case "<":
                outputOperator = ">=";
                break;
            case ">=":
                outputOperator = "<";
                break;
            case "<=":
                outputOperator = ">";
                break;

        }
        return new Proposizione(outputOperand1,outputOperator,outputOperand2);
    }
}

class Regola
{
    private String predizione;
    private double m;
    private double n;
    private List<Proposizione> proposizioni;

    public Regola(String predizione, double m, double n, List<Proposizione> proposizioni) {
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

    public Regola (String rule)
    {
        this.proposizioni = this.extractProposisionsJRip(rule);
        double[] mn = this.extractMN(rule);
        this.m = mn[0];
        this.n = mn[1];
        this.predizione = this.extractPrediction(rule);
    }

    private List<Proposizione> extractProposisionsJRip(String rule)
    {
        List<Proposizione> propositions = new ArrayList<>();

        Pattern propositionPattern = Pattern.compile("\\([\\w>=<.\\d\\s-+]+\\)");

        Matcher matcher = propositionPattern.matcher(rule);

        while (matcher.find()) {
            String prop = matcher.group();
            propositions.add(new Proposizione(prop));
        }

        return propositions;
    }
    private double[] extractMN(String rule)
    {
        double[] mn = new double[2];

        Pattern mnPattern = Pattern.compile("((?<=\\()|(?<=/))\\d+.\\d+((?=/)|(?=\\)))");
        Matcher matcher = mnPattern.matcher(rule);

        matcher.find();
        mn[0] =Double.parseDouble(matcher.group(0));
        matcher.find();
        mn[1] =Double.parseDouble(matcher.group(0));

        return mn;
    }

    private String extractPrediction(String rule)
    {
        Pattern predictionPattern = Pattern.compile("(?<=\\w=)[\\w\\s.+,-/()]+(?=\\s\\(\\d)");
        Matcher matcher = predictionPattern.matcher(rule);
        matcher.find();
        return matcher.group();
    }

    public void addProposision(Proposizione p)
    {
        this.proposizioni.add(p);
    }

    public boolean valuta(Instance inst)
    {
        if(this.proposizioni.isEmpty())
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
        if (!this.proposizioni.isEmpty())
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

    public void setM(double m) {
        this.m = m;
    }

    public void setN(double n) {
        this.n = n;
    }

    public double getM() {
        return m;
    }

    public double getN() {
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

    public int size()
    {
        return this.rules.size();
    }
    public Regola get(int index)
    {
        return this.rules.get(index);
    }

    public Rules(Classifier cls) throws IOException {
        this.rules = this.extractRulesJRip(cls);
        //this.rules = this.extractRules(cls);
    }

    public String getAccuracy(Instances testset) throws Exception {
        int predictedRight = 0;
        int doesKnow = 0;
        int doesntKnow = 0;
        int i =0;
        Enumeration instances = testset.enumerateInstances();
        while (instances.hasMoreElements())
        {
            if (i==106)
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
            //attenzione al caso della regola sempre vera in cui proposizioni is empty()
            if(!r.getProposizioni().isEmpty()) {
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
                        else if (secondaParte.equals(">= 1"))
                            newOperatore = "contiene le parole";
                        else
                            throw new RuntimeException("Strange operator");

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
        {
            /*if (r.getM() == 10.86| r.getN() == 1)
                System.out.println("dd");*/

            if (r.valuta(inst))
                return r.getPredizione();
        }

        return null;
    }

    public List<Regola> extractRulesJRip(Classifier cls) throws IOException {
        List<Regola> rules_list = new ArrayList<>();
        String rules_string = cls.toString();
        rules_string=rules_string.replaceAll("^JRIP rules:[\\n\\r]=+[\\n\\r]{2}","");
        rules_string=rules_string.replaceAll("[\\n\\r]Number.*$","");

        String[] array_rules = rules_string.split("\n");

        for(String rule: array_rules)
        {
            rules_list.add(new Regola(rule));
        }

        return rules_list;
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

class ConjunctiveFormula
{
    private List<Clause> clauses;
    private String predizione;
    private double m;
    private double n;

    public String getPrediction()
    {
        return this.predizione;
    }

    public ConjunctiveFormula(Regola r)
    {
        clauses = new ArrayList<>();

        if(!r.getProposizioni().isEmpty())
            clauses.add(new ConjunctiveClause(r.getProposizioni()));

        this.predizione=r.getPredizione();
        this.m=r.getM();
        this.n = r.getN();
    }


    public void addClause(Clause cl)
    {
        this.clauses.add(cl);
    }

    @Override
    public String toString() {
        String output = "";
        for(Clause cl: this.clauses)
        {
            if(cl != this.clauses.get(this.clauses.size()-1))
                output += cl+" AND ";
            else
                output += cl;
        }
        output+=" :: "+this.predizione + " ("+this.m+"/"+this.n+")";

        return output;
    }

    //if at least one clause if false, the whole formula is false
    public boolean evaluate(Instance inst)
    {
        for(Clause cl : this.clauses)
        {
            if(cl.evaluate(inst)==false)
                return false;
        }
        return true;
    }

    public double getM() {
        return m;
    }

    public double getN() {
        return n;
    }
}

class Formulae
{
    List<ConjunctiveFormula> Formulae;


    public void refine(int numTrainInstances, double min_f1, double min_f2)
    {
        List<ConjunctiveFormula> FormulaeToBeRemoved = new ArrayList<>();
        for (ConjunctiveFormula f:this.Formulae)
        {
            double f1 = (1-f.getN()/f.getM());
            double f2 = f.getM()/numTrainInstances;
            if(f1 < min_f1 | f2 < min_f2)
                FormulaeToBeRemoved.add(f);
        }

        this.removeFormulae(FormulaeToBeRemoved);
    }

    private void removeFormulae(List<ConjunctiveFormula> FormulaeToRemove)
    {
        for (ConjunctiveFormula f: FormulaeToRemove)
            this.Formulae.remove(f);
    }

    public Formulae(Rules rules)
    {
        this.Formulae= new ArrayList<>();

        for(int i=0; i<rules.size(); i++)
        {

            this.Formulae.add(new ConjunctiveFormula(rules.get(i)));

            for (int j=0; j<i; j++)
            {
                List<Proposizione> propsOfJthRule = rules.get(j).getProposizioni();
                ConjunctiveClause conjC = new ConjunctiveClause(propsOfJthRule);
                DisjunctiveClause disjC = conjC.negate();
                this.get(i).addClause(disjC);
            }
        }
    }

    public ConjunctiveFormula get(int index)
    {
        return this.Formulae.get(index);
    }

    public String predict(Instance inst)
    {
        for (ConjunctiveFormula formula: this.Formulae)
        {
            if(formula.evaluate(inst)==true)
                return formula.getPrediction();
        }
        return null;
    }


    public String getAccuracy(Instances testset) throws Exception {
        int predictedRight = 0;
        int doesKnow = 0;
        int doesntKnow = 0;
        int i =0;
        Enumeration instances = testset.enumerateInstances();
        while (instances.hasMoreElements())
        {
            if (i == 316)
            {
                int x = 5;
            }
            Instance inst = (Instance) instances.nextElement();
            String predicted_y = this.predict(inst);


            //ystem.out.println(String.format("%d: %s",i,predicted_y));


            String right_y = inst.stringValue(testset.classIndex());

            if(predicted_y == null)
                doesntKnow++;
            else
            {
                doesKnow++;
                if(predicted_y.equals(right_y))
                    predictedRight++;
            }

            i++;
        }

        return (double)predictedRight/doesKnow+" "+(double)doesntKnow/testset.numInstances();
    }

    public String toString()
    {
        String output = "";
        for (ConjunctiveFormula f: this.Formulae)
        {
            if (f != this.Formulae.get(this.Formulae.size()-1))
                output+=f.toString() + "\n";
            else
                output+=f.toString();
        }

        return output;
    }
}

abstract class Clause
{
    protected List<Proposizione> propositions;
    public Clause(List<Proposizione> propositions)
    {
        this.propositions = propositions;
    }

    abstract public boolean evaluate(Instance inst);
    public String toString() {
        String logicalOperator=null;

        if (this instanceof ConjunctiveClause)
            logicalOperator = " AND ";
        else if(this instanceof DisjunctiveClause)
            logicalOperator = " OR ";

        String output = "(";
        for (Proposizione prop: this.propositions)
        {
            if(prop!=this.propositions.get(this.propositions.size()-1))
                output += prop + logicalOperator;
            else
                output += prop + ")";
        }
        return output;
    }

}
//todo: change to conjunctive
class ConjunctiveClause extends Clause
{
    public ConjunctiveClause(List<Proposizione> propositions)
    {
        super(propositions);
    }

    @Override
    public boolean evaluate(Instance inst) {
        for(Proposizione p:super.propositions)
        {
            if(p.valuta(inst)==false)
            {
                return false;
            }
        }

        return true;
    }

    @Override
    public String toString() {
        String output = "(";
        for (Proposizione prop: super.propositions)
        {
            if(prop!=super.propositions.get(super.propositions.size()-1))
                output += prop + " AND ";
            else
                output += prop + ")";
        }
        return output;
    }

    public DisjunctiveClause negate()
    {
        List<Proposizione> negatedPropositions= new LinkedList<>();
        for(Proposizione prop: super.propositions)
        {
            negatedPropositions.add(prop.negate());
        }

        return new DisjunctiveClause(negatedPropositions);
    }
}
class DisjunctiveClause extends Clause
{
    public DisjunctiveClause(List<Proposizione> propositions) {
        super(propositions);
    }

    @Override
    public boolean evaluate(Instance inst) {
        // if at least one proposition is true, the whole clause is true
        for(Proposizione prop: super.propositions)
        {
            if(prop.valuta(inst)==true)
                return true;
        }
        return false;
    }
}

class Test
{
    public static void test_go() throws Exception
    {
        //System.out.println(args[0]);

        String[] classNames = {
                //"VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX",
                "TERAPIE_ORMONALI_CHECKBOX",//0
                "TERAPIE_ORMONALI_LISTA",//1
                "TERAPIE_OSTEOPROTETTIVE_CHECKBOX",//2
                "TERAPIE_OSTEOPROTETTIVE_LISTA",
                "VITAMINA_D_TERAPIA_CHECKBOX",
                "VITAMINA_D_TERAPIA_LISTA",
                "VITAMINA_D_SUPPLEMENTAZIONE_CHECKBOX",
                "VITAMINA_D_SUPPLEMENTAZIONE_LISTA",
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
                "FRATTURA_SITI_DIVERSI",
                "FRATTURA_FAMILIARITA",
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
                "DEFRA_APPLICABILE",

                "TERAPIA_ALTRO_0",
                "TERAPIA_ALTRO_1",
                "TERAPIA_ALTRO_2",
                "TERAPIA_ALTRO_3",
                "TERAPIA_ALTRO_4",
                "TERAPIA_ALTRO_5",
                "TERAPIA_ALTRO_6",
                "TERAPIA_ALTRO_7",
                "TERAPIA_ALTRO_8",
                "TERAPIA_ALTRO_9",
                "TERAPIA_ALTRO_10",
                "TERAPIA_ALTRO_11",
                "TERAPIA_ALTRO_12",
                "TERAPIA_ALTRO_13",
                "TERAPIA_ALTRO_14",
                "TERAPIA_ALTRO_15",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_0",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_1",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_2",
                "TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA_3",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_0",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_1",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_2",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_3",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_4",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_5",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_6",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_7",
                "TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA_8",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_0",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_1",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_2",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_3",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_4",
                "VITAMINA_D_TERAPIA_OSTEOPROTETTIVA_LISTA_5",
                "PATOLOGIE_UTERINE_DIAGNOSI_0",
                "PATOLOGIE_UTERINE_DIAGNOSI_1",
                "PATOLOGIE_UTERINE_DIAGNOSI_2",
                "PATOLOGIE_UTERINE_DIAGNOSI_3",
                "ALTRE_PATOLOGIE_0",
                "ALTRE_PATOLOGIE_1",
                "NEOPLASIA_MAMMARIA_TERAPIA_0",
                "NEOPLASIA_MAMMARIA_TERAPIA_1",
                "NEOPLASIA_MAMMARIA_TERAPIA_2",
                "NEOPLASIA_MAMMARIA_TERAPIA_3",
                "NEOPLASIA_MAMMARIA_TERAPIA_4",
                "NEOPLASIA_MAMMARIA_TERAPIA_5",
                "DISLIPIDEMIA_TERAPIA_0",
                "DISLIPIDEMIA_TERAPIA_1",
                "DISLIPIDEMIA_TERAPIA_2",
                "DISLIPIDEMIA_TERAPIA_3",
                "ALLERGIE_0",
                "ALLERGIE_1",
                "ALLERGIE_2",
                "ALLERGIE_3",
                "ALLERGIE_4",
                "ALLERGIE_5",
                "ALLERGIE_6",
                "ALLERGIE_7",
                "ALLERGIE_8",
                "INTOLLERANZE_0",
                "INTOLLERANZE_1",
                "INTOLLERANZE_2",
                "INTOLLERANZE_3",
                "INTOLLERANZE_4",
                "INTOLLERANZE_5",
                "INTOLLERANZE_6",
                "INTOLLERANZE_7",
                "CAUSE_OSTEOPOROSI_SECONDARIA_0",
                "CAUSE_OSTEOPOROSI_SECONDARIA_1",
                "CAUSE_OSTEOPOROSI_SECONDARIA_2",
                "CAUSE_OSTEOPOROSI_SECONDARIA_3",
                "CAUSE_OSTEOPOROSI_SECONDARIA_4",
                "CAUSE_OSTEOPOROSI_SECONDARIA_5",
                "CAUSE_OSTEOPOROSI_SECONDARIA_6",
                "CAUSE_OSTEOPOROSI_SECONDARIA_7"
        };

        List<String> nomiColDaTrasInNominal = new ArrayList<String>();

        Collections.addAll(nomiColDaTrasInNominal,tmp);

        CSVLoader loader = new CSVLoader();
        loader.setFile(new File(String.format("/home/dadawg/PycharmProjects/untitled1/perJAVA.csv")));

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
            // finding out indices of columns to be removed
            for(String className_: classNames)
            {
                if(!className_.equals(className)) {
                    classIndexesToRemove[i] = dati.attribute(className_).index();
                    i++;
                }
            }

            // the removal itself
            filter = new Remove();
            ((Remove)filter).setAttributeIndicesArray(classIndexesToRemove);
            filter.setInputFormat(dati);
            dati = Filter.useFilter(dati,filter);

            dati.setClassIndex(dati.numAttributes()-1);

            dati.removeIf(Instance::classIsMissing);

            // train/test sets generation
            filter = new StratifiedRemoveFolds();
            filter.setOptions(new String[]{"-S", "0", "-N", "4", "-F", "1"});
            filter.setInputFormat(dati);
            Instances test =  Filter.useFilter(dati, filter);

            filter.setOptions(new String[]{"-S", "0", "-V", "-N", "4", "-F", "1"});
            filter.setInputFormat(dati);
            Instances train = Filter.useFilter(dati, filter);


            Saver saver = new CSVSaver();
            saver.setInstances(test);
            saver.setFile(new File(String.format("/home/dadawg/PycharmProjects/untitled1/%s_perpython.csv",className)));
            saver.writeBatch();


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
            saver.setFile(new File(String.format("Tests/%s_test.arff",className)));
            saver.writeBatch();
            saver = new ArffSaver();
            saver.setInstances(train);
            saver.setFile(new File(String.format("Tests/%s_train.arff",className)));
            saver.writeBatch();

            //PART cls = new PART();
            JRip cls = new JRip();

            cls.buildClassifier(train);

            Evaluation evl = new Evaluation(train);
            evl.evaluateModel(cls,test);
            //System.out.println(evl.toSummaryString());
            //System.out.println(cls);
            System.out.println(className);

            Rules rules = new Rules(cls);

            Formulae ff = new Formulae(rules);
            System.out.println(ff.getAccuracy(test));
            String formulaeUserNonRefined = ff.toString();
            ff.refine(train.numInstances(),0.8,0.1);
            System.out.println(ff.getAccuracy(test));
            String FormulaeUserRefined = ff.toString();

            System.out.println();

            String qry = String.format("replace into regole_test values('%s','%s','%s')",className,formulaeUserNonRefined,FormulaeUserRefined);
            Connection myConn = DriverManager.getConnection("jdbc:mysql://localhost:3306/CMO","utente_web","CMOREL96T45");
            Statement myStmt = myConn.createStatement();
            // attenzione se devi modificare questa, assicurati di cancellare prima la tabella
            myStmt.executeUpdate( "create table if not exists regole_test (terapia VARCHAR(256) PRIMARY KEY, not_refined TEXT, refined TEXT)");

            myStmt.executeUpdate(qry);

        }

    }

    public static void test_Formulae() throws Exception {

        List<String> datasets = new LinkedList<>();
        datasets.add("cpu.arff");
        datasets.add("colic.arff");
        datasets.add("credit.arff");
        datasets.add("derma.arff");
        datasets.add("ecoli.arff");

        for (String dataset : datasets) {

            ArffLoader loader = new ArffLoader();
            loader.setFile(new File(String.format("Tests/"+dataset)));
            Instances dati = loader.getDataSet();
            dati.setClassIndex(dati.numAttributes() - 1);

            JRip cls = new JRip();
            cls.buildClassifier(dati);
            Evaluation evl = new Evaluation(dati);
            evl.evaluateModel(cls, dati);
            //System.out.println(evl.toSummaryString());
            //System.out.println(cls);


            Rules rules = new Rules(cls);
            System.out.println(rules.getAccuracy(dati));

            Formulae ff = new Formulae(rules);
            System.out.println(ff.getAccuracy(dati));

            System.out.println();
        }

    }
}