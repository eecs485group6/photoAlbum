package edu.umich.eecs485.pa4;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.HashMap;
import java.util.HashSet;
import java.io.FileNotFoundException;
import java.util.Map;
import java.util.Scanner;


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
  
    //System.err.println("Init server with fname " + fname);
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
    // Query Hit result
    ArrayList<QueryHit> result = new ArrayList<QueryHit>();
    //doc score
    HashMap<String, Double> map = new HashMap<String, Double>();
    //hash map query strings
    HashMap<String, Integer> querys = new HashMap<String, Integer>();
    // filter for common strings
    HashSet<String> hs_filter = new HashSet<String>();
    boolean flag = false;
    try{
      // set filter
      File f = new File("Redirecting.txt");
      Scanner prescan = new Scanner(f);
      while (prescan.hasNext()) {
        hs_filter.add(prescan.nextLine());
      }
      String [] que = query.split(" ");
      for (String subQ : que) {
        System.out.println(subQ+ "     "+que.length);
        if (subQ.equals("&") || subQ.equals("/") || subQ.equals("-")) continue;
        if (subQ.startsWith("(") || subQ.startsWith("#")) subQ = subQ.substring(1);
        if (subQ.endsWith(")") || subQ.endsWith("!") || subQ.endsWith("?") || subQ.endsWith(","))
          subQ = subQ.substring(0, subQ.length()-1);
        subQ = subQ.toLowerCase();
        String subQ2 = "";
        if (subQ.indexOf('(') != -1) {
          flag = true;
          subQ2 = subQ.substring(subQ.indexOf('(')+1);
          subQ = subQ.substring(0, subQ.indexOf('(')); 
        } 	
        if (!hs_filter.contains(subQ))
          if (!querys.containsKey(subQ))
            querys.put(subQ, 1);
          else querys.put(subQ, querys.get(subQ)+1);

        if (flag && !hs_filter.contains(subQ2))
          if (!querys.containsKey(subQ2))
            querys.put(subQ2, 1);
          else querys.put(subQ2, querys.get(subQ2)+1);
        flag = false;
      }
      double total = 0;
      for (Map.Entry<String, Integer> entry: querys.entrySet()) {
        String q = entry.getKey();
        int tf = entry.getValue().intValue();
        Scanner check = new Scanner(fname);
        while (check.hasNext()) {
          String [] checkstr = check.nextLine().split(" ");
          if (checkstr[0].equals(q)) {
              double current_tfidf = 
                Double.parseDouble(checkstr[1])*Double.parseDouble(checkstr[4])/Math.sqrt(Double.parseDouble(checkstr[5]));
              if (map.containsKey(checkstr[3]))
                map.put(checkstr[3],
                    map.get(checkstr[3])+current_tfidf*tf*Double.parseDouble(checkstr[1])); // checkstr[1] == idf
              else {
                total += tf*tf*Double.parseDouble(checkstr[1])*Double.parseDouble(checkstr[1]);
                map.put(checkstr[3], current_tfidf*tf*Double.parseDouble(checkstr[1]));
              }
          }
        } 
      }
      System.out.println(total);
      for (Map.Entry<String, Double> entry: map.entrySet()) {
        String id = entry.getKey();
        double score = entry.getValue().doubleValue();
        result.add(new QueryHit(id, score/Math.sqrt(total)));
      }
      return result; 
    }
    catch(FileNotFoundException e){
      System.err.println("Processing query '" + query + "'");
      return new ArrayList<QueryHit>();
    }
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
