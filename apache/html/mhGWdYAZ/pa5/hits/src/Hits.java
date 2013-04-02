import java.util.*;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;

public class Hits {
  public static HashSet<Integer> seeds = new HashSet<Integer>();
  public static HashSet<Integer> base = new HashSet<Integer>();
  public static HashMap<Integer, String> docs = new HashMap<Integer, String>();
  public static HashMap<Integer, Double> ascores = new HashMap<Integer, Double>();
  public static HashMap<Integer, Double> hscores = new HashMap<Integer, Double>();
  public static HashMap<Integer, HashSet<Integer>> linkout = new HashMap<Integer, HashSet<Integer>>();
  public static HashMap<Integer, HashSet<Integer>> linkin = new HashMap<Integer, HashSet<Integer>>();
  public static int numiterations;
  public static double maxchange;
  public static void main (String [] args) {
    //System.out.println("Hello Hits!");
	if(args.length < 7) {
      System.out.println("Error. Usage: filename <h value> (-k <numiterations> | -converge <maxchange>) 'queries' <inputnetfile> <inputinvertedindexfile> <outputfile>");
      System.exit(0);
    }
	int hvalue = Integer.parseInt(args[0]);
	boolean iteration_flag = true;
	if (args[1].equals("-k")) {
      numiterations = Integer.parseInt(args[2]); 
    } else {
      iteration_flag = false;
      maxchange = Double.parseDouble(args[2]);
    }
	String queries = args[3];
	String netfile = args[4];
	String indexfile = args[5];
	String outputfile = args[6];
	SeedSet(queries, indexfile, hvalue);
	BaseSet(netfile);
	//find all the pages a page links to and all the pages that have link to it
	for (Integer doc : base) {
		int docid = doc.intValue();
		
	}
	//init ascores and hscores
	for (Integer doc : base) {
	  ascores.put(doc, 1.0);
	  hscores.put(doc, 1.0);
	}
	  
	  // begin calcualtion
	  
      while (true) {
        HashMap<Integer, Double> ascores_temp = new HashMap<Integer, Double>(ascores);
		HashMap<Integer, Double> hscores_temp = new HashMap<Integer, Double>(hscores);
		double a_norm = 0.0;
		double h_norm = 0.0;
		boolean finish = true;
		double max = 0.0;
		for (Map.Entry<Integer, Double> entry: ascores.entrySet()) {
          int docid = entry.getKey().intValue();
          double oldascore = entry.getValue().doubleValue();
          double ascore = 0;
		  HashSet<Integer> linkins = linkin.get(docid);
		  if (linkins == null) {
		    //System.out.println(docid+" linkins hashset null");
		  } else {
		    for (Integer doc : linkins) {
			  ascore += hscores_temp.get(doc);
		    }
		  }
		  ascores.put(docid, ascore);
		  a_norm += ascore * ascore;
		}
		for (Map.Entry<Integer, Double> entry: ascores.entrySet()) {
		  int docid = entry.getKey().intValue();
		  double ascore_n = entry.getValue().doubleValue()/Math.sqrt(a_norm);
		  ascores.put(docid, ascore_n);
		  double oldascore = ascores_temp.get(docid);
		  double ave_ascore = (ascore_n + oldascore) / 2.0;
		  if (ave_ascore == 0.0) ave_ascore = 1.0;
		  if (Math.abs(ascore_n-oldascore)/(ave_ascore)*100 > max) {
            max = Math.abs(ascore_n-oldascore)/(ave_ascore)*100;
          }
		}
		
		for (Map.Entry<Integer, Double> entry: hscores.entrySet()) {
          int docid = entry.getKey().intValue();
          double hscore = 0;
		  HashSet<Integer> linkouts = linkout.get(docid);
		  if (linkouts == null) {
			//System.out.println(docid+" linkout hashset null");
		  } else {
		    for (Integer doc : linkouts) {
		      hscore += ascores_temp.get(doc);
		    }
		  }
		  hscores.put(docid, hscore);
		  h_norm += hscore * hscore;
		}
		for (Map.Entry<Integer, Double> entry: hscores.entrySet()) {
		  int docid = entry.getKey().intValue();
		  double hscore_n = entry.getValue().doubleValue()/Math.sqrt(h_norm);
		  hscores.put(docid, hscore_n);
		  double oldhscore = hscores_temp.get(docid);
		  //System.out.println(oldhscore+" oldhscore");
		  //System.out.println(hscore_n+" new hscore");
		  double ave_hscore = (hscore_n + oldhscore) / 2.0;
		  if (ave_hscore == 0.0) ave_hscore = 1.0;
		  if (Math.abs(hscore_n-oldhscore)/(ave_hscore) * 100 > max) {
            max = Math.abs(hscore_n-oldhscore)/(ave_hscore)*100;
          }
		}
		// Check for the sum of a and h scores
		/*double total_h = 0.0;
		for (Map.Entry<Integer, Double> entry: hscores.entrySet()) {
		  double hscr = entry.getValue().doubleValue();
		  total_h += hscr * hscr;
		}
		double total_a = 0.0;
		for (Map.Entry<Integer, Double> entry: ascores.entrySet()) {
		  double ascr = entry.getValue().doubleValue();
		  total_a += ascr * ascr;
		}
		System.out.println("total authority this round is : " + total_a);
		System.out.println("total hub this round is : " + total_h);
		*/
		
		if (iteration_flag) {
          numiterations--;
          if (numiterations <= 0) break;
        } else {
		  //System.out.println(max+" max");
		  if (max <= maxchange) break;
        }
      }
	  //System.out.println(ascores.toString());
	  //System.out.println(hscores.toString());
	  try {
	    File outputf = new File(outputfile);
        FileWriter filewt = new FileWriter(outputf);
        BufferedWriter bufwt = new BufferedWriter(filewt);
      
        // sort ascore hashmap
        Map<Integer, Double> ascores_treemap = new TreeMap<Integer, Double>(ascores);

        for (Map.Entry<Integer, Double> entry: ascores_treemap.entrySet()) {
          int docid = entry.getKey().intValue();
          double ascore = entry.getValue().doubleValue();
		  double hscore = hscores.get(docid);
          bufwt.write(docid+","+hscore+","+ascore);
          bufwt.newLine();
        }
        bufwt.flush();
        bufwt.close();
	  } catch (IOException ioe){
        System.err.println(ioe.toString());
      }
  }
  
