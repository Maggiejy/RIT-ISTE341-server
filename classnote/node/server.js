var http = require('http');
var fs = require('fs');
var url = require('url');

//create the server
http.createServer(function(request,response){
    //parse the request for filename
    var pathName = url.parse(request.url).pathname;
    console.log(pathName);

    //read the requested file
    fs.readFile(pathName.substr(1),function(err,data){
        if(err){
            console.log(err);
            //send 404 message
            response.writeHead(404,{'Content-Type':'text/html'});
            response.write('<html><body>File Not Found</body></html>');
        } else {
            //send 200 with the requested file
            response.writeHead(200,{'Content-Type':'text/html'});
            response.write(data.toString());
        }
        //send the respone body
        response.end();
    })
}).listen(8081);
console.log('server running at http://127.0.0.1:8081');
