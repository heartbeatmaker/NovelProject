var net = require('net');
function getConnection(connName){
    var client = net.connect({port: 8000, host:'18.191.197.32'}, function() {
        console.log(connName + ' Connected: ');
        console.log('   local = %s:%s', this.localAddress, this.localPort);
        console.log('   remote = %s:%s', this.remoteAddress, this.remotePort);
        this.setTimeout(600000); //10분
        this.setEncoding('utf8');
        this.on('data', function(data) {
            console.log(connName + " From Server: " + data.toString());
            this.end();
        });
        this.on('end', function() {
            console.log(connName + ' Client disconnected');
        });
        this.on('error', function(err) {
            console.log('Socket Error: ', JSON.stringify(err));
        });
        this.on('timeout', function() {
            console.log('Socket Timed Out');
        });
        this.on('close', function() {
            console.log('Socket Closed');
        });
    });
    return client;
}
function writeData(socket, data){
    var success = !socket.write(data);
    if (!success){
        (function(socket, data){
            socket.once('drain', function(){
                writeData(socket, data);
            });
        })(socket, data);
    }else{
        console.log('Data is written');
    }
}
var Dwarves = getConnection("Dwarves");
var Elves = getConnection("Elves");
var Hobbits = getConnection("Hobbits");
writeData(Dwarves, "enter/More Axes");
writeData(Elves, "enter/More Arrows");
writeData(Hobbits, "enter/More Pipe Weed");