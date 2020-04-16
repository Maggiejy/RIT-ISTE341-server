using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using RestServiceLab.Models;

// For more information on enabling Web API for empty projects, visit https://go.microsoft.com/fwlink/?LinkID=397860

namespace RestServiceLab.Controllers
{
    [Route("api/[controller]")]
    public class ServicesController : Controller
    {
        private List<Product> products = new List<Product>();
        public ServicesController()
        {
            CreateProducts();
        }
        private void CreateProducts()
        {
            
            products.Add(new Product() { Name = "Apples", Price = 3.99 });
            products.Add(new Product() { Name = "Peaches", Price = 4.05 });
            products.Add(new Product() { Name = "Pumpkin", Price = 13.99 });
            products.Add(new Product() { Name = "Pie", Price = 8.00 });
        }
        

        // GET: api/Services/Products
        [Route("Products")]
        [HttpGet]
        public IEnumerable<Product> GetAll()
        {
            return this.products;
        }

        [HttpGet]
        public IEnumerable<Product> GetOrdered()
        {
            return products.OrderBy(x => x.Price);
        }

        [Route("Products/{name}")]
        [HttpGet]
        public IActionResult GetPriceByName(string name)
        {
            Product product = products.Find(x => x.Name == name);
            if (product == null)
            {
                return NotFound();
            }
            return new ObjectResult(product);
        }

        [Route("Products/Cheapest")]
        [HttpGet]
        public IActionResult GetCheapest()
        {
            Product product = this.GetOrdered().First(x => x.Price > 0);
            if (product == null)
            {
                return NotFound();
            }
            return new ObjectResult(product);
        }

        [Route("Products/Costliest")]
        [HttpGet]
        public IActionResult GetCostliest()
        {
            Product product = this.GetOrdered().Last(x => x.Price > 0);
            if (product == null)
            {
                return NotFound();
            }
            return new ObjectResult(product);
        }
    }
}
