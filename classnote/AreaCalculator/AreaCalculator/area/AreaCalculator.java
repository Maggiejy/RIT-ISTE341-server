package area;
//if from differenct package we need to import area.*;
import javax.ws.rs.core.*;
import javax.ws.rs.*; //only import the class in that folder nor the subfolders

@Path("AreaCalculator")
public class AreaCalculator{

   @Context
   UriInfo uriInfo;
   
   @Path("Hello")
   @GET
   @Produces("application/json")
   public Response helloWorld(){
      return Response.ok("{\"response\":\"Hello\"}").build();
   }
   
   @Path("Hello/{name}")
   @GET
   @Produces("application/json")
   public Response helloName(@PathParam("name") String name){
      return Response.ok("{\"hi\":\""+name+"\"}").build();

   }
   
   @Path("Rectangle")
   @GET
   @Produces("application/xml")
   public Response calcRectangleAreaXML(
      @DefaultValue("1") @QueryParam("width") double width,
      @DefaultValue("1") @QueryParam("length") double length
   ) {
      return Response.ok("<area>"+width*length+"</area>").build();
   }
   
   @Path("Rectangle")
   @GET
   @Produces("application/json")
   public Response calcRectangleAreaJSON(
      @DefaultValue("1") @QueryParam("width") double width,
      @DefaultValue("1") @QueryParam("length") double length
   ) {
      return Response.ok("{\"area\":\""+width*length+"\"}").build();
   }
   
   @Path("Circle")
   @GET
   @Produces("application/json")
   public Response calcCircleArea(
      @QueryParam("radius") double radius
   ) {
      return Response.ok("{\"area\":\""+Math.PI*radius*radius+"\"}").build();
   }
   
   @Path("Circle")
   @POST
   @Produces("application/json")
   public Response createCircle(
      @FormParam("radius") double r
   ) {
      //create object in insert into db but we're assuming an id of 1 is being returned in this case
      //and we have a matching GET route - which we don't in this case
      Circle c = new Circle(r);
      Link link = Link.fromUri(uriInfo.getPath()+"/"+c.id).rel("self").build();
      return Response.status(Response.Status.CREATED).location(link.getUri()).build();
   }
   
   @Path("Circle/{id}")
   @PUT
   @Consumes("application/json")
   public Response updateCircle(
      @PathParam("id") int id, 
      Circle circleIn
   ) {
      //the body comes as a string, so you would normally
      //need to parse it (and change Circle to String above)
      //but in the server parse it for us
      //normally check to see if exists in db
      boolean exists = true;
      if (!exists){
         return Response.status(Response.Status.NOT_FOUND).build();
      }
      //do some validation
      if (circleIn.radius == 0){
         return Response.status(Response.Status.BAD_REQUEST)
            .entity("No content to upadate").build();
      }
      //update the db and return on "OK" or a link above
      //like in insert
      return Response.ok("Circle Updated").build();
      
   }
   
   @Path("Circle/{id}")
   @DELETE
   public Response deleteCircle(
      @PathParam("id") int id
   ) {
      boolean exists = true;
      if (!exists){
         return Response.status(Response.Status.NOT_FOUND).build();
      }
      //delete from db
      return Response.ok("Circle Deleted").build();
   
   }

   
   


}
