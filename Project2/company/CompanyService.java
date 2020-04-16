package company;

import javax.ws.rs.core.*;
import javax.ws.rs.*;
import companydata.*;
import java.text.SimpleDateFormat;  
import java.time.LocalDate;
import java.util.List;
import java.util.Calendar;
import java.sql.Date;
import java.sql.Timestamp;
import java.net.*;
import java.io.*;
import java.util.*; 
import javax.json.*;
import javax.json.stream.*;
import javax.json.stream.JsonParser.*;

@Path("CompanyService")
public class CompanyService{

   @Context
   UriInfo uriInfo;
   DataLayer dl = null;    
   
   @Path("/company")
   @DELETE
   @Produces("application/json")
   public Response deleteCompany(@QueryParam("company") String company) {
      try {
         dl = new DataLayer(company);
         int row = dl.deleteCompany(company);
         if (row == 0){
            return Response.status(Response.Status.NOT_FOUND).build();
         } else {
            return Response.ok("{\"success\":\""+company+"'s information deleted\"}\n").build();
         }         
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }      
   }
   
   @Path("/department")
   @GET
   @Produces("application/json")
   public Response getDepartment(
      @QueryParam("company") String company,
      @QueryParam("dept_id") int id
   ){
      try {
         dl = new DataLayer(company);
         Department d = dl.getDepartment(company,id);   	  
         return Response.ok("{\n\"dept_id\":"+d.getId()+",\n"+
                            "\"company\":\""+d.getCompany()+"\",\n"+
                            "\"dept_name\":\""+d.getDeptName()+"\",\n"+
                            "\"dept_no\":\""+d.getDeptNo()+"\",\n"+
                            "\"location\":\""+d.getLocation()+"\"\n}").build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }       
   }
   
   @Path("/departments")
   @GET
   @Produces("application/json")
   public Response getAllDepartment(@QueryParam("company") String company){
      try {
         dl = new DataLayer(company);
         List<Department> departments = dl.getAllDepartment(company);
         List<String> d_list = new ArrayList<String>();
         for(Department d : departments ){    	  
            d_list.add("\n{\n\"dept_id\":"+d.getId()+",\n"+
                            "\"company\":\""+d.getCompany()+"\",\n"+
                            "\"dept_name\":\""+d.getDeptName()+"\",\n"+
                            "\"dept_no\":\""+d.getDeptNo()+"\",\n"+
                            "\"location\":\""+d.getLocation()+"\"\n}");
         }
         return Response.ok(d_list.toString()).build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }    
   }
   
   @Path("/department")
   @PUT
   @Consumes("application/json")
   @Produces("application/json")
   public Response updateDepartment(String inJSON) {
      try {
         JsonReader rdr = Json.createReader(new StringReader(inJSON));
         JsonObject obj = rdr.readObject();
         int id = obj.getInt("dept_id");
         String company = obj.getString("company");
         String dept_name = obj.getString("dept_name");
         String dept_no = obj.getString("dept_no");
         String location = obj.getString("location");
         dl = new DataLayer(company);
         List<Department> departments = dl.getAllDepartment(company);
         List<Integer> deptId_list = new ArrayList<Integer>();
         for(Department dept : departments ){
            deptId_list.add(dept.getId());
            if(dept.getDeptNo() == dept_no && dept.getId() != id){
               dept_no = dept_no.concat("_1");
            }
         }
         if(!deptId_list.contains(id)){
            return Response.ok("{\"error\":\"There is no such dept_id.\"}\n").build();
         }
         Department dept = dl.getDepartment(company,id);         
         dept.setDeptName(dept_name);
         dept.setDeptNo(dept_no);
         dept.setLocation(location);
   	   Department d = dl.updateDepartment(dept);
         return Response.ok("{\n\"sucess\":{\n\"dept_id\":"+d.getId()+",\n"+
                            "\"company\":\""+d.getCompany()+"\",\n"+
                            "\"dept_name\":\""+d.getDeptName()+"\",\n"+
                            "\"dept_no\":\""+d.getDeptNo()+"\",\n"+
                            "\"location\":\""+d.getLocation()+"\"\n}\n}").build();
                            
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }             
   }
   
