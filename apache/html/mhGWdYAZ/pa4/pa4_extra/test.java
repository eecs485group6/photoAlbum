import edu.umich.eecs485.pa4.utils.QueryHit;
import edu.umich.eecs485.pa4.IndexServer;
import java.util.List;
import java.io.*;
import java.util.*;

public class test {
  public static void main(String[] args) {
    try {
    File f = new File("index.txt");
    IndexServer IS = new IndexServer(8000,f);
    System.out.println("begin!");
    List<QueryHit> qh = IS.processQuery("dogs");
    System.out.println("good "+qh.size());
    for (QueryHit q: qh) {
      System.out.println(q.getIdentifier()+ "  "+ q.getScore());
    }
    }
    catch (Exception e) {
      System.err.println("wa");
    }
  }
}
