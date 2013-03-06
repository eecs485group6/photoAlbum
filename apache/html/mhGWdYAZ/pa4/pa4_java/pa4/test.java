import edu.umich.eecs485.pa4.utils.QueryHit;
import edu.umich.eecs485.pa4.IndexServer;
import java.util.List;
import java.io.*;
import java.util.*;

public class test {
  public static void main(String[] args) {
    try {
    File f = new File("test.txt");
    IndexServer IS = new IndexServer(9000,f);
    List<QueryHit> qh = IS.processQuery("big dogs");
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