   @Path("/department")
   @POST
   @Produces("application/json")
   public Response insertDepartment(
      @FormParam("company") String company,
      @FormParam("dept_name") String dept_name,
      @FormParam("dept_no") String dept_no,
      @FormParam("location") String location     
   ) {
      try {
         dl = new DataLayer(company);           
         List<Department> departments = dl.getAllDepartment(company);
         List<String> deptNo_list = new ArrayList<String>();
         for(Department dept : departments){
            deptNo_list.add(dept.getDeptNo());  
         }
         if(deptNo_list.contains(dept_no)){
               dept_no = dept_no.concat("_1");
         }
         Department d = new Department(company,dept_name,dept_no,location);
         d = dl.insertDepartment(d);
         if (d.getId()>0){
            return Response.ok("{\n\"sucess\":{\n\"dept_id\":"+d.getId()+",\n"+
                            "\"company\":\""+d.getCompany()+"\",\n"+
                            "\"dept_name\":\""+d.getDeptName()+"\",\n"+
                            "\"dept_no\":\""+d.getDeptNo()+"\",\n"+
                            "\"location\":\""+d.getLocation()+"\"\n}\n}").build();

         } else {
            return Response.ok("{\"error\":\"Not inserted.\"}\n").build();
         }                        
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }   
   }
   
   @Path("/department")
   @DELETE
   @Produces("application/json")
   public Response deleteDepartment(
      @QueryParam("company") String company,
      @QueryParam("dept_id") int id
   ) {
      try {
         dl = new DataLayer(company);
         int row = dl.deleteDepartment(company,id);
         if (row <= 0){
            return Response.status(Response.Status.NOT_FOUND).build();
         } else {
            return Response.ok("{\"success\":\"Department "+id+" from "+company+" deleted\"}\n").build();
         }
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }      
   }
   
   @Path("/employee")
   @GET
   @Produces("application/json")
   public Response getEmployee(
      @QueryParam("company") String company,
      @QueryParam("emp_id") int id
   ){
      try {
         dl = new DataLayer(company);
         Employee e = dl.getEmployee(id);   	  
         return Response.ok("{\n\"emp_id\":"+e.getId()+",\n"+
                            "\"emp_name\":\""+e.getEmpName()+"\",\n"+
                            "\"emp_no\":\""+e.getEmpNo()+"\",\n"+
                            "\"hire_date\":\""+e.getHireDate()+"\",\n"+
                            "\"job\":\""+e.getJob()+"\",\n"+
                            "\"salary\":\""+e.getSalary()+"\",\n"+
                            "\"dept_id\":\""+e.getDeptId()+"\",\n"+
                            "\"mng_id\":\""+e.getMngId()+"\"\n}").build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }       
   }
   
   @Path("/employees")
   @GET
   @Produces("application/json")
   public Response getAllEmployee(@QueryParam("company") String company){
      try {
         dl = new DataLayer(company);
         List<Employee> employees = dl.getAllEmployee(company);
         List<String> e_list = new ArrayList<String>();
         for(Employee e : employees ){    	  
            e_list.add("{\n\"emp_id\":"+e.getId()+",\n"+
                            "\"emp_name\":\""+e.getEmpName()+"\",\n"+
                            "\"emp_no\":\""+e.getEmpNo()+"\",\n"+
                            "\"hire_date\":\""+e.getHireDate()+"\",\n"+
                            "\"job\":\""+e.getJob()+"\",\n"+
                            "\"salary\":\""+e.getSalary()+"\",\n"+
                            "\"dept_id\":\""+e.getDeptId()+"\",\n"+
                            "\"mng_id\":\""+e.getMngId()+"\"\n}");
         }
         return Response.ok(e_list.toString()).build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }    
   }
   
