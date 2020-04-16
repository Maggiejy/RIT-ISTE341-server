using System;
namespace RestServiceLab.Models
{
    public class Product
    {
        public string Name { get; set; }
        public double Price { get; set; }

        public override string ToString()
        {
            return  "Name: " + Name + "Price" + Price;
        }
        public Product()
        {
            

        }
    }
}
