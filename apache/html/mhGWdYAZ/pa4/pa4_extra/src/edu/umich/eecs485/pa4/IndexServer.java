package edu.umich.eecs485.pa4;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.HashMap;
import java.util.HashSet;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.Map;
import java.util.Scanner;
import java.util.Iterator;

import java.sql.*;
import java.util.Properties;

import edu.umich.eecs485.pa4.utils.QueryHit;
import edu.umich.eecs485.pa4.utils.GenericIndexServer;

/*******************************************************
 * The <code>IndexServer</code> loads an inverted index and processes
 * user queries.  It returns Hit objects that are then returned to the
 * PHP server over the network.
 *
 * Its superclass is GenericIndexServer, which provides basic network
 * and serialization functionality.
 *******************************************************/
public class IndexServer extends GenericIndexServer {
  /**
   * Creates a new <code>IndexServer</code> instance.
   *
   * The superclass needs a port to listen on.
   * We store fname in a member variable for later use.
   */
  private static HashMap<String, HashMap<Integer, HashSet<Integer>>> map;
  private static HashMap<Integer, String> captions;
  public IndexServer(int port, File fname) throws IOException {
    super(port, fname);
  }

  /**
   * This method is called once when the server is first started.
   * Inside this method you should load the inverted index from disk.
   *
   * Fill in this method to do something useful!
   */
  public void initServer(File fname) {
    // Do something!
    map = new HashMap<String, HashMap<Integer, HashSet<Integer>>>();
    try {
      Scanner check = new Scanner(fname);
      while(check.hasNext()) {
        String [] subS = check.nextLine().split(" ");
        String key = subS[0];
        double idf = Double.parseDouble(subS[1]);
        int total = Integer.parseInt(subS[2]);
        int docid = Integer.parseInt(subS[3]);
        int tf = Integer.parseInt(subS[4]);
        HashSet<Integer> posHS = new HashSet<Integer>();
        for (int i = 0; i < tf; i++) {
         posHS.add(Integer.parseInt(subS[5+i]));
        }
        double tfidf_normalization = Double.parseDouble(subS[subS.length-1]);
        if (map.containsKey(key)) {
          HashMap<Integer, HashSet<Integer>> now = map.get(key);
          now.put(docid, posHS);
          map.put(key, now);
        }
        else {
          HashMap<Integer, HashSet<Integer>> now = new HashMap<Integer, HashSet<Integer>>();
          now.put(docid, posHS);
          map.put(key, now);
        } 
      }
    }
    catch (IOException ioe) {
      System.err.println("cannot open fname " + fname);
    }
    // caption list
    // CARE! the caption list starts from index 0. 
    captions = new HashMap<Integer, String>();
    String db_name = "group6";
    String db_user = "group6";
    String db_pass = "01302013";
    Connection conn = null;
    Statement statement = null;
    ResultSet resultSet = null;


    try {
      System.out.println("Connecting to mysql DB...");
      String db_url = "jdbc:mysql://localhost:3306/" + db_name;
      
      Class.forName("com.mysql.jdbc.Driver").newInstance();
      
      conn = DriverManager.getConnection(db_url, db_user, db_pass);

      statement = conn.createStatement();
      
      // query captions

      String queryString = "SELECT caption, sequencenum FROM Contain WHERE albumid=5 ORDER BY sequencenum";
      resultSet = statement.executeQuery(queryString);
      while (resultSet.next()) {
        int picid = Integer.parseInt(resultSet.getString("sequencenum"));
        String caption = resultSet.getString("caption");
        captions.put(picid, caption);
      } 
    }
    catch(Exception e) {
      System.err.println("cannot connect mysql server");
    }
    finally {
      try {
        if (resultSet != null)
          resultSet.close();

        if (statement != null) 
          statement.close();


        if (conn != null) 
          conn.close();

      } catch (Exception e) {
        System.err.println("sql close error");
      }
    }
  }
  /**
   * The <code>processQuery</code> method takes a user query and
   * returns a relevance-ranked and scored list of document hits.
   * If the list is empty, then there are zero hits for the query. 
   *
   * This method should never return null.
   *
   * Fill in this method to do something useful!
   */
  public List<QueryHit> processQuery(String query) {
    System.out.println("processing normal query: "+query);
    // Query Hit result
    ArrayList<QueryHit> result = new ArrayList<QueryHit>();
    //doc score
    HashMap<Integer, Double> scoreMap = new HashMap<Integer, Double>();
    //hash map doc total score
    HashMap<Integer, Double> docTotal = new HashMap<Integer, Double>();
    // filter for common strings
    HashSet<String> hs_filter = new HashSet<String>();
    try{
      // set filter
      File f = new File("Redirecting.txt");
      Scanner prescan = new Scanner(f);
      while (prescan.hasNext()) {
        hs_filter.add(prescan.nextLine());
      }
      String [] que = query.split("\\s*[^0-9a-zA-Z']+\\s*");
      int total = 0;
      for (String subQ : que) {
        subQ = subQ.toLowerCase();
        if (hs_filter.contains(subQ)) continue;
        if (map.containsKey(subQ)) {
          HashMap<Integer, HashSet<Integer>> now = map.get(subQ);
          double tfidf = Math.log(captions.size()/now.size())/Math.log(10);
          if (!hs_filter.contains(subQ)) total+=tfidf*tfidf;
          //System.out.println("size="+now.size());
          for (Map.Entry<Integer, HashSet<Integer>> entry: now.entrySet()) {
            int docid = entry.getKey().intValue();
            int tf_doc = entry.getValue().size();
            //System.out.println(docid); 

            double tfidf_doc = tf_doc*Math.log(captions.size()/now.size());
            // if not calculated total before
            //System.out.println("GOOD!~"+docid);
            if (!docTotal.containsKey(docid)) 
              docTotal.put(docid, calDocTotal(docid));
            // get tfidf_normalization of doc
            //System.out.println("docTotal="+docTotal.get(docid));
            double normal_tfidf_doc = tfidf/Math.sqrt(docTotal.get(docid));
            
            // we will divide by total of the query words later
            if (scoreMap.containsKey(docid)) {
              scoreMap.put(docid, scoreMap.get(docid)+tfidf*normal_tfidf_doc);
            }
            else scoreMap.put(docid, tfidf*normal_tfidf_doc);

            //System.out.println(subQ+ " docid="+ docid+" tfidf="+tfidf+" ntfidf="+normal_tfidf_doc);
          }
        }
         
      }
      for (Map.Entry<Integer, Double> entry: scoreMap.entrySet()) {
        String id = entry.getKey().toString();
        double score = entry.getValue().doubleValue();
        if (total == 0)
          total++;
        result.add(new QueryHit(id, score/Math.sqrt(total)));
      }
      System.out.println("Normal Query Done. Total:"+result.size()+" results");
      return result; 
    }
    catch(FileNotFoundException e){
      System.err.println("Processing query '" + query + "'");
      return new ArrayList<QueryHit>();
    }
  }
   /**
   * The <code>processQuery2</code> method takes a user query phrase and
   * returns a relevance-ranked and scored list of document hits. Notice
   * here only documents that strictly contain the phrase can be returned.
   */
  public List<QueryHit> processQuery2(String query) {
    System.out.println("Processing phrase query: "+query);
    // Query Hit result
    ArrayList<QueryHit> result = new ArrayList<QueryHit>();
    //doc score
    HashMap<Integer, Double> scoreMap = new HashMap<Integer, Double>();
    //hash map doc total score
    HashMap<Integer, Double> docTotal = new HashMap<Integer, Double>();
    // filter for common strings
    HashSet<String> hs_filter = new HashSet<String>();
    try{
      // set filter
      File f = new File("Redirecting.txt");
      Scanner prescan = new Scanner(f);
      while (prescan.hasNext()) {
        hs_filter.add(prescan.nextLine());
      }
      String [] que = query.split("\\s*[^0-9a-zA-Z']+\\s*");
      for (int i = 0; i < que.length; i++)
        que[i] = que[i].toLowerCase();

      int total = 0;

      if (que != null && map.containsKey(que[0])) {
        HashMap<Integer, HashSet<Integer>> first = map.get(que[0]);
        for (Map.Entry<Integer, HashSet<Integer>> entry: first.entrySet()) {
          int docid = entry.getKey().intValue();
          HashSet<Integer> firstHS = entry.getValue();
          Iterator<Integer> itr = firstHS.iterator();
          while (itr.hasNext()) {
            boolean flag = true;
            int pos = itr.next().intValue();
            for (int i = 1; i < que.length; i++) {
              //DEBUG: System.out.println(que[i]+ " pos="+pos+" docid="+ docid);
              if (!map.containsKey(que[i]) ||
                  !map.get(que[i]).containsKey(docid) ||
                  !map.get(que[i]).get(docid).contains(pos+i)) {
                flag = false;
                break;
                  }
            }
            if (flag) {
              scoreMap.put(docid, 0.0);
              break;
            }
          }
        }
        for (String subQ : que) {
          if (map.containsKey(subQ)) {
            HashMap<Integer, HashSet<Integer>> now = map.get(subQ);
            double tfidf = Math.log(captions.size()/now.size())/Math.log(10);
            if (!hs_filter.contains(subQ)) total+=tfidf*tfidf;

            for (Map.Entry<Integer, HashSet<Integer>> entry: now.entrySet()) {
              int docid = entry.getKey().intValue();
              if (!scoreMap.containsKey(docid)) continue;
              int tf_doc = entry.getValue().size();
              double tfidf_doc = tf_doc*Math.log(captions.size()/now.size());
              // if not calculated total before
              if (!docTotal.containsKey(docid)) 
                docTotal.put(docid, calDocTotal(docid));
              // get tfidf_normalization of doc
              double normal_tfidf_doc = tfidf/Math.sqrt(docTotal.get(docid));

              // we will divide by total of the query words later
              if (scoreMap.containsKey(docid)) {
                scoreMap.put(docid, scoreMap.get(docid)+tfidf*normal_tfidf_doc);
              }
              else scoreMap.put(docid, tfidf*normal_tfidf_doc);
            }
          }
        }   
      }
      for (Map.Entry<Integer, Double> entry: scoreMap.entrySet()) {
        String id = entry.getKey().toString();
        double score = entry.getValue().doubleValue();
        if (total == 0)
          total++;
        result.add(new QueryHit(id, score/Math.sqrt(total)));
      }
      System.out.println("Phrase Query Done. Total:"+result.size()+" results");
      return result; 
    }
    catch(FileNotFoundException e){
      System.err.println("Processing query '" + query + "'");
      return new ArrayList<QueryHit>();
    }
  }