   @Path("/employee")
   @POST
   @Produces("application/json")
   public Response insertEmployee(
      @FormParam("company") String company,
      @FormParam("emp_name") String emp_name,
      @FormParam("emp_no") String emp_no,
      @FormParam("hire_date") String hire_date,
      @FormParam("job") String job,
      @FormParam("salary") Double salary,
      @FormParam("dept_id") int dept_id,
      @DefaultValue("0")@FormParam("mng_id") int mng_id) {
      try {
         //check company string
         if (!company.equals("yj3010") ){
              return Response.ok("{\"error\":\"no such company. Company must be the RIT username.\"}\n").build();
         }
         dl = new DataLayer(company); 
         //validation
         List<Department> departments = dl.getAllDepartment(company);
         List<Employee> employees = dl.getAllEmployee(company);
         
         List<Integer> deptId_list = new ArrayList<Integer>();
         List<Integer> empId_list = new ArrayList<Integer>();
         List<String> empNo_list = new ArrayList<String>();
         //check if dept_id exist
         for(Department dept : departments ){ 
            deptId_list.add(dept.getId()); 
         }
         if(!deptId_list.contains(dept_id)){
           return Response.ok("{\"error\":\"There is no such dept_id.\"}\n").build();
         } 
         
         //check if mng_id exist and if emp_no is unique
         for(Employee emp : employees ){
            empId_list.add(emp.getId());
            empNo_list.add(emp.getEmpNo());  
         }
         if(mng_id != 0 && !empId_list.contains(mng_id)){
            return Response.ok("{\"error\":\"There is no such emp_id.\"}\n").build();
         }
         if(empNo_list.contains(emp_no)){
            emp_no = emp_no.concat("_1");
         }
         //check if the hire_date valid
         java.util.Date hire_date_util = new SimpleDateFormat("yyyy-MM-dd").parse(hire_date);         
         LocalDate now = LocalDate.now();
         java.util.Date date = java.sql.Date.valueOf(now);
         
         Calendar c = Calendar.getInstance();
         c.setTime(hire_date_util);
         
         int dayOfWeek = c.get(Calendar.DAY_OF_WEEK);
         if (date.compareTo(hire_date_util) > 0 && (dayOfWeek == 1 || dayOfWeek ==7)){
            return Response.ok("{\"error\":\"Not valid hire_date.It must be a valid date equal to the current date or earlier. The hire_date cannot be Saturday or Sunday.\"}\n").build();
         }
         java.sql.Date hire_date_sql = new java.sql.Date(hire_date_util.getTime());
         //insert the new employee
         Employee e = new Employee(emp_name,emp_no,hire_date_sql,job,salary,dept_id,mng_id);
         e = dl.insertEmployee(e);
         return Response.ok("{\n\"success\":{\n\"emp_id\":"+e.getId()+",\n"+
                            "\"emp_name\":\""+e.getEmpName()+"\",\n"+
                            "\"emp_no\":\""+e.getEmpNo()+"\",\n"+
                            "\"hire_date\":\""+e.getHireDate()+"\",\n"+
                            "\"job\":\""+e.getJob()+"\",\n"+
                            "\"salary\":\""+e.getSalary()+"\",\n"+
                            "\"dept_id\":\""+e.getDeptId()+"\",\n"+
                            "\"mng_id\":\""+e.getMngId()+"\"\n}\n}").build();
                            
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }   
   }
   
   @Path("/employee")
   @PUT
   //@Consumes("application/json")
   @Produces("application/json")
   public Response updateEmployee(String inJSON) {
      try {
         JsonReader rdr = Json.createReader(new StringReader(inJSON));
         JsonObject obj = rdr.readObject();
         //get strings and integers from json object
         String company = obj.getString("company");
         int emp_id = obj.getInt("emp_id");
         String emp_name = obj.getString("emp_name");
         String emp_no = obj.getString("emp_no");
         String hire_date_string = obj.getString("hire_date");
         String job = obj.getString("job");
         double salary = Double.valueOf(obj.getInt("salary"));
         int dept_id = obj.getInt("dept_id");
         int mng_id = obj.getInt("mng_id");
         
         //check company string
         if (!company.equals("yj3010") ){
              return Response.ok("{\"error\":\"no such company. Company must be the RIT username.\"}\n").build();
         }
         dl = new DataLayer(company); 
         //validation
         List<Department> departments = dl.getAllDepartment(company);
         List<Employee> employees = dl.getAllEmployee(company);
         
         List<Integer> deptId_list = new ArrayList<Integer>();
         List<Integer> empId_list = new ArrayList<Integer>();
         //check if dept_id exist
         for(Department dept : departments ){ 
            deptId_list.add(dept.getId()); 
         }
         if(!deptId_list.contains(dept_id)){
           return Response.ok("{\"error\":\"There is no such dept_id.\"}\n").build();
         } 
         
         //check if mng_id exist, if emp_no is unique and if emp_id exists
         for(Employee emp : employees ){
            empId_list.add(emp.getId());
            if(emp.getId() != emp_id && emp.getEmpNo() == emp_no){
               emp_no = emp_no.concat("_1");
            }
         }  
         if(mng_id != 0 && !empId_list.contains(mng_id)){
            return Response.ok("{\"error\":\"There is no such emp_id as manager.\"}\n").build();
         }
         if(!empId_list.contains(emp_id)){
            return Response.ok("{\"error\":\"There is no such emp_id.\"}\n").build();
         }
         //check if the hire_date valid
          java.util.Date hire_date_util = new SimpleDateFormat("yyyy-MM-dd").parse(hire_date_string);         
         LocalDate now = LocalDate.now();
         java.util.Date date = java.sql.Date.valueOf(now);
         
         Calendar c = Calendar.getInstance();
         c.setTime(hire_date_util);
         
         int dayOfWeek = c.get(Calendar.DAY_OF_WEEK);
         if (date.compareTo(hire_date_util) > 0 && (dayOfWeek == 1 || dayOfWeek ==7)){
            return Response.ok("{\"error\":\"Not valid hire_date.It must be a valid date equal to the current date or earlier. The hire_date cannot be Saturday or Sunday.\"}\n").build();
         }
          java.sql.Date hire_date_sql = new java.sql.Date(hire_date_util.getTime());
         //update the employee
         Employee e = dl.getEmployee(emp_id);
         e.setEmpName(emp_name);
         e.setEmpNo(emp_no);
         e.setHireDate(hire_date_sql);
         e.setJob(job);
         e.setSalary(salary);
         e.setDeptId(dept_id);
         e.setMngId(mng_id);
         e = dl.updateEmployee(e);
         return Response.ok("{\n\"success\":{\n\"emp_id\":"+e.getId()+",\n"+
                            "\"emp_name\":\""+e.getEmpName()+"\",\n"+
                            "\"emp_no\":\""+e.getEmpNo()+"\",\n"+
                            "\"hire_date\":\""+e.getHireDate()+"\",\n"+
                            "\"job\":\""+e.getJob()+"\",\n"+
                            "\"salary\":\""+e.getSalary()+"\",\n"+
                            "\"dept_id\":\""+e.getDeptId()+"\",\n"+
                            "\"mng_id\":\""+e.getMngId()+"\"\n}\n}").build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }             
   }

   
   @Path("/employee")
   @DELETE
   @Produces("application/json")
   public Response deleteEmployee(
      @QueryParam("company") String company,
      @QueryParam("emp_id") int id
   ) {
      try {
         dl = new DataLayer(company);
         int row = dl.deleteEmployee(id);
         if (row <= 0){
            return Response.status(Response.Status.NOT_FOUND).build();
         } else {
            return Response.ok("{\"success\":\"Employee "+id+" deleted.\"}\n").build();
         }
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }      
   }
   
