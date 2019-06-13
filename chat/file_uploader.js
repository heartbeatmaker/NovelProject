const express = require('express');
const app = express();
const httpServer = require('http').Server(app);
const path = require('path');
const siofu = require('socketio-file-upload');
const socketServer = require('socket.io')(httpServer);

app.set('view engine', 'pug');
app.set('views', path.join(__dirname, '/public/views'));
app.use(express.static(path.join(__dirname, '/public')));
app.use(siofu.router);

app.get('/', (req, res) => {
    res.render('index');
});
~
socketServer.on('connection', socket => {
    console.log('Socket connected');
    socket.emit('data', 'Hello World');
    const uploader = new siofu();
    uploader.dir = path.join(__dirname, '/files');
    uploader.listen(socket);
});

httpServer.listen(3000);