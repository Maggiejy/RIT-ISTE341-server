var http = require("http");
var server = http.createServer(function(request,response){

});
server.listen(1234,function(){
    console.log((new Date())+' Server is listening on port 1234');
});

var WebSocketServer = require('websocket').server;
var wsServer = new WebSocketServer({httpServer: server});

var count = 0;
var clients = {};

wsServer.on('request',function(request){
    //we've received a "request" event for a connection
    //accept the connetion
    var connection = request.accept(null,request.origin);

    //create an id for the client
    var id = count++;

    //store the connection
    clients[id] = connection;
    console.log((new Date())+ ' connection accepted ['+id+']');

    //client sent a message
    connection.on('message',function(message){
        //the string message that was sent to us, should only be text
        //so should check message type first
        var msgString = message.utf8Data; //crush if not utf8
        //loop through all clients/connections
        for (var i in clients){
            //echo the message
            clients[i].sendUTF(msgString);
        }

    });

    //user disconnect
    connection.on('close',function(reasonCode,description){
        console.log((new Date())+ ' User: '+connection.remoteAddress+' disconnected');
        delete clients[id];
    });

});