   @Path("/timecard")
   @GET
   @Produces("application/json")
   public Response getTimecard(
      @QueryParam("company") String company,
      @QueryParam("timecard_id") int id
   ){
      try {
         dl = new DataLayer(company);
         Timecard t = dl.getTimecard(id);   	  
         return Response.ok("{\n\"timecard\":{\n\"timecard_id\":"+t.getId()+",\n"+
                            "\"start_time\":\""+t.getStartTime()+"\",\n"+
                            "\"end_time\":\""+t.getEndTime()+"\",\n"+
                            "\"emp_id\":\""+t.getEmpId()+"\"\n}\n}").build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }       
   }
   
   @Path("/timecards")
   @GET
   @Produces("application/json")
   public Response getAllTimecard(
      @QueryParam("company") String company,
      @QueryParam("emp_id") int id
   ){
      try {
         dl = new DataLayer(company);
         List<Timecard> timecards = dl.getAllTimecard(id);
         List<String> t_list = new ArrayList<String>();
         for(Timecard t : timecards ){    	  
            t_list.add("{\n\"timecard_id\":"+t.getId()+",\n"+
                            "\"start_time\":\""+t.getStartTime()+"\",\n"+
                            "\"end_time\":\""+t.getEndTime()+"\",\n"+
                            "\"emp_id\":\""+t.getEmpId()+"\"\n}");
         }
         return Response.ok(t_list.toString()).build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }    
   }
   
   @Path("/timecard")
   @DELETE
   @Produces("application/json")
   public Response deleteTimecard(
      @QueryParam("company") String company,
      @QueryParam("timecard_id") int id
   ) {
      try {
         dl = new DataLayer(company);
         int row = dl.deleteTimecard(id);
         if (row <= 0){
            return Response.status(Response.Status.NOT_FOUND).build();
         } else {
            return Response.ok("{\"success\":\"Timecard "+id+" deleted.\"}\n").build();
         }
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }      
   }
   
