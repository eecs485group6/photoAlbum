package edu.umich.eecs485.pa4;

import java.io.File;
import java.io.IOException;
import java.io.FileNotFoundException;
import java.io.BufferedWriter;
import java.io.FileWriter;
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
      FileWriter fw = new FileWriter(outputFile.getAbsoluteFile());
      BufferedWriter bw = new BufferedWriter(fw);
      Scanner prescan = new Scanner(f);
      while (prescan.hasNext()) {
        hs.add(prescan.nextLine());
      }      
      Scanner scan = new Scanner(contentFile);
      int picid = 0;
      while (scan.hasNext()) {
        String s = scan.nextLine();
        if (s.equals("")) continue;
        picid++;
        String[] subS = s.split("\\s*[^0-9a-zA-Z']+\\s*"); 
        // regex: 0 or multi spaces and one or more character between them
        for (String sub: subS) {
          sub = sub.toLowerCase();
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
          bw.write(key+" "+idf
              +" "+total+" "+docid+" "+tf + " "+tfidf_normalization.get(docid));
          bw.newLine();
        }
      }
      bw.flush();
      bw.close();
    }
    catch (IOException fnfe) {
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
