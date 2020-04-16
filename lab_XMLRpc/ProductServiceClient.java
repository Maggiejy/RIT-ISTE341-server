import org.apache.xmlrpc.*;
import java.util.Vector;
import java.io.IOException;
import java.net.MalformedURLException;

public class ProductServiceClient {
   public static void main(String[] args) {
      try {
         XmlRpcClient client = new XmlRpcClient("http://localhost:8100/");
//         XmlRpcClient client = new XmlRpcClient("http://kelvin.ist.rit.edu/~bdfvks/341/xmlrpcdemo/productServer.php");
         Vector params = new Vector();
         
         Object result = client.execute("product.getMethods",params);
         System.out.println("Methods: " + result.toString());
         
         result = client.execute("product.getCheapest",params);
         System.out.println("Cheapest: " +result.toString());

         result = client.execute("product.getCostliest",params);
         System.out.println("Costliest: " +result.toString());

          result = client.execute("product.getProducts",params);
          System.out.println("Products: " +result.toString());
 
         params.addElement("Apples");
         result = client.execute("product.getPrice",params);
         System.out.println("Price for Apples: " +result.toString());


      
         
      } catch(XmlRpcException e) {
         System.out.println("XmlRpc error: " + e.getMessage());
      } catch(MalformedURLException e) {
         System.out.println("BadURL error: " + e.getMessage());
      } catch(IOException e) {
         System.out.println("IO error: " + e.getMessage());
      }


   }
}