var express = require('express');
var app = express();
var http = require('http').Server(app); // app을 http에 연결하고, 이 http를 다시 socket.io에 연결한다
var io = require('socket.io')(http);    //

app.get('/',function(req, res){  //모든 request는 client.html을 response 한다
    // res.sendFile(__dirname + '/client.html');
    res.send('hello world');
});


var count=1;

// 서버에 전달된 이벤트(=connection= 사용자의 접속)를 인식하여, 함수를 실행시킨다
// 접속한 사용자의 socket = parameter
io.on('connection', function(socket){
    console.log('user connected: ', socket.id);  // 사용자의 socket.id 출력
    var name = "user" + count++;                 // 사용자의 이름 만들기
    io.to(socket.id).emit('change name',name);   // change name이라는 이벤트를 발생시킨다
    // -> 이 이벤트는 client.html의 이벤트 리스너가 처리
    // 해당 socket.id에만 이벤트를 전달한다

    // 해당 소켓에 전달된 event(=disconnect=사용자의 접속이 해제됨)를 인식하여 함수를 실행시키는 이벤트 리스너
    socket.on('disconnect', function(){
        console.log('user disconnected: ', socket.id); // 해당 사용자의 소켓 id를 출력한다
    });


    // 해당 소켓에 전달된 event(=send message=사용자가 메시지를 보냄)를 인식하여 함수를 실행시키는 이벤트 리스너
    // 메시지를 보낸 사용자의 이름과 메시지가 parameter로 함께 전단됨
    socket.on('send message', function(name,text){ //3-3
        var msg = name + ' : ' + text;
        console.log(msg);
        io.emit('receive message', msg); // '모든' 클라이언트에게 이 이벤트를 전달한다
    });
});

http.listen(3000, function(){ // app.listen이 아닌 http.listen임에 유의한다. 무슨뜻?
    console.log('server on!');
});