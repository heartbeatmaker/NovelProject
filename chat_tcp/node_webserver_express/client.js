var express = require('express');
var app = express();
var jquery = require('jquery');
var net = require('net');

app.use(express.urlencoded()); //body-parser
app.use(express.json()); //body-parser

var path = require('path'); //jquery 사용
app.use('/node_modules', express.static(path.join(__dirname, '/node_modules'))); //jquery 사용때문에

var fs = require('fs'); //file reader
// var template = require('./lib/template.js');

var client ='';
var username = '';
app.get('/', function(req, res){

    client= net.connect(8000, '18.191.197.32', function() {
        username=makeid();

        console.log(username+' is connected');
        client.write(`enter/${username} \r\n`);

        initClientMethod(client);
    });

    res.sendFile(__dirname + '/chat.html');
});

app.listen(3000, function(){
    console.log('client.js is running on port 3000');
});


function makeid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}

function initClientMethod(client) {
    client.on('data', function (data) {
        console.log('Received data from Server: ' + data);

        var incoming_data = data.toString();
        var array = incoming_data.split('/');

        switch(array[0]) {

            case "enter":

                // jquery.ajax({
                //     url: '/enter_post', //서버측에서 가져올 페이지
                //     type: 'POST',//통신타입 설정. GET 혹은 POST. 아래의 데이터를 post 방식으로 넘겨준다.
                //     data: {//서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                //         'enter':1,
                //         'user': array[1]
                //     },
                //
                //     //http 요청 성공 시 발생하는 이벤트
                //     success: function(response){
                //         console.log('(ajax) enter: success')
                //     }
                // });

                break;
        };

        // client.destroy(); // kill client after server's response
    });

    client.on('close', function () {
        console.log('Connection closed');
    });
}

app.get('/read', function(req, res){

});

app.post('/read/post', function(req, res){
    console.log('post request: '+req);
    var data = req.data;
    console.log('post data: '+data);
});

app.post('/enter_post', function(req, res){
    var id = req.body.user;
    console.log("enter_id: "+id);

    client.write(`enter/${id} \r\n`);
});


app.post('/msg_post', function(req, res){
    var msg = req.body.msg_input;
    console.log("msg: "+msg);

    client.write(`msg/${username}/room/${msg} \r\n`);
});