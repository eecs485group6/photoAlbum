package edu.umich.eecs485.pa4;

import java.io.File;
import java.io.IOException;
import java.io.FileNotFoundException;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.util.*;
import java.io.IOException;

import java.sql.*;
import java.util.Properties;


/*********************************************************
 * <code>Indexer</code> reads in some raw content and writes to
 * an inverted index file
 *********************************************************/
public class Indexer {
  public Indexer() {
  }

  /**
   * The <code>index</code> code transforms the content into the
   * actual on-disk inverted index file.
   *
   * Fill in this method to do something useful!
   */
  public void index(File contentFile, File outputFile) {
    // Do something!
    HashMap<String, HashMap<Integer, ArrayList<Integer>>> map = new HashMap<String, HashMap<Integer, ArrayList<Integer>>>();
    HashMap<Integer, Double> tfidf_normalization = new HashMap<Integer, Double>();
    HashSet<String> hs = new HashSet<String>(); 
    int picid = 0;
    HashMap<Integer, String> captions = new HashMap<Integer, String>();
    String db_name = "group6";
    String db_user = "group6";
    String db_pass = "01302013";
    Connection conn = null;
    Statement statement = null;
    ResultSet resultSet = null;

    try {
      File f = new File("Redirecting.txt");
      FileWriter fw = new FileWriter(outputFile.getAbsoluteFile());
      BufferedWriter bw = new BufferedWriter(fw);
      Scanner prescan = new Scanner(f);
      while (prescan.hasNext()) {
        hs.add(prescan.nextLine());
      }
      // content file captions is broken. Do not use it.
      // connect to mysql to get all the current captions.
      //Scanner scan = new Scanner(contentFile);
            System.out.println("Connecting to mysql DB...");
      String db_url = "jdbc:mysql://localhost:3306/" + db_name;
      
      Class.forName("com.mysql.jdbc.Driver").newInstance();

      conn = DriverManager.getConnection(db_url, db_user, db_pass);

      statement = conn.createStatement();

      String queryString = "SELECT caption, sequencenum FROM Contain WHERE albumid=5 ORDER BY sequencenum";
      resultSet = statement.executeQuery(queryString);
      while (resultSet.next()) {
        String s = resultSet.getString("caption");
        picid = Integer.parseInt(resultSet.getString("sequencenum"));
        if (s.equals("")) continue;
        //picid++;
        String[] subS = s.split("\\s*[^0-9a-zA-Z']+\\s*"); 
        // regex: 0 or multi spaces and one or more character between them
        for (int i = 0; i < subS.length; i++) {
          subS[i] = subS[i].toLowerCase();
          //if (!hs.contains(sub)) {
            if (map.containsKey(subS[i])) {
              HashMap<Integer, ArrayList<Integer>> now = map.get(subS[i]);
              if (now.containsKey(picid)) {
                ArrayList<Integer> al = now.get(picid);
                al.add(i);
                now.put(picid, al);
                map.put(subS[i], now);
              }
              else { 
                ArrayList<Integer> al = new ArrayList<Integer>();
                al.add(i);
                now.put(picid, al);
                map.put(subS[i], now);
              }
            }
            else {
              HashMap<Integer, ArrayList<Integer>> now = new HashMap<Integer, ArrayList<Integer>>();
              ArrayList<Integer> al = new ArrayList<Integer>();
              al.add(i);
              now.put(picid, al); 
              map.put(subS[i], now);
            }
          //}
        }
      }
      //second step
      for (Map.Entry<String, HashMap<Integer, ArrayList<Integer>>> entry: map.entrySet()) {
        String key = entry.getKey();
        
        HashMap<Integer, ArrayList<Integer>> now = entry.getValue();
        int nk = now.size();
        for (Map.Entry<Integer, ArrayList<Integer>> ent: now.entrySet()) {
          //total += ent.getValue().intValue();
          int docid = ent.getKey().intValue();
          int tf = ent.getValue().size();
          double tfidf = tf*Math.log((double)picid/nk)/Math.log(10);
          if (hs.contains(key)) tfidf = 0;
          if (tfidf_normalization.containsKey(docid))
            tfidf_normalization.put(docid, tfidf_normalization.get(docid)+tfidf*tfidf);
          else tfidf_normalization.put(docid, tfidf*tfidf);
        }
      }


      // second step
      for (Map.Entry<String, HashMap<Integer, ArrayList<Integer>>> entry: map.entrySet()) {
        String key = entry.getKey();
        HashMap<Integer, ArrayList<Integer>> now = entry.getValue();
        int nk = now.size();
        int total = 0;
        for (Map.Entry<Integer, ArrayList<Integer>> ent: now.entrySet()) {
          total += ent.getValue().size();
        }
        for (Map.Entry<Integer, ArrayList<Integer>> ent: now.entrySet()) {
          int docid = ent.getKey().intValue();
          ArrayList<Integer> posAL = ent.getValue();
          int tf = posAL.size();
          double idf = Math.log((double)picid/nk)/Math.log(10);
          if (hs.contains(key)) idf = 0;
          bw.write(key+" "+idf
              +" "+total+" "+docid+" "+tf);
          for (int i = 0; i < tf; i++) {
            bw.write (" "+posAL.get(i));
          }
          bw.write(" "+tfidf_normalization.get(docid));
          bw.newLine();
        }
      }
      bw.flush();
      bw.close();
    }
    catch (Exception e) {
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
   * Parse the command-line args.
   */
  public static void main(String argv[]) throws IOException {
    if (argv.length < 2) {
      System.err.println("Usage: Indexer <content-filename> <inverted-index-filename>");
      return;
    }
    int i = 0;
    File contentFname = new File(argv[i++]).getCanonicalFile();
    File invertedIndexFname = new File(argv[i++]).getCanonicalFile();

    Indexer indexer = new Indexer();
    indexer.index(contentFname, invertedIndexFname);
  }
}
