import com.google.gson.*;
import java.util.*;
import java.net.*;
import org.apache.commons.io.*;
import java.nio.charset.StandardCharsets;

public class JavaJsonWithGson {

   public static void main(String[] args) {
      
      //GSON using object binding
      List<Park> dataset = new ArrayList<Park>();
      Park p = new Park("Letchworth","NY","Grand Canyon of the East");
      dataset.add(p);
      p = new Park("Watkins Glen","NY","Gorgeous");
      dataset.add(p);
      
      GsonBuilder builder = new GsonBuilder();
      Gson gson = builder.create();
      System.out.println(gson.toJson(dataset));
      
      try {
      URL url = new URL("http://www.ist.rit.edu/~bdf/454/nationalParks?type=json");
      Parks parks = gson.fromJson(IOUtils.toString(url.openStream(),null), Parks.class);  
      for(Park park : parks.parks) {
         System.out.println(park.parkName);
      }
   } catch(Exception e) {
      System.out.println(e.getMessage());
   }
   
   }

}