  /**
   *  Calculate total sum(tf*tf*idf*idf) of one doc.
   *  Used for process queries.
   */
  public static double calDocTotal(int docid) {
    String caption = captions.get(docid);
    double total = 0;
    //if (docid == 178) System.out.println(caption);
    String [] words = caption.split("\\s*[^0-9a-zA-Z']+\\s*");
    //if (docid == 178) System.out.println(words.length);
      for (String word : words) {
        //if (docid == 178) System.out.println(word);
        word = word.toLowerCase();
        double tf = map.get(word).get(docid).size();
        double idf = Math.log(captions.size()/map.get(word).size())/Math.log(10);
        total += tf*tf*idf*idf;
        //System.out.println("good"+word);
    } 
    return total;
  }
  public boolean updateCaption(int sequencenum, String newcaption) {
    String oldcaption = captions.get(sequencenum);
    System.out.println("Update "+sequencenum+": "+oldcaption+" -> "+newcaption);
    String [] words = oldcaption.split("\\s*[^0-9a-zA-Z']+\\s*");
    for (int i = 0; i < words.length; i++) {
      words[i] = words[i].toLowerCase();
      HashMap<Integer, HashSet<Integer>> HM = map.get(words[i]);
      HM.remove(sequencenum);
      map.put(words[i], HM);
    }
    captions.put(sequencenum, newcaption);
    words = newcaption.split("\\s*[^0-9a-zA-Z']+\\s*");
    for (int i = 0; i < words.length; i++) {
      words[i] = words[i].toLowerCase();
      if (map.containsKey(words[i])) {
        HashMap<Integer, HashSet<Integer>> now = map.get(words[i]);
        HashSet<Integer> posHS;
        if (now.containsKey(sequencenum)) 
          posHS = now.get(sequencenum);
        else
          posHS = new HashSet<Integer>();
        posHS.add(i);
        now.put(sequencenum, posHS);
        map.put(words[i], now);
      }
      else {
        HashSet<Integer> posHS = new HashSet<Integer>();
        posHS.add(i);
        HashMap<Integer, HashSet<Integer>> now = new HashMap<Integer, HashSet<Integer>>();
        now.put(sequencenum, posHS);
        map.put(words[i], now);
      } 
    }
    return true;
  } 
  /**
   * Parse the command-line args.  Then start up the server.
   */
  public static void main(String argv[]) throws IOException {
    if (argv.length < 2) {
      System.err.println("Usage: IndexServer <portnum> <inverted-index-filename>");
      return;
    }

    // Parse args
    int i = 0;
    int portnum = -1;
    try {
      portnum = Integer.parseInt(argv[i++]);
    } catch (NumberFormatException nfe) {
      System.err.println("Cannot parse port number: " + argv[i-1]);
      return;
    }
    File fname = new File(argv[i++]).getCanonicalFile();

    // Run server.  Note that because server.serve() creates a new
    // thread, the process will not terminate even though serve() returns.
    IndexServer server = new IndexServer(portnum, fname);
    server.serve();
  }
}
