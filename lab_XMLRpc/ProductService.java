import org.apache.xmlrpc.*;

public class ProductService {
    public static void main(String[] args) {
        WebServer server = new WebServer(8100);
        System.out.println("Service created.");
        server.addHandler("product",new ProductHandler());
        System.out.println("Handler Registered.");
        server.start();
        System.out.println("Service started.");
    }

}