  public static void SeedSet (String query, String indexf, int h) {
	HashMap<String, HashSet<String>> docmap = new HashMap<String, HashSet<String>>();
	ArrayList<Integer> doclist = new ArrayList<Integer>();
	String[] q = query.split(" ");
	int numwords = q.length;
	File indexfile = new File(indexf);
	try {
      Scanner scan = new Scanner(indexfile);
    	
      while (scan.hasNext()) {
	    String s = scan.nextLine();
	    if (s.length() == 0) {continue;}
	    String[] indexstring = s.split(" ");
	    for (int i = 0; i < numwords; i++) {
	      if (q[i].toLowerCase().equals(indexstring[0].toLowerCase())) {
		    if (docmap.containsKey(indexstring[1])) {
			  docmap.get(indexstring[1]).add(q[i].toLowerCase());
		    } else {
		      HashSet<String> hs = new HashSet<String>();
			  hs.add(q[i].toLowerCase());
		      docmap.put(indexstring[1], hs);
		    }
		  }
	    }
      }
	  for (Map.Entry<String, HashSet<String>> entry: docmap.entrySet()) {
	    HashSet<String> hs = entry.getValue();
	    if (hs.size() == numwords) {
	      int docid = Integer.parseInt(entry.getKey());
		  doclist.add(docid);
	    }
	  }
	  Collections.sort(doclist);
	  if (doclist.size() < h) { 
	    h = doclist.size();
	  }	  
	  for (int i = 0; i < h; i++) {
	    int doci = doclist.get(i);
	    seeds.add(doci);
		base.add(doci);
	  }
	} catch(FileNotFoundException fnfe) { 
      System.out.println(fnfe.getMessage());
    }
  }
  
