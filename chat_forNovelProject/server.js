//express: http 모듈에 여러 기능을 추가해서 쉽게 사용할 수 있게 만든 모듈
//node.js의 핵심 모듈인 http와 connect 컴포넌트를 기반으로 하는 웹 프레임워크(=미들웨어??)
//node.js 기본 모듈(http)만 이용하면 불편한 점이 있음. 이것을 개선해줌

const express = require('express');
const app = express();//애플리케이션 객체 생성
const http = require('http').createServer(app);// 웹서버 생성 후 express과 연결
const io = require('socket.io')(http);//이 http를 다시 socket.io에 연결한다


//필요한 외부 모듈
const mysql = require('mysql');
const session = require('express-session');//세션을 쉽게 생성할 수 있게 해줌

//const와 var의 차이?

const connection = mysql.createConnection({
    host: '192.168.133.131',
    user: 'root',
    password: 'vhxmvhffldh@2019',
    database: 'webdata'
});

connection.connect();

app.use(session({ //세션을 적용한다
    secret: 'key', //세션을 암호화
    resave: false, //세션을 항상 저장할 지 여부
    saveUninitialized: true //초기화 되지 않은 채 스토어에 저장되는 세션? 뭔말
}));


var id_received=''; //사용자의 db id와 닉네임
var username_received='';

//app.get(): express 모듈이 제공하는 요청 핸들러(서버가 특정 요청을 받을 때마다 실행됨)
app.get('/', (req, res) => { // '/'주소로 클라이언트로부터 GET 요청이 올 때 처리하는 코드

    id_received = req.query.id; //get 파라미터를 받는 방법 중 하나. params 도 이용할 수 있다
    username_received = req.query.name;

    console.log(id_received+'/'+username_received+' accessed the root path');

    //채팅 참여자의 수를 표시한다
    var clients = io.sockets.clients();
    console.log('client list: '+clients.length);

    //응답: index.html 파일을 보낸다
    res.sendFile(__dirname + '/index.html');
});


var chat_id_received=''; //사용자의 db id와 닉네임
var chat_username_received='';
var chat_room_id_received = '';

app.get('/chat', (req, res) => {

    console.log('chat')

    chat_id_received = req.query.id; //get 파라미터를 받는 방법 중 하나. params 도 이용할 수 있다
    chat_username_received = req.query.name;
    chat_room_id_received = req.query.rm;


    //방에 입장한다
    if(req.query.rm != null){

        console.log(chat_id_received+'/'+chat_username_received+' entered the room.');
        console.log("room_id_received="+chat_room_id_received);

        // //관리자가 기존 방에 입장했을 경우, responded 를 YES로 바꾼다(관리자가 이 채팅에 응했다는 사실을 저장)
        // if(name_received='yonju'){
        //     connection.query('UPDATE chat SET responded = ? WHERE room_id = ?', ['Y', room_id_received], function(err, result){
        //         console.log("mysql result_responded: "+result);
        //     });
        // }
    }

    res.sendFile(__dirname + '/chat.html');
});



