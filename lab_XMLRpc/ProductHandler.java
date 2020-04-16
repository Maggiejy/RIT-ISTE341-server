public class ProductHandler {
    String[][] products;
    double price;
    public String[] getMethods() {
        String[] temp = {"double getPrice(String product)",
                         "String[] getProducts()",
                         "String getCheapest()",
                         "String getCostliest()"};
        return temp;
    }
    
    
    public double getPrice(String product){
        for(int i=0;i<4;i++){
          if(products[i][0] == product){
            price = Double.parseDouble(products[i][1]);
          } else {
            price = 0; 
          }
        }
        return price;
    
    }
    
    public String[][] getProducts(){
        products = new String[][]{
                    {"Apples", "3.99"},
                    {"Peaches","4.05"},
                    {"Pumpkin","13.99"},
                    {"Pie","8.00"}};
        return products;
                              
    }
    
    public String getCheapest() {
        String cheapest = "";
        for(int j=0;j<4;j++){
          double smallest = Double.parseDouble(products[j][1]);
          if((Double.parseDouble(products[j][1])) <= smallest){
            smallest = Double.parseDouble(products[j][1]);
            cheapest = products[j][0];
          } 
        }
        return cheapest;
    }
    
    public String getCostliest() {
        String costliest = "";
        for(int j=0;j<4;j++){
          double largest = Double.parseDouble(products[j][1]);
          if((Double.parseDouble(products[j][1])) >= largest){
            largest = Double.parseDouble(products[j][1]);
            costliest = products[j][0];
          } 
        }
        return costliest;
    } 


}