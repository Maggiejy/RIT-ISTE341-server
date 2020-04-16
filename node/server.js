var express = require("express");
var moment = require("moment");
var vali = require("./business.js")
var app = express();

app.use(express.json());
var urlencodedParser = app.use(express.urlencoded({ extended: false }));

var DataLayer = require("./companydata/index.js");
var dl = new DataLayer("yj3010");
var response = "";

/* DELETE company */
app.delete('/CompanyServices/company', function(req, res, next) {
    try {
        company = req.query.company;
        dl = new DataLayer(company);
        var row = dl.deleteCompany(company);
        if (row <= 0){
            response = {error:"There is no such company."};
        } else {
            response = {success:company+"'s information deleted."};
        }
     } catch (e) {
        response = {error:"Cannot delete."};
     } finally {
     }  
    res.send(JSON.stringify(response));
});
/* GET departments */
app.get('/CompanyServices/departments', function(req, res, next) {
    try{
        company = req.query.company;
        dl = new DataLayer(company);
        var departments = dl.getAllDepartment(company);
        var d_list = [];
        for(let d of departments ){    	  
            dept = {
                dept_id:d.getId(),
                company:d.getCompany(),
                dept_name:d.getDeptName(),
                dept_no:d.getDeptNo(),
                location:d.getLocation()
            }
            d_list.push(dept)
        }
        response = d_list;
    } catch (e){
        response = {error: "Cannot get departments."};
    } finally {
    } 
    res.send(JSON.stringify(response));
});
/* GET department */
app.get('/CompanyServices/department', function(req, res, next) {
    try{
        company = req.query.company;
        dept_id = req.query.dept_id;
        dl = new DataLayer(company);
        if(vali.checkIfDeptidExist(dept_id) == true){
            var d = dl.getDepartment(company,dept_id);  
            response = {
                    dept_id:d.getId(),
                    company:d.getCompany(),
                    dept_name:d.getDeptName(),
                    dept_no:d.getDeptNo(),
                    location:d.getLocation()
            }
        } else {
            response = {error: "No such dept_id."}
        }
    } catch (e){
        response = {error: "Cannot get department with dept_id: "+dept_id+"."};
    } finally {
    } 
    res.send(JSON.stringify(response));
});
/* POST department */
app.post('/CompanyServices/department', function(req, res, next) {
    try{
        var company = req.body.company;
        var dept_name = req.body.dept_name;
        var dept_no = vali.checkIfDeptnoUnique(req.body.dept_no);
        var location = req.body.location;
        dl = new DataLayer(company);
        var d = new dl.Department(company,dept_name,dept_no,location);
        d = dl.insertDepartment(d);
        if (d.getId()>0){
            response = 
            {
                sucess:
                {
                dept_id:d.getId(),
                company:d.getCompany(),
                dept_name:d.getDeptName(),
                dept_no:d.getDeptNo(),
                location:d.getLocation()
                }
            };
        } else {
            response = {error:"Department is not inserted."};
        }
        
    } catch (e){
        response = {error:"Cannot insert department."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* PUT department */
app.put('/CompanyServices/department', function(req, res, next) {
    try{
        var company = req.body.company;
        var dept_id = parseInt(req.body.dept_id);
        var company = req.body.company;
        var dept_name = req.body.dept_name;
        var dept_no = vali.checkIfDeptnoUniquePut(req.body.dept_no,dept_id);
        var location = req.body.location;
        dl = new DataLayer(company);
        if(vali.checkIfDeptidExist(dept_id) == true){
            var dept = dl.getDepartment(company,dept_id);         
            dept.setDeptName(dept_name);
            dept.setDeptNo(dept_no);
            dept.setLocation(location);
   	        var d = dl.updateDepartment(dept);
            response =
            {
                sucess:
                {
                    dept_id:d.getId(),
                    company:d.getCompany(),
                    dept_name:d.getDeptName(),
                    dept_no:d.getDeptNo(),
                    location:d.getLocation()
                }
            };
        } else {
            response = {error: "There is no such dept_id."}
        }
    } catch (e){
        response = {error:"Cannot update department."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* DELETE department */
app.delete('/CompanyServices/department', function(req, res, next) {
    try {
        company = req.query.company;
        dept_id = req.query.dept_id;
        dl = new DataLayer(company);
        var row = dl.deleteDepartment(company,dept_id);
        if (row <= 0){
            response = {error:"Error with Database."};
        } else {
            response = {success:"Department "+dept_id+" from "+company+" is deleted"};
        }
     } catch (e) {
        response = {error:"Cannot delete."};
     } finally {
     }  
    res.send(JSON.stringify(response));
});

/* GET employees */
app.get('/CompanyServices/employees', function(req, res, next) {
    try{
        company = req.query.company;
        dl = new DataLayer(company);
        var employees = dl.getAllEmployee(company);
        var e_list = [];
        for(let e of employees ){    	  
            emp = {
                emp_id:e.getId(),
                emp_name:e.getEmpName(),
                emp_no:e.getEmpNo(),
                hire_date:e.getHireDate(),
                job:e.getJob(),
                salary:e.getSalary(),
                dept_id:e.getDeptId(),
                mng_id:e.getMngId()
            };
            e_list.push(emp);
        }
        response = e_list;
    } catch (e){
        response = {error: "Cannot get employees."};
    } finally {
    } 
    res.send(response);
});
/* GET employee */
app.get('/CompanyServices/employee', function(req, res, next) {
     try{
        company = req.query.company;
        emp_id = parseInt(req.query.emp_id);
        dl = new DataLayer(company);
        if(vali.checkIfEmpidExist(emp_id) == true){
            var e = dl.getEmployee(emp_id); 
            response = {
                emp_id:e.getId(),
                emp_name:e.getEmpName(),
                emp_no:e.getEmpNo(),
                hire_date:e.getHireDate(),
                job:e.getJob(),
                salary:e.getSalary(),
                dept_id:e.getDeptId(),
                mng_id:e.getMngId()
            };
        } else {
            response = {error:"No such emp_id."}
        }
    } catch (e){
        response = {error: "Cannot get employee with emp_id: "+emp_id+"."};
    } finally {
    } 
    res.send(JSON.stringify(response));
});
/* POST employee */
app.post('/CompanyServices/employee', function(req, res, next) {
    try{
        var company = req.body.company;
        var emp_name = req.body.emp_name;
        var emp_no = vali.checkIfEmpnoUnique(req.body.emp_no);
        var hire_date = req.body.hire_date;
        var job = req.body.job;
        var salary = parseFloat(req.body.salary);
        var dept_id = parseInt(req.body.dept_id);
        var mng_id = parseInt(req.body.mng_id);
        if(!vali.checkIfEmpidExist(mng_id)){
            mng_id = 0;
        }
        //check if the hire_date valid
        if(!vali.checkDateValidate(hire_date)){
            response = {error:"hire_date must be a valid date equal to the current date or earlier."};
        }
        else if(!vali.checkDayOfWeek(hire_date)){
            response = {error:"hire_date must be a weekday."};
        }
        else if(vali.checkIfCompany(company) && vali.checkIfDeptidExist(dept_id)){
            dl = new DataLayer(company);
            //var emp = new dl.Employee(emp_name,emp_no,hire_date,job,salary,dept_id,mng_id);
            var e = dl.insertEmployee(new dl.Employee(emp_name,emp_no,hire_date,job,salary,dept_id,mng_id));
            response = 
            {
                sucess:
                {
                    emp_id:e.getId(),
                    emp_name:e.getEmpName(),
                    emp_no:e.getEmpNo(),
                    hire_date:e.getHireDate(),
                    job:e.getJob(),
                    salary:e.getSalary(),
                    dept_id:e.getDeptId(),
                    mng_id:e.getMngId()
                }
            };
        
        } else {
            response = {error:"DeptId is not exist."};
        }
        
    } catch (e){
        response = {error:"Cannot insert employee."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* PUT employee */
app.put('/CompanyServices/employee', function(req, res, next) {
    try{
        var company = req.body.company;
        var emp_name = req.body.emp_name;
        var emp_id = parseInt(req.body.emp_id);
        var emp_no = vali.checkIfEmpnoUniquePut(req.body.emp_no,emp_id);
        var hire_date = req.body.hire_date;
        var job = req.body.job;
        var salary = parseFloat(req.body.salary);
        var dept_id = parseInt(req.body.dept_id);
        var mng_id = parseInt(req.body.mng_id);
        if(!vali.checkIfEmpidExist(mng_id)){
            mng_id = 0;
        } 
        if(!vali.checkIfEmpidExist(emp_id)){
            response = {error:"There is no such emp_id."};
        } 
        //check if the hire_date valid
        else if(!vali.checkDateValidate(hire_date)){
            response = {error:"hire_date must be a valid date equal to the current date or earlier."};
        }
        else if(!vali.checkDayOfWeek(hire_date)){
            response = {error:"hire_date must be a weekday."};
        }
        else if(vali.checkIfCompany(company) && vali.checkIfDeptidExist(dept_id)){
            dl = new DataLayer(company);
            var e = dl.getEmployee(emp_id);
            e.setEmpName(emp_name);
            e.setEmpNo(emp_no);
            e.setHireDate(hire_date);
            e.setJob(job);
            e.setSalary(salary);
            e.setDeptId(dept_id);
            e.setMngId(mng_id);
            e = dl.updateEmployee(e);
            response = 
            {
                sucess:
                {
                    emp_id:e.getId(),
                    emp_name:e.getEmpName(),
                    emp_no:e.getEmpNo(),
                    hire_date:e.getHireDate(),
                    job:e.getJob(),
                    salary:e.getSalary(),
                    dept_id:e.getDeptId(),
                    mng_id:e.getMngId()
                }
            };
        } else {
            response = {error: "There is no such dept_id."}
        }
    } catch (e){
        response = {error:"Cannot update employee."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* DELETE employee */
app.delete('/CompanyServices/employee', function(req, res, next) {
    try {
        company = req.query.company;
        emp_id = req.query.emp_id;
        dl = new DataLayer(company);
        var row = dl.deleteEmployee(emp_id);
        if (row <= 0){
            response = {error:"There is no such emp_id."};
        } else {
            response = {success:"Employee "+emp_id+" is deleted."};
        }
    } catch (e) {
        response = {error:"Cannot delete."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* GET timecards */
app.get('/CompanyServices/timecards', function(req, res, next) {
    try{
        company = req.query.company;
        emp_id = req.query.emp_id;
        dl = new DataLayer(company);
        if(!vali.checkIfEmpidExist(emp_id)){
            var timecards = dl.getAllTimecard(emp_id);
            var t_list = [];
            for(let t of timecards ){    	  
                timecard = {
                timecard_id:t.getId(),
                start_time:t.getStartTime(),
                end_time:t.getEndTime(),
                emp_id:t.getEmpId()
                };
                t_list.push(timecard);
            }
            response = t_list;
        } else {
            response = {error: "There is no such emp_id."};
        }
    } catch (e){
        response = {error: "Cannot get timecards."};
    } finally {
    } 
    res.send(response);
});
/* GET timecard */
app.get('/CompanyServices/timecard', function(req, res, next) {
    try{
        company = req.query.company;
        timecard_id = parseInt(req.query.timecard_id);
        dl = new DataLayer(company);
        if(vali.checkIfTimeidExist(timecard_id)){
            var t = dl.getTimecard(timecard_id); 
            response = {
                timecard_id:t.getId(),
                start_time:t.getStartTime(),
                end_time:t.getEndTime(),
                emp_id:t.getEmpId()
            };
        } else {
            response = {error:"No such timecard_id."}
        }
    } catch (e){
        response = {error: "Cannot get timecard with timecard_id: "+timecard_id+"."};
    } finally {
    } 
    res.send(JSON.stringify(response));
});
/* POST timecard */
app.post('/CompanyServices/timecard', function(req, res, next) {
    try{
        var company = req.body.company;
        var start_time = req.body.start_time;
        var end_time = req.body.end_time;
        var emp_id = parseInt(req.body.emp_id);
        //check if the start_time and end_time valid
        if(!vali.checkDateValidate(start_time)){
            response = {error:"Not valid start_time: must be a valid date equal to the current date or earlier."};
        }
        else if(!vali.checkIfDayRange(start_time)){
            response = {error:"Not valid start_time: not later than a week of today."};
        }
        else if(!vali.checkDayOfWeek(start_time)){
            response = {error:"Not valid start_time: must be a weekday."};
        }
        else if(!vali.checkIfTimeRange(start_time)){
            response = {error:"Not valid start_time: no earlier than 6:00 and cannot later than 17:00"};
        }
        else if(!vali.checkIfTimeEnd(end_time)){
            response = {error:"Not valid end_time: no earlier than 7:00 and cannot later than 18:00"};
        }
        else if(!vali.checkIfTimeDiff(start_time,end_time)){
            response = {error:"Not valid end_time: End time must be at least 1 hour greater than the start_time"};
        }
        else if(!vali.checkIfDateDiff(start_time,end_time)){
            response = {error:"Not valid end_time: must be on the same day as the start date."};
        } 
        else if(!vali.checkIfEmpTimeDiff(emp_id,start_time)){
            response = {error:"Not valid start_time: must not be the same day as other timecard"};
        }
        else if(vali.checkIfCompany(company) && vali.checkIfEmpidExist(emp_id)){
            dl = new DataLayer(company);
            var time = new dl.Timecard(start_time,end_time,emp_id);
            var t = dl.insertTimecard(time);
            response = 
            {
                sucess:
                {
                    timecard_id:t.getId(),
                    start_time:t.getStartTime(),
                    end_time:t.getEndTime(),
                    emp_id:t.getEmpId()
                }
            };
        
        } else {
            response = {error:"This emp_id does not exist."};
        }
        
    } catch (e){
        response = {error:"Cannot insert timecard."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* PUT timecard */
app.put('/CompanyServices/timecard', function(req, res, next) {
    try{
        var company = req.body.company;
        var timecard_id = req.body.timecard_id;
        var start_time = req.body.start_time;
        var end_time = req.body.end_time;
        var emp_id = parseInt(req.body.emp_id);
        if(!vali.checkIfTimeidExist(timecard_id)){
            response = {error:"Not such timecard_id."};
        }
        //check if the start_time and end_time valid
        else if(!vali.checkDateValidate(start_time)){
            response = {error:"Not valid start_time: must be a valid date equal to the current date or earlier."};
        }
        else if(!vali.checkIfDayRange(start_time)){
            response = {error:"Not valid start_time: not later than a week of today."};
        }
        else if(!vali.checkDayOfWeek(start_time)){
            response = {error:"Not valid start_time: must be a weekday."};
        }
        else if(!vali.checkIfTimeRange(start_time)){
            response = {error:"Not valid start_time: no earlier than 6:00 and cannot later than 17:00"};
        }
        else if(!vali.checkIfTimeEnd(end_time)){
            response = {error:"Not valid end_time: no earlier than 7:00 and cannot later than 18:00"};
        }
        else if(!vali.checkIfTimeDiff(start_time,end_time)){
            response = {error:"Not valid end_time: End time must be at least 1 hour greater than the start_time"};
        }
        else if(!vali.checkIfDateDiff(start_time,end_time)){
            response = {error:"Not valid end_time: must be on the same day as the start date."};
        } 
        else if(!vali.checkIfEmpTimeDiffPut(emp_id,start_time,timecard_id)){
            response = {error:"Not valid start_time: must not be the same day as other timecard"};
        }
        else if(vali.checkIfCompany(company) && vali.checkIfEmpidExist(emp_id)){
            dl = new DataLayer(company);
            var time = dl.getTimecard(timecard_id);
            time.setEmpId(emp_id);
            time.setStartTime(start_time);
            time.setEndTime(end_time);
   	        var t = dl.updateTimecard(time);   
            response = 
            {
                sucess:
                {
                    timecard_id:t.getId(),
                    start_time:t.getStartTime(),
                    end_time:t.getEndTime(),
                    emp_id:t.getEmpId()
                }
            };
        
        } else {
            response = {error:"This emp_id does not exist."};
        }
    } catch (e){
        response = {error:"Cannot update this timecard."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});
/* DELETE timecard */
app.delete('/CompanyServices/timecard', function(req, res, next) {
    try {
        company = req.query.company;
        timecard_id = req.query.timecard_id;
        dl = new DataLayer(company);
        var row = dl.deleteTimecard(timecard_id);
        if (row <= 0){
            response = {error:"There is no such timecard_id."};
        } else {
            response = {success:"Timecard "+timecard_id+" is deleted."};
        }
    } catch (e) {
        response = {error:"Cannot delete."};
    } finally {
    }  
    res.send(JSON.stringify(response));
});


var server = app.listen(8080,function(){
    var host = server.address().address;
    var port = server.address().port;
});
//module.exports = app;