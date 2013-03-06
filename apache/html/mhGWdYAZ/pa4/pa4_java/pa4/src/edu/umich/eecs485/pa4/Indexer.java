package edu.umich.eecs485.pa4;

import java.io.File;
import java.io.IOException;
import java.io.FileNotFoundException;
import java.util.*;

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
    HashMap<String, HashMap<Integer, Integer>> map = new HashMap<String, HashMap<Integer, Integer>>();
    HashMap<Integer, Double> tfidf_normalization = new HashMap<Integer, Double>();
    HashSet<String> hs = new HashSet<String>(); 
    try {
      File f = new File("Redirecting.txt");
      Scanner prescan = new Scanner(f);
      while (prescan.hasNext()) {
        hs.add(prescan.nextLine());
      }      
      Scanner scan = new Scanner(contentFile);
      int picid = 0;
      String sub2 = "";
      boolean flag = false;
      while (scan.hasNext()) {
        String s = scan.nextLine();
        if (s.equals("")) continue;
        picid++;
        String[] subS = s.split(" ");
        for (String sub: subS) {
          if (sub.equals("&") || sub.equals("/") || sub.equals("-")) continue;
          if (sub.startsWith("(") || sub.startsWith("#")) sub = sub.substring(1);
          if (sub.endsWith(")") || sub.endsWith("!") || sub.endsWith("?") || sub.endsWith(","))
            sub = sub.substring(0, sub.length()-1);
          sub = sub.toLowerCase();
          if (sub.indexOf('(') != -1) {
            flag = true;
            sub2 = sub.substring(sub.indexOf('(')+1);
            sub = sub.substring(0, sub.indexOf('('));
          }
          //sub
          if (!hs.contains(sub)) {
            if (map.containsKey(sub)) {
              HashMap<Integer, Integer> now = map.get(sub);
              if (now.containsKey(picid)) {
                now.put(picid, now.get(picid)+1);
                map.put(sub, now);
              }
              else { 
                now.put(picid, 1);
                map.put(sub, now);
              }
            }
            else {
              HashMap<Integer, Integer> now = new HashMap<Integer, Integer>();
              now.put(picid, 1); 
              map.put(sub, now);
            }
          }
          //sub2
          if (flag && !hs.contains(sub2)) {
            if (map.containsKey(sub2)) {
              HashMap<Integer, Integer> now = map.get(sub2);
              if (now.containsKey(picid)) {
                now.put(picid, now.get(picid)+1);
                map.put(sub2, now);
              }
              else { 
                now.put(picid, 1);
                map.put(sub2, now);
              }
            }
            else {
              HashMap<Integer, Integer> now = new HashMap<Integer, Integer>();
              now.put(picid, 1); 
              map.put(sub2, now);
            }
            flag = false;
          }
        }
      }
      //second step
      for (Map.Entry<String, HashMap<Integer, Integer>> entry: map.entrySet()) {
        //String key = entry.getKey();
        HashMap<Integer, Integer> now = entry.getValue();
        int nk = now.size();
        for (Map.Entry<Integer, Integer> ent: now.entrySet()) {
          //total += ent.getValue().intValue();
          int docid = ent.getKey().intValue();
          int tf = ent.getValue().intValue();
          double tfidf = tf*Math.log((double)picid/nk)/Math.log(10);
          if (tfidf_normalization.containsKey(docid))
            tfidf_normalization.put(docid, tfidf_normalization.get(docid)+tfidf*tfidf);
          else tfidf_normalization.put(docid, tfidf*tfidf);
        }
      }


      // second step
      for (Map.Entry<String, HashMap<Integer, Integer>> entry: map.entrySet()) {
        String key = entry.getKey();
        HashMap<Integer, Integer> now = entry.getValue();
        int nk = now.size();
        int total = 0;
        for (Map.Entry<Integer, Integer> ent: now.entrySet()) {
          total += ent.getValue().intValue();
        }
        for (Map.Entry<Integer, Integer> ent: now.entrySet()) {
          int docid = ent.getKey().intValue();
          int tf = ent.getValue().intValue();
          double idf = Math.log((double)picid/nk)/Math.log(10);
          System.out.println(key+" "+idf
              +" "+total+" "+docid+" "+tf + " "+tfidf_normalization.get(docid));
        }
      }
    }
    catch (FileNotFoundException fnfe) {
      System.err.println(fnfe.getMessage());
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
