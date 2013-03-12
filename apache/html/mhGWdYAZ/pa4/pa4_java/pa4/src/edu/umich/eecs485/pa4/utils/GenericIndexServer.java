package edu.umich.eecs485.pa4.utils;

import java.io.File;
import java.io.IOException;
import java.io.OutputStream;
import java.io.OutputStream;
import java.io.StringWriter;
import java.net.InetSocketAddress;
import java.net.URI;
import java.net.URLEncoder;
import java.net.URLDecoder;
import java.util.Iterator;
import java.util.List;
import java.util.Set;
import java.util.concurrent.Executors;

import org.json.simple.JSONObject;
import org.json.simple.JSONArray;

import com.sun.net.httpserver.Headers;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpServer;

import edu.umich.eecs485.pa4.utils.QueryHit;
import edu.umich.eecs485.pa4.utils.GenericIndexServer;

/******************************************************
 * <code>GenericIndexServer</code> handles all the network and
 * serialization stuff for the student's IndexServer code.
 ******************************************************/
public abstract class GenericIndexServer {
  protected int port;
  protected File fname;
  
  /**
   * Store members, and do the initServer() call.
   */
  public GenericIndexServer(int port, File fname) {
    this.port = port;
    this.fname = fname;

    // init student's server
    initServer(fname);
  }

  /**
   * Block forever, listening to port.
   */
  public void serve() throws IOException {
    InetSocketAddress addr = new InetSocketAddress(port);
    HttpServer server = HttpServer.create(addr, 0);

    server.createContext("/", new QueryHandler());
    server.setExecutor(Executors.newCachedThreadPool());
    server.start();
    System.out.println("Server is listening on port " + port);
  }

  class QueryHandler implements HttpHandler {
    public void handle(HttpExchange exchange) throws IOException {
      String requestMethod = exchange.getRequestMethod();
      if (requestMethod.equalsIgnoreCase("GET")) {
        Headers responseHeaders = exchange.getResponseHeaders();
        responseHeaders.set("Content-Type", "text/plain");
        exchange.sendResponseHeaders(200, 0);

        OutputStream responseBody = exchange.getResponseBody();
        Headers requestHeaders = exchange.getRequestHeaders();
        URI uri = exchange.getRequestURI();
        String path = uri.getPath();

        //
        // Test to make sure the URL is properly formed,
        // then grab the query string contents
        //
        if ("/search".equals(path)) {
          String queryStr = null;
          String query = uri.getQuery();
          String elements[] = query.split("&");
          for (String elt: elements) {
            String name = elt.split("=")[0];
            String val = URLDecoder.decode(elt.split("=")[1], "UTF-8");
            if ("q".equals(name)) {
              queryStr = val;
            }
          }
          List<QueryHit> hits = processQuery(queryStr);

          //
          // Now we can handle the QueryHit JSON encoding
          //
          JSONArray hitlist = new JSONArray();
          for (QueryHit qh: hits) {
            JSONObject hitObj = new JSONObject();
            hitObj.put("id", qh.getIdentifier());
            hitObj.put("score", qh.getScore());
            hitlist.add(hitObj);
          }
          
          JSONObject resultObj = new JSONObject();
          resultObj.put("hits", hitlist);

          //
          // Emit the text
          //
          StringWriter out = new StringWriter();
          resultObj.writeJSONString(out);
          responseBody.write((out.toString()).getBytes());
        }
        responseBody.close();
      }
    }
  }
  

  /**
   * Jetty callback
   */
  public void get() {
    //processQuery(req.get("query"));
  }

  /**
   * initServer(File fname) and processQuery(String query)
   * are abstract functions the student fills in.
   */
  public abstract void initServer(File fname);
  public abstract List<QueryHit> processQuery(String query);
}
