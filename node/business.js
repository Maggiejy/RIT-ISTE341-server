var moment = require("moment");
var DataLayer = require("./companydata/index.js");
var company = "yj3010";
var dl = new DataLayer(company);
module.exports = {
    checkIfCompany: function(company) {
        if(company == "yj3010"){
            return true;
        } else {
            return false;
        }
    },
    checkIfDeptnoUnique: function(dept_no) {
        var deptNo_list = [];
        var departments = dl.getAllDepartment(company);
        for(let dept of departments){
            deptNo_list.push(dept.getDeptNo());  
        }
        if(deptNo_list.includes(dept_no)){
            dept_no = dept_no.concat("_1");
        }
        return dept_no 
    },
    checkIfDeptnoUniquePut: function(dept_no,id) {
        var departments = dl.getAllDepartment(company);
        for(let dept of departments){
            if(dept.getDeptNo() == dept_no && dept.getId() != id){
                dept_no = dept_no.concat("_1");
            }
        }
        return dept_no 
    },
    checkIfDeptidExist: function(id) {
        var deptId_list = [];
        var departments = dl.getAllDepartment(company);
        for(let dept of departments){
            deptId_list.push(dept.getId());
        }
        if(!deptId_list.includes(id)){
            return false;
        } else {
            return true;
        }
    },
    checkIfEmpnoUnique: function(emp_no) {
        var empNo_list = [];
        var employees = dl.getAllEmployee(company);
        for(let dept of employees){
            empNo_list.push(dept.getEmpNo());  
        }
        if(empNo_list.includes(emp_no)){
            emp_no = emp_no.concat("_1");
        }
        return emp_no 
    },
    checkIfEmpnoUniquePut: function(emp_no,emp_id) {
        var employees = dl.getAllEmployee(company);
        for(let emp of employees){
            if(emp.getEmpNo() == emp_no && emp.getId() != emp_id){
                emp_no = emp_no.concat("_1");
            }
        }
        return emp_no 
    },
    checkIfEmpidExist: function(id) {
        var empId_list = [];
        var employees = dl.getAllEmployee(company);
        for(let emp of employees){
            empId_list.push(emp.getId());
        }
        if(!empId_list.includes(id)){
            return false;
        } else {
            return true;
        }
    },
    checkDateValidate: function(date) {
        var dateVali = moment(date)
        var now = moment();
        if(dateVali.isSameOrBefore(now)){
            return true;
        } else {
            return false;
        }

    },
    checkDayOfWeek: function(date) {
        var dow = moment(date);
        if(dow.day() != 6 && dow.day() != 0){
            return true;
        } else {
            return false;
        }
    },
    checkIfTimeidExist: function(id){
        var row = dl.getTimecard(id);
        if(row == null){
            return false;
        } else {
            return true;
        }
    },
    checkIfDayRange: function(date){
        var dateVali = moment(date)
        var now = moment()
        var lastRangeDay = now.subtract(7,'days')
        if(dateVali.isSameOrAfter(lastRangeDay)){
            return true;
        } else {
            return false;
        }
    },
    checkIfTimeRange: function(time){
        var dateVali = moment(time);
        if(dateVali.hour()>=6 && dateVali.hour()<17){
            return true;
        } else if(dateVali.hour() ==17 && dateVali.minute()==0){
            return true;
        }else {
            return false;
        }
    },
    checkIfTimeEnd: function(time){
        var dateVali = moment(time);
        if(dateVali.hour()>=7 && dateVali.hour()<18){
            return true;
        } else if(dateVali.hour() ==18 && dateVali.minute()==0){
            return true;
        }else {
            return false;
        }
    },
    checkIfTimeDiff: function(time1, time2){
        var startVali = moment(time1);
        var endVali = moment(time2);
        if(startVali.isBefore(endVali,'hour')){
            return true;
        } else {
            return false;
        }
    },
    checkIfEmpTimeDiff: function(emp_id, time){
        var dateVali = moment(time);
        var timecards = dl.getAllTimecard(emp_id);
        for(let tm of timecards){
            var d = moment(tm.getStartTime());
            if(tm.getEmpId() == emp_id && dateVali.isSame(d,'day')){
                return false;
            } else {
                return true;
            }
        }
    },
    checkIfEmpTimeDiffPut: function(emp_id, time, timecard_id){
        var dateVali = moment(time);
        var timecards = dl.getAllTimecard(emp_id);
        for(let tm of timecards){
            var d = moment(tm.getStartTime());
            if(tm.getEmpId() == emp_id && dateVali.isSame(d,'day') && tm.getId() != timecard_id){
                return false;
            } else {
                return true;
            }
        }
    },
    checkIfDateDiff: function(time1, time2){
        var startVali = moment(time1);
        var endVali = moment(time2);
        if(startVali.isSame(endVali,'day')){
            return true;
        } else {
            return false;
        }
    }
}