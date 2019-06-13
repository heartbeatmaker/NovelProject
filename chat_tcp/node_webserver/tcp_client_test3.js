var net = require('net');

var client = new net.Socket();
client.connect(8000, '18.191.197.32', function() {
    console.log('Connected');
    client.write('enter/IM NODEJS CLIENT4 \r\n');
});

client.on('data', function(data) {
    console.log('Received: ' + data);
    // client.destroy(); // kill client after server's response
});

client.on('close', function() {
    console.log('Connection closed');
});