   @Path("/timecard")
   @POST
   @Produces("application/json")
   public Response insertTimecard(
      @FormParam("company") String company,
      @FormParam("start_time") String start_time_string,
      @FormParam("end_time") String end_time_string,
      @FormParam("emp_id") int emp_id) {
      try {
         //check company string
         if (!company.equals("yj3010") ){
              return Response.ok("{\"error\":\"no such company. Company must be the RIT username.\"}\n").build();
         }
         //data layer
         dl = new DataLayer(company);
         //check if employee exist
         List<Employee> employees = dl.getAllEmployee(company);
         List<Integer> empId_list = new ArrayList<Integer>();
         for(Employee emp : employees ){    	  
            empId_list.add(emp.getId());
         }
         if (!empId_list.contains(emp_id)){
            return Response.ok("{\"error\":\"no such employee.\"}\n").build();
         }
         //convert string to timestamp
         Timestamp start_time = new Timestamp(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(start_time_string).getTime());
         Timestamp end_time = new Timestamp(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(end_time_string).getTime()); 
         //date validation
         java.util.Date start_date = new java.util.Date(start_time.getTime());
         java.util.Date end_date = new java.util.Date(end_time.getTime());
         
         LocalDate now = LocalDate.now();
         java.util.Date date = java.sql.Date.valueOf(now);
         
         Calendar cStart = Calendar.getInstance();
         Calendar cRange = Calendar.getInstance();
         Calendar cEnd = Calendar.getInstance();
         cStart.setTime(start_time);
         cEnd.setTime(end_time);
         
         cRange.setTime(date);
         cRange.add(Calendar.DATE,-7);
         java.util.Date dateRange = cRange.getTime();
         
         int dayOfWeek_start = cStart.get(Calendar.DAY_OF_WEEK);
         if (date.compareTo(start_time) < 0){
            return Response.ok("{\"error\":\"Not valid start_date: not earlier than today.\"}\n").build();
         } 
         if (dateRange.compareTo(start_date) > 0){
            return Response.ok("{\"error\":\"Not valid start_date: not later than a week of today.\"}\n").build();

         }
         if (dayOfWeek_start == 1 || dayOfWeek_start ==7){
            return Response.ok("{\"error\":\"Not valid start_date:The start_date cannot be Saturday or Sunday.\"}\n").build();
         } 
         if (cStart.HOUR_OF_DAY < 6||cStart.HOUR_OF_DAY > 17){
            return Response.ok("{\"error\":\"The start time cannot earlier than 6:00 and cannot later than 17:00.\"}\n").build();
         }
         long dateDiff = (end_date.getTime() - start_date.getTime())/(24 * 60 * 60 * 1000); 
         if (dateDiff != 0){
            return Response.ok("{\"error\":\"The end date must be on the same day as the start date.\"}\n").build();
         } 
         if( cEnd.HOUR_OF_DAY < 7 ||cEnd.HOUR_OF_DAY > 18){
            return Response.ok("{\"error\":\"The end time must between 7:00 to 18:00.\"}\n").build();
         }
         long timeDiff = (end_date.getTime() - start_date.getTime())/60000;
         if (timeDiff <= 60){
             return Response.ok("{\"error\":\"Not valid end time: End time must be at least 1 hour greater than the start_time\"}\n").build();
         }
         //check if timecard id exist
         List<Timecard> timecards = dl.getAllTimecard(emp_id);
         for(Timecard tm : timecards ){
            Date tdate = new Date(tm.getStartTime().getTime()); 
            long dDiff = (tdate.getTime() - start_date.getTime())/(24 * 60 * 60 * 1000); 
            if(emp_id == tm.getEmpId()  && dDiff == 0){
               return Response.ok("{\"error\":\"Not valid start_date.It must not be on the same day as other start time\"}\n").build();
            }
         }
          
   	   Timecard t = new Timecard(start_time,end_time,emp_id);        
         t = dl.insertTimecard(t);
         return Response.ok("{\n\"success\":{\n\"timecard_id\":"+t.getId()+",\n"+
                            "\"start_time\":\""+t.getStartTime()+"\",\n"+
                            "\"end_time\":\""+t.getEndTime()+"\",\n"+
                            "\"emp_id\":\""+t.getEmpId()+"\"\n}\n}").build();
                            
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }   
   }
   
