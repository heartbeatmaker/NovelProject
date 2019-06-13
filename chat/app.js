/* socket\app.js */
const mysql = require('mysql');
var session = require('express-session');

const app = require('express')(); //라우터 객체를 생성한다
const http = require('http').createServer(app);// app을 http에 연결하고
const io = require('socket.io')(http);//이 http를 다시 socket.io에 연결한다

//const와 var의 차이?

const connection = mysql.createConnection({
    host: '192.168.133.131',
    user: 'root',
    password: 'achilles',
    database: 'webdata'
});

var name='';
connection.connect();
// connection.query('SELECT*FROM userinfo WHERE session_id IS NOT NULL',
//     function (error, results, fields) {
//         if (error) throw error;
//         console.log('name= ', results[0].username);
//         name=results[0].username;
//     }); //논리 오류. session_id는 자동로그인을 한 사용자에게만 저장된다


app.use(session({ //현재 사용하지 않음
    secret: 'key',
    resave: false,
    saveUninitialized: true
}));


var name_received=''; //채팅 시작하기 전에 사용자가 입력한 이름(get방식으로 받아옴)
app.get('/', (req, res) => { // '/'주소로 클라이언트로부터 GET 요청이 올 때 index.html 파일을 보낸다(응답)

    name_received = req.query.name; //get 파라미터를 받는 방법 중 하나. params 도 이용할 수 있다
    console.log("username="+name_received);

    // if(req.session.user){
    //     console.log("sess="+req.session.user);
    //     name=req.session.user;
    // }else{
    //     console.log("nothing");
    // }

    res.sendFile(__dirname + '/index.html');
});


var count=1; //사용자 이름에 붙일 숫자 - 사용하지 않음

// io.on('이벤트명', 함수): 서버에 전달된 이벤트를 인식하여, 함수를 실행시킨다
// 사용자가 접속(=connection 이벤트)하면, 다음의 함수를 실행한다
// 접속한 사용자의 socket = parameter
io.on('connection', (socket) => {

    //접속 알림을 로그에 남긴다. 사용자의 socket.id 출력
    console.log(socket.id+' is connected');

    //나의 이름 지정

    // var name= "user"+ count++; //사용자의 이름을 만든다
    var name= name_received;

    io.to(socket.id).emit('set name', name); //set name이라는 이벤트를 발생시킨다

    //나의 입장 알림(나에게)
    var me_entrance_message = "Welcome!"
    io.to(socket.id).emit('me entered', me_entrance_message);

    //나의 입장 알림(나를 제외한 모두에게)
    var enterance_message= name+" entered the room.";
    //나를 제외한 모든 클라이언트에게 이 이벤트를 전달한다
    socket.broadcast.emit('user entered', enterance_message);


    //해당 소켓에 전달된 이벤트를 인식하여 함수를 실행시키는 이벤트 리스너
    //send message(=사용자가 메시지를 보냄)이 발생했을 때 다음의 함수를 실행한다
    //send message: 메시지를 보낸 사람의 이름과 메시지가 parameter로 전달되어 온다
    socket.on('send message', (name, text) => {

        //나의 메시지를 띄워줌(나에게)
        var my_msg = '[ Me ] : '+text;
        io.to(socket.id).emit('receive my message', my_msg);

        //나의 메시지를 띄워줌(나를 제외한 모두에게)
        var msg = name+ ' : '+text;
        console.log(msg);
        //나를 제외한 모든 클라이언트에게 이 이벤트를 전달한다
        socket.broadcast.emit('receive message', msg);
    });

    //사용자의 접속이 해제(=이벤트)되었을 때 아래 함수를 실행시킨다
    socket.on('disconnect', () => {
        //유저의 접속해제 사실을 로그로 남긴다
        console.log(socket.id+' is disconnected');

        //퇴장 메시지를 화면에 띄운다
        var leave_message= name+" left the room.";
        //나를 제외한 '모든' 클라이언트에게 이 이벤트를 전달한다
        socket.broadcast.emit('user left', leave_message);
    });
});

http.listen(3000, () => {// app.listen이 아닌 http.listen임에 유의한다. 무슨뜻?
    console.log('Connected at 3000');
});