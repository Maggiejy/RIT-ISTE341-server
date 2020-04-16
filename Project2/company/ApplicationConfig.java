package company;

import java.util.Set;
import javax.ws.rs.core.Application;

@javax.ws.rs.ApplicationPath("resources")
//name_of_the_domain/project_name/ApplicationPath
public class ApplicationConfig extends Application{
   @Override 
   public Set<Class<?>> getClasses(){
      return getRestResourceClasses();
   }
   
   private Set<Class<?>> getRestResourceClasses(){
      Set<Class<?>> resources = new java.util.HashSet<Class<?>>();
      resources.add(company.CompanyService.class);
      return resources;
   }

}