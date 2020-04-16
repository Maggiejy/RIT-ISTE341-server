var express = require('express');
var app = express();
app.use(express.static('public'));
var urlencodedParser = express.urlencoded({extended:false});

app.get('/index.html',function(req,res){
    res.sendFile(__dirname+"/index.html");
});
//urlencodedParser middleware runs first and then the function
app.post('/process_post',urlencodedParser,function(req,res){
    response = {first_name:req.body.first_name,
        last_name:req.body.last_name};
    console.log(response);
    res.end(JSON.stringify(response));
});

app.get('/process_get',function(req,res){
    response = {first_name:req.query.first_name,
                last_name:req.query.last_name};
    console.log(response);
    res.end(JSON.stringify(response));
});
app.get('/',function(req,res){
    //res.send("Hello World!");
    console.log("Got a GET request for the home page");
    res.send("Hello GET");
});
app.post('/',function(req,res){
    console.log("Got a POST request for the home page");
    res.send("Hello POST");
});
app.delete('/del_user',function(req,res){
    console.log("Got a DELETE request for the /del_user");
    res.send("Hello DELETE");
});
app.get('/list_user',function(req,res){
    console.log("Got a GET request for the /list_user");
    res.send("User Listing");
});
app.get('/ab*cd',function(req,res){
    // /abcd /abxcd /ab123cd
    console.log("Got a GET request for the /ab*cd");
    res.send("Pattern Match");
});


var server = app.listen(8081,function(){
    var host = server.address().address;
    var port = server.address().port;
    console.log("Express example listening at http://%s:%s",host,port);

});