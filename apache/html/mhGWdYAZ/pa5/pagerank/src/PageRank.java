import java.util.*;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.*;

public class PageRank {
  public static void main (String [] args) {
    HashMap<Integer, Double> pagerank = new HashMap<Integer, Double>();
    HashMap<Integer, Double> pagerank_temp = new HashMap<Integer, Double>();
    HashMap<Integer, Vector<Integer>> pointlist = new HashMap<Integer, Vector<Integer>>();
    // check condition
    double dvalue = Double.parseDouble(args[0]);
    boolean iteration_flag = true;
    int numiterations = 0;
    double maxchange = 0.0;
    String s = "";
    HashSet<Integer> deadNodes = new HashSet<Integer>();
    if (args.length < 5) {
      System.out.println("Error. Usage: java filename <dvalue> (-k <numiterations> | -converge <maxch    ange>) <inputfile> <outputfile>");
      System.exit(0);
    
    }
    if (args[1].equals("-k")) {
      numiterations = Integer.parseInt(args[2]); 
    } else if (args[1].equals("-converge")){
      iteration_flag = false;
      maxchange = Double.parseDouble(args[2]);
    }
    else {
      System.out.println("the op is not supported.");
      System.out.println("Error. Usage: java filename <dvalue> (-k <numiterations> | -converge <maxch    ange>) <inputfile> <outputfile>");
    }
    try {
      
      FileReader fr = new FileReader(args[3]);
      BufferedReader br = new BufferedReader(fr);
      String [] verticesString = br.readLine().split(" ");
      int vnum = Integer.parseInt(verticesString[1]);
      for (int i = 0; i < vnum; i++) {
        s = br.readLine();
        //get rid of empty line
        if (s.length() <= 1) {i--; continue;}
        String [] vertexString = s.split(" ");
        int docid = Integer.parseInt(vertexString[0]);
        //System.out.println(i+" "+docid);
        if (pagerank.containsKey(docid)) {
          System.err.println("ERROR! "+docid+" already exists in the list.");
          System.exit(1);
        }
        Vector<Integer> newvector = new Vector<Integer>(1);
        pointlist.put(docid, newvector);
        pagerank.put(docid, 1.0/vnum);
      }
      //System.out.println("start arc");
      String [] arcsString = br.readLine().split(" ");
      int anum = Integer.parseInt(arcsString[1]);
      //System.out.println(anum);
      for (int i = 0; i < anum; i++) {
        s= br.readLine();
        //get rid of empty line
        if (s.length() == 0) {i--; continue;}
        String [] arcString = s.split(" ");
        int doc1 = Integer.parseInt(arcString[0]);
        int doc2 = Integer.parseInt(arcString[1]);
        // remove self link
        //System.out.println(i+" "+doc1+" "+doc2);
        if (doc1 != doc2) {
          Vector<Integer> vec = pointlist.get(doc1);
          // vector may not be safe if same node is added twice.
          if (!vec.contains(doc2)) {
            vec.add(doc2);
            pointlist.put(doc1, vec);
          }
        } 
      }
      br.close();

      //System.out.println("virtual begin!");
      // add virtual links
      for (Map.Entry<Integer, Vector<Integer>> entry: pointlist.entrySet()) {
        int docid = entry.getKey().intValue();
        Vector<Integer> vec = entry.getValue();
        if (vec.size() == 0) {
          deadNodes.add(docid);
        }  
      }
      //System.out.println("Begin!"); 
      // begin calcualtion
      while (true) {
        pagerank_temp = new HashMap<Integer, Double>();
        //deadsum
        double deadSum = 0.0;
        Iterator<Integer> it = deadNodes.iterator();
        while (it.hasNext()) {
          int docid = it.next();
          if (!pagerank.containsKey(docid)) {
            System.out.println(docid + "does not exist.");
            System.exit(1);
          }
          deadSum += pagerank.get(docid);
        }
        for(Map.Entry<Integer, Double> entry: pagerank.entrySet()) {
          int docid = entry.getKey().intValue();
          if (deadNodes.contains(docid)) 
            pagerank_temp.put(docid, (1.0-dvalue)/vnum+dvalue*(deadSum-pagerank.get(docid))/(vnum-1));
          else
            pagerank_temp.put(docid, (1.0-dvalue)/vnum+dvalue*deadSum/(vnum-1));
        }
        //deadsum end
        for (Map.Entry<Integer, Vector<Integer>> entry: pointlist.entrySet()) {
          int docid = entry.getKey().intValue();
          double pr = pagerank.get(docid);
          Vector<Integer> vec = entry.getValue();
          //System.out.println("docid:"+docid);
          //Thread.sleep(2000);
          if (vec.size() > 0) {
            Iterator<Integer> itr = vec.iterator();
            while (itr.hasNext()) {
              int target = itr.next().intValue();
              //System.out.println("target:"+target);
              if (pagerank_temp.containsKey(target))
                pagerank_temp.put(target, pagerank_temp.get(target)+dvalue*(pr)/vec.size());
              else {
                System.out.println("ERROR. target "+target+" does not exist.");
                System.exit(1); 
              }
            }
          }
        }

        //System.out.println("half way!");
        if (iteration_flag) {
          numiterations--;
          pagerank = new HashMap<Integer, Double>(pagerank_temp);
          if (numiterations <= 0) break;
        }
        else {
          boolean finish = true;
          for (Map.Entry<Integer, Double> entry: pagerank.entrySet()) {
            int docid = entry.getKey().intValue();
            double score = entry.getValue();
            double nextScore = pagerank_temp.get(docid);
            if (Math.abs(score-nextScore)/(score)*100>maxchange) {
              finish = false;
              break;
            }
          }
          pagerank = new HashMap<Integer, Double>(pagerank_temp);
          if (finish) {
            break;
          }
        }
      }
      File f = new File(args[4]);
      FileWriter fw = new FileWriter(f);
      BufferedWriter bw = new BufferedWriter(fw);
      
      // sort hashmap
      Map<Integer, Double> pagerank_treemap = new TreeMap<Integer, Double>(pagerank);

      for (Map.Entry<Integer, Double> entry: pagerank_treemap.entrySet()) {
        int docid = entry.getKey().intValue();
        double score = entry.getValue();
        bw.write(docid+" "+score);
        bw.newLine();
      }
      bw.flush();
      bw.close();
    }
    catch (IOException ioe){
      System.err.println(ioe.toString());
      ioe.printStackTrace();
      System.out.println(s);
    }
  }
}
