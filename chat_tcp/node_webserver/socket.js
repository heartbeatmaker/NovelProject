var http = require('http');
var fs = require('fs');
var url = require('url'); //url이라는 모듈을 사용한다
var qs = require('querystring');
var net = require('net');

var client = new net.Socket();
client.connect(8000, '18.191.197.32', function() {
    console.log('Connected');
    client.write('enter/yonju from nodejs \r\n');
});

client.on('data', function(data) {
    console.log('Received: ' + data);


    //ajax: 서버에 요청을 하는 방식 중 하나
    //서버와 비동기적으로 통신한다. 비동기: 서버와 통신하는 동안 다른 작업을 할 수 있다는 의미
    //jquery에서 ajax() 함수를 사용하면 편리하게 서버와 통신할 수 있다
    $.ajax({
        url: '/process_read', //서버측에서 가져올 페이지
        type: 'POST',//통신타입 설정. GET 혹은 POST. 아래의 데이터를 post 방식으로 넘겨준다.
        data: {//서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
            'message': data
        },

        //http 요청 성공 시 발생하는 이벤트
        success: function(response){
            console.log('message received')

            //response=서버에서 받아온 데이터=$saved_comment=추가된 댓글
            //response를 display_area(=전체 댓글 div)의 마지막에 붙인다
            $('#result').append(response);
        }
    });





    // client.destroy(); // kill client after server's response
});

client.on('close', function() {
    console.log('Connection closed');
});


function templateHtml(){
    var data = `
<html>
<head>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
</head>
<body>
    <div>
    <p>Data to Server</p>
    <form action="/process_write" method="post">
    <input type="text" name="text" id="text">
    <input type="submit" name="btn" id="btn">
    </form>
    </div>
    
    <br>
    
    <div id="result">
    <p>Data from Server</p>
    <p>sample data</p>
    </div>
    
</body>
</html>
    `;
    return data;
}

var app = http.createServer(function(request, response){
    var _url = request.url;
    var queryData = url.parse(_url, true).query;
    var pathname = url.parse(_url, true).pathname; //url에 들어있는 정보 중 pathname을 가져온다

    if(pathname === '/') { //루트로 접근했을 경우 아래와같이 정상적인 정보를 띄워준다

        //홈화면으로 들어갔을 경우 (id값 = undefined)
        //없는 값일 경우, undefined가 뜬다
        if (queryData.id === undefined) {

            var template = templateHtml();

            response.writeHead(200);
            response.end(template); //클라이언트에게 넘겨줄 데이터(response 방식)

        } else { //비정상적인 경로 -> not found 띄워줌
            response.writeHead(404);
            response.end('Not Found');
        }
    }else if(pathname === '/process_write') {

        var message='';
        request.on('data', function(data){
            message += data;
        });
        request.on('end', function(){
            var post = qs.parse(message);
            var text = post.text;
            console.log("my message: "+text);

            client.write(`${text} \r\n`);

            response.writeHead(302, {Location: '/'});
            response.end('success'); //클라이언트에게 넘겨줄 데이터(response 방식)

        });
    }else if(pathname === '/process_read') {

        var message='';
        request.on('data', function(data){
            message += data;
        });
        request.on('end', function(){
            var post = qs.parse(message);
            var text = post.text;
            console.log("my message: "+text);

            client.write(`${text} \r\n`);

            response.writeHead(302, {Location: '/'});
            response.end('success'); //클라이언트에게 넘겨줄 데이터(response 방식)

        });
    }
});
app.listen(3000);