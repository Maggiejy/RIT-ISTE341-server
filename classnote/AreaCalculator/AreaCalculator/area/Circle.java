package area;

public class Circle{
   public double radius;
   public int id;
   public double area;
   
   public Circle(double r){
      id = 1; //could from db
      radius = r;
      area = Math.PI*radius*radius; 
   }
   
   public Circle(){
      //need for deserializing
      
   }

}