// io.on('이벤트명', 함수): 서버에 전달된 이벤트를 인식하여, 함수를 실행시킨다
// 사용자가 접속(=connection 이벤트)하면, 다음의 함수를 실행한다
// 접속한 사용자의 socket = parameter
io.on('connection', (socket) => {


    socket.db_id = id_received; //사용자의 db id와 닉네임 등록
    socket.username = username_received;
    console.log("new user join the chat. user_db_id="+id_received+" username="+username_received);


    //방 목록을 클라이언트에게 보낸다
    var chatInfo_result=''; //존재하는 모든 방 목록
    var userInfo_result=''; //이 사용자가 가입한 방 목록(array)
    connection.query('SELECT * FROM novelProject_chatInfo', function (error, results) { //방 정보 가져오기
        if (error) {
            console.log(error);
        }

        chatInfo_result = results;

        //사용자 정보 가져오기
        connection.query('SELECT * FROM novelProject_userInfo WHERE id = ?', [socket.db_id], function (error, results) {
            if (error) {
                console.log(error);
            }

            //사용자 정보 중에서 참여중인 방 목록만 가져와서, 배열로 만든다
            Object.keys(results).forEach(function(key) {
                var row = results[key];
                console.log(row.joinedChatRooms)
                userInfo_result = row.joinedChatRooms.split(';');
            });

            console.log(userInfo_result);

            //해당 사용자에게 방 목록 발송
            io.to(socket.id).emit('existing rooms', chatInfo_result, userInfo_result);
        });

    });



    //새로운 방을 개설했다는 알림을 받음
    socket.on('new room', (room_name, room_description) => {

        //db에 방 정보를 저장하기
        var time = new Date();
        var info={room_name: room_name, description: room_description, created_time: time, numberOfMembers: 1};
        var room_db_id;

        connection.query('INSERT INTO novelProject_chatInfo SET ?', info, function(err, result) {
            // console.log("mysql result: "+result);
            room_db_id=result.insertId; //db에 값 들어갔는지 확인용
            console.log("new room query: db id: "+room_db_id);

            console.log('a room created: '+room_name+'/'+room_description+'/'+result.insertId);

            //방 추가 사실을 알림(나에게)
            io.to(socket.id).emit('my room created', room_name, room_description, result.insertId, socket.username, socket.db_id);

            //방 추가 사실을 알림(나를 제외한 그룹멤버에게)
            socket.broadcast.emit('room created', room_name, room_description, result.insertId);
        });


        //이 사용자의 db를 업데이트한다('참여중인 채팅방 목록' 컬럼에 데이터를 추가)
        //해당 사용자의 정보를 불러온다
        connection.query('SELECT * FROM novelProject_userInfo WHERE id = ?', [socket.db_id], function(err, result) {
            if (err) {
                console.log(err);
            }
            console.log("mysql result: "+result);

            //참여방 목록만 추출한다
            var joinedRoomList_retrieved = result[0].joinedChatRooms;
            console.log("joinedRoomList: "+joinedRoomList_retrieved);


            //참여방 목록이 비어있으면 -> 이 방 id만 저장
            if(joinedRoomList_retrieved == null | joinedRoomList_retrieved == ''){

                connection.query('UPDATE novelProject_userInfo SET joinedChatRooms = ? WHERE id = ?', [room_db_id, socket.db_id], function(err, result){
                    console.log('the list is empty');
                    console.log("mysql update_responded: "+result);
                });

            }else{ //참여방 목록에 이미 값이 있으면 -> ; 뒤에 이 방 id를 저장

                connection.query('UPDATE novelProject_userInfo SET joinedChatRooms = ? WHERE id = ?', [joinedRoomList_retrieved+';'+room_db_id, socket.db_id], function(err, result){
                    console.log('the list is not empty');
                    console.log("mysql update_responded: "+result);
                });

            }

        });

    });


    //사용자가 어떤 채팅방에 참여하겠다는 알림을 받음
    socket.on('join room', (room__db_id) => {



    });

    // var room =''; //방 이름 변수 초기화
    // if(room_id_received!=''){ //기존에 있던 방에 들어가는 것이라면
    //     room = room_id_received;
    //     socket.join(room); //그룹에 들어간다
    //
    // }else{//새로운 방을 만드는 것이라면
    //     room= "room_"+ generateRandomString(); //방 이름 만들기. 원래는 방 이름이 겹치는지 확인해야함
    //     socket.join(room); //그룹에 들어간다
    //
    //     //db에 방 정보를 저장하기
    //     var time = new Date();
    //     var socketid = socket.id;
    //     var info={room_id: room, socket_id: socketid, name: name, created_time: time, responded: 'N', isEmpty: 'N'};
    //     var last_inserted_id ='';
    //
    //     connection.query('INSERT INTO chat SET ?', info, function(err, result) {
    //         // console.log("mysql result: "+result);
    //         last_inserted_id=result.insertId; //db에 값 들어갔는지 확인용
    //         console.log("last Inserted ID: "+last_inserted_id);
    //     });
    // }
    //
    // //접속 알림을 로그에 남긴다. 사용자의 socket.id와 방 이름 출력
    // console.log(socket.id+' entered '+room);
    //
    // //채팅 참여자의 수를 표시한다
    // io.of('/').in(room).clients(function(error,clients){
    //     const message_with_client_list = clients.length+" participants";
    //     io.to(room).emit('print client list', message_with_client_list);
    // });
    //
    // //나의 이름을 화면에 표시하기
    // io.to(socket.id).emit('set name', name); //set name이라는 이벤트를 발생시킨다
    //
    // //나의 입장 알림(나에게)
    // var me_entrance_message = "Welcome!"
    // io.to(socket.id).emit('me entered', me_entrance_message);
    //
    // //나의 입장 알림(나를 제외한 그룹멤버에게)
    // var enterance_message= name+" entered the room.";
    // //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
    // socket.broadcast.to(room).emit('user entered', enterance_message);
    //
    //
    // //해당 소켓에 전달된 이벤트를 인식하여 함수를 실행시키는 이벤트 리스너
    // //send message(=사용자가 메시지를 보냄)이 발생했을 때 다음의 함수를 실행한다
    // //send message: 메시지를 보낸 사람의 이름과 메시지가 parameter로 전달되어 온다
    // socket.on('send message', (name, text) => {
    //
    //     if(text!='') {
    //         //나의 메시지를 띄워줌(나에게)
    //         var my_msg = '[ Me ] : ' + text;
    //         io.to(socket.id).emit('receive my message', my_msg);
    //
    //         //나의 메시지를 띄워줌(나를 제외한 그룹멤버에게)
    //         var msg = name + ' : ' + text;
    //         console.log(msg);
    //         //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
    //         socket.broadcast.to(room).emit('receive message', msg);
    //     }
    // });
    //
    //
    //
    // //사용자의 접속이 해제(=이벤트)되었을 때 아래 함수를 실행시킨다
    socket.on('disconnect', () => {

        console.log(socket.username+' id disconnected');
    });
    //
    //     //방을 떠난다
    //     socket.leave(room);
    //     //유저의 접속해제 사실을 로그로 남긴다
    //     console.log(socket.id+' left '+room);


        //db에 있는 방 정보를 삭제한다
        // connection.query('DELETE FROM chat WHERE id = ?', [last_inserted_id], function(err, result) {
        //     console.log("mysql-number of deleted rows: "+result.affectedRows);
        // });
    // });
});

//서버 실행
http.listen(3000, () => {// app.listen이 아닌 http.listen임에 유의한다. 무슨뜻?
    console.log('Connected at 3000');
});
//참고: 서버종료: close()






//---------------------  필요한 메소드 -----------------------

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

