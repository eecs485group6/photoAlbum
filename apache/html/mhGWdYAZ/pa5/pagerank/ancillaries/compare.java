import java.util.*;
import java.io.*;
public class compare {
  public static void main (String[] args) {
    File f1 = new File("pr.output");
    File f2 = new File("pagerankSmallOut");
    try {
      Scanner a = new Scanner(f1);
      Scanner b = new Scanner(f2);
      while(a.hasNext() && b.hasNext()){
        String [] pr_Strings = a.nextLine().split(" ");
        String [] ps_Strings = b.nextLine().split(" ");
        if (!pr_Strings[0].equals(ps_Strings[0]))  
          System.out.println("mismatch:"+pr_Strings[0]+" "+ps_Strings[0]);
      }
    }
    catch (Exception e) {
      System.err.println("cannot open");
    }
  }
}