   @Path("/timecard")
   @PUT
   @Consumes("application/json")
   @Produces("application/json")
   public Response updateTimecard(String inJSON) {
      try {
         JsonReader rdr = Json.createReader(new StringReader(inJSON));
         JsonObject obj = rdr.readObject();
         //get strings and integers from json object
         String company = obj.getString("company");
         int id = obj.getInt("timecard_id");
         String start_time_string = obj.getString("start_time");
         String end_time_string = obj.getString("end_time");
         int emp_id = obj.getInt("emp_id");
         
         //check company string
         if (!company.equals("yj3010") ){
              return Response.ok("{\"error\":\"no such company. Company must be the RIT username.\"}\n").build();
         }
         //data layer
         dl = new DataLayer(company);
         //check if employee exist
         List<Employee> employees = dl.getAllEmployee(company);
         List<Integer> empId_list = new ArrayList<Integer>();
         for(Employee emp : employees ){    	  
            empId_list.add(emp.getId());
         }
         if (!empId_list.contains(emp_id)){
            return Response.ok("{\"error\":\"no such employee.\"}\n").build();
         }
         //convert string to timestamp
         Timestamp start_time = new Timestamp(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(start_time_string).getTime());
         Timestamp end_time = new Timestamp(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(end_time_string).getTime()); 
         //date validation
         java.util.Date start_date = new java.util.Date(start_time.getTime());
         java.util.Date end_date = new java.util.Date(end_time.getTime());
         
         LocalDate now = LocalDate.now();
         java.util.Date date = java.sql.Date.valueOf(now);
         
         Calendar cStart = Calendar.getInstance();
         Calendar cRange = Calendar.getInstance();
         Calendar cEnd = Calendar.getInstance();
         cStart.setTime(start_time);
         cEnd.setTime(end_time);
         
         cRange.setTime(date);
         cRange.add(Calendar.DATE,-7);
         java.util.Date dateRange = cRange.getTime();
         
         int dayOfWeek_start = cStart.get(Calendar.DAY_OF_WEEK);
         if (date.compareTo(start_time) < 0){
            return Response.ok("{\"error\":\"Not valid start_date: not earlier than today.\"}\n").build();
         } 
         if (dateRange.compareTo(start_date) > 0){
            return Response.ok("{\"error\":\"Not valid start_date: not later than a week of today.\"}\n").build();

         }
         if (dayOfWeek_start == 1 || dayOfWeek_start ==7){
            return Response.ok("{\"error\":\"Not valid start_date:The start_date cannot be Saturday or Sunday.\"}\n").build();
         } 
         if (cStart.HOUR_OF_DAY < 6||cStart.HOUR_OF_DAY > 17){
            return Response.ok("{\"error\":\"The start time cannot earlier than 6:00 and cannot later than 17:00.\"}\n").build();
         }
         long dateDiff = (end_date.getTime() - start_date.getTime())/(24 * 60 * 60 * 1000); 
         if (dateDiff != 0){
            return Response.ok("{\"error\":\"The end date must be on the same day as the start date.\"}\n").build();
         } 
         if( cEnd.HOUR_OF_DAY < 7 ||cEnd.HOUR_OF_DAY > 18){
            return Response.ok("{\"error\":\"The end time must between 7:00 to 18:00.\"}\n").build();
         }
         long timeDiff = (end_date.getTime() - start_date.getTime())/60000;
         if (timeDiff <= 60){
             return Response.ok("{\"error\":\"Not valid end time: End time must be at least 1 hour greater than the start_time\"}\n").build();
         }
         //check if timecard id exist
         List<Timecard> timecards = dl.getAllTimecard(emp_id);
         List<Integer> tId_list = new ArrayList<Integer>();
         for(Timecard tm : timecards ){
            tId_list.add(tm.getId());
            Date tdate = new Date(tm.getStartTime().getTime()); 
            long dDiff = (tdate.getTime() - start_date.getTime())/(60 * 60 * 1000); 
            if(emp_id == tm.getEmpId() && dDiff <= 14 && dDiff >= -14 && id != tm.getId()){
               return Response.ok("{\"error\":\"Not valid start_date.It must not be on the same day as other start time\"}\n").build();
            } 
         }
         if(!tId_list.contains(id)){
            return Response.ok("{\"error\":\"No such timecard.\"}\n").build();
         }
         Timecard t = dl.getTimecard(id);
         t.setEmpId(emp_id);
         t.setStartTime(start_time);
         t.setEndTime(end_time);
   	   t = dl.updateTimecard(t);
         return Response.ok("{\n\"success\":{\n\"timecard_id\":"+t.getId()+",\n"+
                            "\"start_time\":\""+t.getStartTime()+"\",\n"+
                            "\"end_time\":\""+t.getEndTime()+"\",\n"+
                            "\"emp_id\":\""+t.getEmpId()+"\"\n}\n}").build();
      } catch (Exception e) {
         return Response.ok("{\"error\":\""+e.getMessage()+"\"}\n").build();
      } finally {
      	 dl.close();
      }             
   }


}
