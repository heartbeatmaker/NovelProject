/* socket\room.js */

//포트폴리오 사이트에서 socket-io로 채팅할 때, 서버를 담당하는 코드이다
//같은 폴더에 있는 index.html 파일 : 클라이언트한테 보여주는 화면

const mysql = require('mysql');
var session = require('express-session');

const app = require('express')(); //라우터 객체를 생성한다
const http = require('http').createServer(app);// app을 http에 연결하고
const io = require('socket.io')(http);//이 http를 다시 socket.io에 연결한다

//const와 var의 차이?

const connection = mysql.createConnection({
    host: '192.168.133.131',
    user: 'root',
    password: 'vhxmvhffldh@2019',
    database: 'webdata'
});


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
var room_id_received = '';

app.get('/', (req, res) => { // '/'주소로 클라이언트로부터 GET 요청이 올 때 index.html 파일을 보낸다(응답)

    name_received = req.query.name; //get 파라미터를 받는 방법 중 하나. params 도 이용할 수 있다
    console.log("username="+name_received);

    //기존 방에 입장하는 경우
    if(req.query.room != null){
        room_id_received = req.query.room;
        console.log("방 넘버를 전달받음");
        console.log("room_id_received="+room_id_received);

        //관리자가 기존 방에 입장했을 경우, responded 를 YES로 바꾼다(관리자가 이 채팅에 응했다는 사실을 저장)
        if(name_received='yonju'){
            connection.query('UPDATE chat SET responded = ? WHERE room_id = ?', ['Y', room_id_received], function(err, result){
                console.log("mysql result_responded: "+result);
            });
        }
    }else{ //새로운 방에 입장할 경우
        room_id_received=''; //변수 초기화!!!
        // 이것을 해주지 않으면 다른 사용자가 원래 있던 방에 들어오게 된다. 새로운 방 생성 못함
        // 밑에서 이 변수의 '' 값 여부에 따라 새로운 방을 생성할지 결정하므로
    }
    res.sendFile(__dirname + '/index.html');
});


// io.on('이벤트명', 함수): 서버에 전달된 이벤트를 인식하여, 함수를 실행시킨다
// 사용자가 접속(=connection 이벤트)하면, 다음의 함수를 실행한다
// 접속한 사용자의 socket = parameter
io.on('connection', (socket) => {

    var name= name_received; //사용자의 이름을 지정
    socket.name = name;

    var room =''; //방 이름 변수 초기화
    if(room_id_received!=''){ //기존에 있던 방에 들어가는 것이라면
        room = room_id_received;
        socket.join(room); //그룹에 들어간다

    }else{//새로운 방을 만드는 것이라면
        room= "room_"+ generateRandomString(); //방 이름 만들기. 원래는 방 이름이 겹치는지 확인해야함
        socket.join(room); //그룹에 들어간다

        //db에 방 정보를 저장하기
        var time = new Date();
        var socketid = socket.id;
        var info={room_id: room, socket_id: socketid, name: name, created_time: time, responded: 'N', isEmpty: 'N'};
        var last_inserted_id ='';

        connection.query('INSERT INTO chat SET ?', info, function(err, result) {
            // console.log("mysql result: "+result);
            last_inserted_id=result.insertId; //db에 값 들어갔는지 확인용
            console.log("last Inserted ID: "+last_inserted_id);
        });
    }

    //접속 알림을 로그에 남긴다. 사용자의 socket.id와 방 이름 출력
    console.log(socket.id+' entered '+room);

    //채팅 참여자의 수를 표시한다
    io.of('/').in(room).clients(function(error,clients){
        const message_with_client_list = clients.length+" participants";
        io.to(room).emit('print client list', message_with_client_list);
    });

    //나의 이름을 화면에 표시하기
    io.to(socket.id).emit('set name', name); //set name이라는 이벤트를 발생시킨다

    //나의 입장 알림(나에게)
    var me_entrance_message = "Welcome!"
    io.to(socket.id).emit('me entered', me_entrance_message);

    //나의 입장 알림(나를 제외한 그룹멤버에게)
    var enterance_message= name+" entered the room.";
    //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
    socket.broadcast.to(room).emit('user entered', enterance_message);


    //해당 소켓에 전달된 이벤트를 인식하여 함수를 실행시키는 이벤트 리스너
    //send message(=사용자가 메시지를 보냄)이 발생했을 때 다음의 함수를 실행한다
    //send message: 메시지를 보낸 사람의 이름과 메시지가 parameter로 전달되어 온다
    socket.on('send message', (name, text) => {

        if(text!='') {
            //나의 메시지를 띄워줌(나에게)
            var my_msg = '[ Me ] : ' + text;
            io.to(socket.id).emit('receive my message', my_msg);

            //나의 메시지를 띄워줌(나를 제외한 그룹멤버에게)
            var msg = name + ' : ' + text;
            console.log(msg);
            //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
            socket.broadcast.to(room).emit('receive message', msg);
        }
    });



    //사용자의 접속이 해제(=이벤트)되었을 때 아래 함수를 실행시킨다
    socket.on('disconnect', () => {

        //퇴장 메시지를 화면에 띄운다
        var leave_message= name+" left the room.";
        //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
        socket.broadcast.to(room).emit('user left', leave_message);

        //채팅 참여자의 수를 표시한다
        io.of('/').in(room).clients(function(error,clients){
            const message_with_client_list = clients.length+" participants";
            io.to(room).emit('print client list', message_with_client_list);

            //마지막 사람이 방에서 나갈 때, db를 업데이트한다
            if(clients.length == 0){
                console.log("clients.length: "+clients.length);
                console.log("room: "+room);
                connection.query('UPDATE chat SET isEmpty = ? WHERE room_id = ?', ['Y', room], function(err, result){
                    console.log("mysql result_isEmpty: "+result);
                });
            }
        });

        //방을 떠난다
        socket.leave(room);
        //유저의 접속해제 사실을 로그로 남긴다
        console.log(socket.id+' left '+room);


        //db에 있는 방 정보를 삭제한다
        // connection.query('DELETE FROM chat WHERE id = ?', [last_inserted_id], function(err, result) {
        //     console.log("mysql-number of deleted rows: "+result.affectedRows);
        // });
    });
});

http.listen(3000, () => {// app.listen이 아닌 http.listen임에 유의한다. 무슨뜻?
    console.log('Connected at 3000');
});

function generateRandomString(){
    var resource = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var result='';
    for(var i = 0; i < 6; i++) {
        result += resource.charAt(Math.floor(Math.random() * resource.length));
    };
    return result;
}

function numOfClientsInRoom(namespace, room) {
    var clients = io.nsps[namespace].adapter.rooms[room];
    console.log("clients="+clients);
    for(var client in clients){
        console.log(client);
    }
    return Object.keys(clients).length;
}