  public static void BaseSet (String hitf) {
	File hitfile = new File(hitf);
	HashMap<Integer, HashSet<Integer>> seed_connections = new HashMap<Integer, HashSet<Integer>>();
    try {     
      Scanner scan = new Scanner(hitfile);
      String [] verticesString = scan.nextLine().split(" ");
      int vnum = Integer.parseInt(verticesString[1]);
      for (int i = 0; i < vnum; i++) {
        String s = scan.nextLine();
        if (s.length() == 0) {i--; continue;}
        String [] vertexString = s.split(" ");
        int docid = Integer.parseInt(vertexString[0]);
        if (docs.containsKey(docid)) {
          System.err.println("ERROR! "+docid+" already exists.");
          System.exit(1);
        }
		docs.put(docid, vertexString[1]);
      }
      String [] arcsString = scan.nextLine().split(" ");
      int anum = Integer.parseInt(arcsString[1]);
      for (int i = 0; i < anum; i++) {
        String s= scan.nextLine();
        if (s.length() == 0) {i--; continue;}
        String [] arcString = s.split(" ");
        int doc1 = Integer.parseInt(arcString[0]);
		int doc2 = Integer.parseInt(arcString[1]);
		if (seeds.contains(doc1)) {
			if (seed_connections.containsKey(doc1)) {
				seed_connections.get(doc1).add(doc2);
			} else {
				HashSet<Integer> hs = new HashSet<Integer>();
				hs.add(doc2);
				seed_connections.put(doc1, hs);
			}
		} 
		if (seeds.contains(doc2)) {
			if (seed_connections.containsKey(doc2)) {
				seed_connections.get(doc2).add(doc1);
			} else {
				HashSet<Integer> hs = new HashSet<Integer>();
				hs.add(doc1);
				seed_connections.put(doc2, hs);
			}
		}
		/*if (seeds.contains(doc1)) {
		  base.add(doc2);
		} 
		if (seeds.contains(doc2)) {
		  base.add(doc1);
		}*/
      }
	  //add first 50 connection pages of a seed page to the base set
	  for (Map.Entry<Integer, HashSet<Integer>> entry: seed_connections.entrySet()) {
	    HashSet<Integer> hs = entry.getValue();
	    if (hs != null) {
		  //System.out.println(hs.size());
		  if (hs.size() > 50) {
		    ArrayList<Integer> connections = new ArrayList<Integer>();
		    for (Integer doc : hs) {
	          connections.add(doc);
		    }
		    Collections.sort(connections);
			for (int i=0; i < 50; i++) {
				base.add(connections.get(i));
			}
		  } else {
			for (Integer doc : hs) {
	          base.add(doc);
		    }
		  }
	    }
	  }
	  //go over the list again to find all the linkins to and linkouts from a page
	  Scanner scan2 = new Scanner(hitfile);
      String [] vString = scan2.nextLine().split(" ");
      int v_num = Integer.parseInt(vString[1]);
      for (int i = 0; i < v_num; i++) {
        String s = scan2.nextLine();
        if (s.length() == 0) {i--; continue;}
      }
      String [] aString = scan2.nextLine().split(" ");
      int a_num = Integer.parseInt(aString[1]);
      for (int i = 0; i < a_num; i++) {
        String s= scan2.nextLine();
        if (s.length() == 0) {i--; continue;}
        String [] arcString = s.split(" ");
        int doc1 = Integer.parseInt(arcString[0]);
		int doc2 = Integer.parseInt(arcString[1]);
		if (base.contains(doc1) && base.contains(doc2)) {
		  if (linkout.containsKey(doc1)) {
			HashSet<Integer> hs = linkout.get(doc1);
			hs.add(doc2);
			linkout.put(doc1, hs);
		  } else {
			HashSet<Integer> hs = new HashSet<Integer>();
			hs.add(doc2);
			linkout.put(doc1, hs);
		  }
		  if (linkin.containsKey(doc2)) {
			HashSet<Integer> hs = linkin.get(doc2);
			hs.add(doc1);
			linkin.put(doc2, hs);		    
		  } else {
			HashSet<Integer> hs = new HashSet<Integer>();
			hs.add(doc1);
			linkin.put(doc2, hs);		  
		  }
		} 

      }
	} catch(FileNotFoundException fnfe) { 
      System.out.println(fnfe.getMessage());
    }
  }
}
