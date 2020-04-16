/* 
terminal help:
node main.js
node
var x = 10
var y = 20
x+y
var sum = _ //last value
console.log(sum)
.help

npm install express
npm init

*/
////console.log("Hello World!");
//var http = require('http');
//
////create our server
////http://localhost:8081/
//http.createServer(function(request,response){
//    //send the HTTP header
//    response.writeHead(200,{'Content-Type':'text/plain'});
//
//    //send response body
//    response.end('Hello world!\n');
//}).listen(8081);
//
//console.log("Server runing at http://127.0.0.1:8081");

// var fs = require('fs');
// // non-blocking example
// fs.readFile("input.txt",function(err,data){
//     if(err) return console.error(err);
//     console.log(data.toString());
// });
// console.log("program ended for non-blcoking example\n");
// blocking example
// var data = fs.readFileSync("input.txt"); //blocking
// console.log(data.toString());
// console.log("program ended");

var events = require("events");
//create an EventEmitter object
var eventEmitter = new events.EventEmitter();

//listener #1
var listener1 = function listener1(){
    console.log('Listener 1 executed');
}
//listener #2
var listener2 = function listener2(){
    console.log('Listener 2 executed');
}
//bind the conneciton event to listener function
eventEmitter.addListener('connection',listener1);

//do the same thing for listener 2
eventEmitter.on('connection',listener2);

//get a list of the listeners
var eventListeners = require('events').EventEmitter.listenerCount(eventEmitter,'connection');
console.log(eventListeners + ' Listener(s) for connection event')

//fire the connection event
eventEmitter.emit('connection');

//remove the binding for listener1
eventEmitter.removeListener('connection',listener1);

//fire the connection event
eventEmitter.emit('connection');
var eventListeners = require('events').EventEmitter.listenerCount(eventEmitter,'connection');
console.log(eventListeners + ' Listener(s) for connection event')


// //create an event handler
// var connectHandler = function connected(){
//     console.log('connection successful');
//     //fire the data_received event
//     eventEmitter.emit('data_received');
// }
// //bind the connection event to the handler
// eventEmitter.on('connection',connectHandler);

// //bind the data receive event to an anonymous function
// eventEmitter.on('data_received',function(){
//     console.log('data received successfully');
// });

// //Fire the connection event
// eventEmitter.emit('connection');