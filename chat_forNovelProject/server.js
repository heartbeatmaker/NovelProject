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

    console.log(username_received+' is connected from index.html. user_db_id='+id_received);

    chat_room_id_received = ''; //초기화해준다 - 이 값의 여부를 기준으로 로비와 챗방을 나눔

    //응답: index.html 파일을 보낸다
    res.sendFile(__dirname + '/index.html');
});



var chat_id_received=''; //사용자의 db id와 닉네임
var chat_username_received='';
var chat_room_id_received = '';

app.get('/chat', (req, res) => {

    //변수를 전달받는다
    chat_id_received = req.query.id; //get 파라미터를 받는 방법 중 하나. params 도 이용할 수 있다
    chat_username_received = req.query.name;
    chat_room_id_received = req.query.rm;

    if(req.query.rm != null){
        console.log(chat_username_received+' is connected from chat.html. user_db_id='+chat_id_received+' room_db_id='+chat_room_id_received);
    }

    res.sendFile(__dirname + '/chat.html');
});





var roomInfo = {}; //방 정보를 담는 객체

// io.on('이벤트명', 함수): 서버에 전달된 이벤트를 인식하여, 함수를 실행시킨다
// 사용자가 접속(=connection 이벤트)하면, 다음의 함수를 실행한다
// 접속한 사용자의 socket = parameter
io.on('connection', (socket) => {


    if(chat_room_id_received!='') { //방에 입장하는 경우

        //사용자 db id, 닉네임, 방id를 등록한다
        socket.db_id = chat_id_received;
        socket.username = chat_username_received;
        socket.room_id = chat_room_id_received;


        socket.join(chat_room_id_received); //방에 들어간다


        //방 정보 업데이트
        var socketInfo = new Object();
        socketInfo.user_id = socket.db_id;
        socketInfo.username = socket.username;

        var isNew = true; //이 사용자가 이 방에 처음 들어왔는지 확인하는 변수

        if(roomInfo[socket.room_id] != null){ //이 방 객체가 이미 있으면
            console.log('room object already exists')

            console.log('room object:'+roomInfo[socket.room_id]);

            //이 사용자가 이미 이 방에 참여중인지 확인한다
            var individual_roomInfo = roomInfo[socket.room_id];


            //이 방 참여자 목록에 이 사용자가 없으면, 이 사용자를 참여자 목록에 추가한다
            console.log('checking if '+socket.username+' is new to the room'+socket.room_id);
            for(var k=0; k<individual_roomInfo.length; k++){

                if(individual_roomInfo[k].user_id == socket.db_id){
                    console.log(socket.username+' has already join the room'+socket.room_id);
                    isNew = false;
                }
            }

            if(isNew){
                individual_roomInfo.push(socketInfo);
                console.log(socket.username+' is new to this room'+socket.room_id)
            }

        }else{//이 방 객체가 없으면
            console.log('room object doesnt exist')

            console.log('room object:'+roomInfo[socket.room_id]);

            var individual_roomInfo = new Array();
            individual_roomInfo.push(socketInfo);

            //해당 사용자의 정보를 담은 객체를 방정보 객체에 추가한다
            roomInfo[socket.room_id] = individual_roomInfo;
        }

        console.log(roomInfo);


        //이 방 참여자 수, 명단을 세서 클라이언트에 보낸다
        var array = roomInfo[socket.room_id];

        var numberOfMembers = array.length;
        var members= '';
        for(var i=0; i<numberOfMembers; i++){

            members += array[i].username;
            members += ', '
        }
        console.log('numberOfMembers:'+numberOfMembers+' / members: '+members);
        io.to(socket.room_id).emit('print members', numberOfMembers, members);




        console.log("new user joined the chat room ("+socket.room_id+") socket.id="+socket.id+" / user_db_id="+socket.db_id+" / username="+socket.username);


        //방 이름을 표시한다
        connection.query('SELECT * FROM novelProject_chatInfo WHERE id = ?', [socket.room_id], function (error, results) {
            if (error) {
                console.log(error);
            }

            var room_name = results[0].room_name;
            console.log('room name='+room_name);
            io.to(socket.room_id).emit('print room name', room_name);

        });



        //나의 입장 사실을 나에게 알림
        var me_entrance_message = "";
        if(isNew){
            me_entrance_message = "Welcome.";
        }else{
            me_entrance_message = "Welcome Back.";
        }
        io.to(socket.id).emit('me entered', me_entrance_message);


        //나의 입장 사실을 다른 사람들에게 알림
        var entrance_message = "";
        if(isNew){
            entrance_message = socket.username+" entered the room.";
        }else{
            entrance_message = socket.username+" is back to the room.";
        }
        //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
        socket.broadcast.to(socket.room_id).emit('user entered', entrance_message);


        //내가 메시지를 보냈다는 알림을 받음
        socket.on('send message', (msg) => {

            if(msg != '') {

                //나의 메시지를 나에게 띄워줌
                io.to(socket.id).emit('receive my message', msg, getTime());

                //나의 메시지를 다른 사람들에게 띄워줌
                socket.broadcast.to(socket.room_id).emit('receive message', socket.username, msg, getTime());
            }
        });








    }else{ //로비에 입장하는 경우

        //사용자의 db id와 닉네임 등록
        socket.db_id = id_received;
        socket.username = username_received;
        console.log("new user entered the lobby. socket.id="+socket.id+" / user_db_id="+socket.db_id+" / username="+socket.username);



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

                try{
                    if(results == null | results==''){}else{
                        //사용자 정보 중에서 참여중인 방 목록만 가져와서, 배열로 만든다
                        Object.keys(results).forEach(function(key) {
                            var row = results[key];
                            console.log(row.joinedChatRooms)
                            userInfo_result = row.joinedChatRooms.split(';');
                        });
                    }
                }catch (e) {
                    console.log('error occured:'+e);
                }


                // console.log(userInfo_result);

                //해당 사용자에게 방 목록 발송
                io.to(socket.id).emit('existing rooms', chatInfo_result, userInfo_result);
            });

        });



        //새로운 방을 개설했다는 알림을 받음 -> 방 정보를 db에 저장하고, socket을 방에 join 시킴
        socket.on('new room', (room_name, room_description) => {

            //db에 방 정보를 저장하기
            var time = new Date();
            var info={room_name: room_name, description: room_description, created_time: time, numberOfMembers: 1};
            var room_db_id;

            connection.query('INSERT INTO novelProject_chatInfo SET ?', info, function(err, result) {
                // console.log("mysql result: "+result);
                room_db_id=result.insertId; //db에 값 들어갔는지 확인용
                // console.log("new room query: db id: "+room_db_id);

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

                try{

                    //참여방 목록만 추출한다
                    var joinedRoomList_retrieved = result[0].joinedChatRooms;
                    console.log("joinedRoomList: "+joinedRoomList_retrieved);novelProject_community

                }catch (e) {
                    console.log('error while retrieving joinedChatRoom list')
                }


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
        //중복확인 후, 사용자의 db와 방db를 업데이트한다
        //중복확인: 사용자가 이미 이 방에 참여중인지 확인
        socket.on('join room', (room_db_id, room_name, room_description) => {


            var isNewRoom = true; //사용자가 이미 이 방에 참여중인지 확인하는 변수

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

                }else{ //참여방 목록에 이미 값이 있으면 -> 중복 확인 후, ; 뒤에 이 방 id를 저장
                    console.log('the list is not empty');

                    var userInfo_result = joinedRoomList_retrieved.split(';');

                    console.log('Checking if the user if new to this room..');
                    for(var j=0; j<userInfo_result.length; j++){

                        if(userInfo_result[j]==room_db_id){
                            isNewRoom = false;
                            console.log('the user is not new to this room');
                        }
                    }

                    if(isNewRoom){

                        console.log('the user is new to this room. updating joinedChatRooms column at userInfo');
                        connection.query('UPDATE novelProject_userInfo SET joinedChatRooms = ? WHERE id = ?', [joinedRoomList_retrieved+';'+room_db_id, socket.db_id], function(err, result){
                            console.log("mysql update_responded: "+result);
                        });

                    }

                }

            });


            var new_joinedRoom = '';
            if(isNewRoom){ // 사용자가 이 방에 새로 입장하는 것이라면

                //이 방의 db 업데이트
                connection.query('UPDATE novelProject_chatInfo SET numberOfMembers = numberOfMembers +1  WHERE id = ?', [room_db_id], function(err, result){
                    if (err) {
                        console.log(err);
                    }
                    console.log("mysql update_responded - numberOfMembers+1: "+result);
                });


                new_joinedRoom = '<div class="chat_list">\n' +
                    '                        <div class="chat_people">\n' +
                    '                            <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>\n' +
                    '                            <div class="chat_ib">\n' +
                    '                                <h5 id="room_name">'+room_name+'<span class="chat_date">1명</span></h5>\n' +
                    '                                <p id="description">'+room_description+'</p>\n' +
                    '                            </div>\n' +
                    '                            <input type="hidden" id="room_data" data-room_name="'+room_name+'" data-description="'+room_description+'" data-room_db_id="'+room_db_id+'">\n'+
                    '                        </div>\n' +
                    '                    </div>';

            }else{//이미 가입한 방에 다시 들어가는 것이라면

                new_joinedRoom = 'old';
            }
            console.log('new_joinedRoom='+new_joinedRoom);

            //이사람의 id, 닉네임, room_db_id, 새로 참여하는 방의 html 코드를 전송한다
            io.to(socket.id).emit('info for join', socket.db_id, socket.username, room_db_id, new_joinedRoom);

        });


    }




    //사용자의 접속이 해제 되었을 때 = 해당 페이지를 나감
    socket.on('disconnect', () => {

        if(socket.room_id!=null){

            console.log(socket.username+' id disconnected from the room ('+socket.room_id+')');
            //방을 떠난다
            socket.leave(socket.room_id);

            //메시지를 화면에 띄운다(안 읽고있음) @@퇴장메시지 아님!!@@
            var leave_message= socket.username+" is not reading messages.";
            //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
            socket.broadcast.to(socket.room_id).emit('user disconnected', leave_message);

        }else{
            console.log(socket.username+' id disconnected from the lobby');
        }

    });


    // db에 있는 방 정보를 삭제한다 - 나중에 나가기 버튼 만들면 그때 실행
    // @@퇴장메시지 띄워주기@@
    // connection.query('DELETE FROM chat WHERE id = ?', [last_inserted_id], function(err, result) {
    //     console.log("mysql-number of deleted rows: "+result.affectedRows);
    // });




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


// 현재 방에 있는 사용자 인원 세기(위의 메소드 동작x 이게 진짜)
// io.of('/').in(socket.room_id).clients(function(error,clients){
//
//     const numberOfParticipants = clients.length;
//
//     console.log('numberOfParticipants:'+numberOfParticipants);
//     io.to(socket.room_id).emit('print number', numberOfParticipants);
// });


function getTime(){
    var time = new Date();
    var time_modified = time.format("hh:mm a/p | MM-dd");

    return time_modified;
    return time;
}



Date.prototype.format = function (f) {

    if (!this.valueOf()) return " ";



    var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];

    var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];

    var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var d = this;



    return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function ($1) {

        switch ($1) {

            case "yyyy": return d.getFullYear(); // 년 (4자리)

            case "yy": return (d.getFullYear() % 1000).zf(2); // 년 (2자리)

            case "MM": return (d.getMonth() + 1).zf(2); // 월 (2자리)

            case "dd": return d.getDate().zf(2); // 일 (2자리)

            case "KS": return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)

            case "KL": return weekKorName[d.getDay()]; // 요일 (긴 한글)

            case "ES": return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)

            case "EL": return weekEngName[d.getDay()]; // 요일 (긴 영어)

            case "HH": return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)

            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)

            case "mm": return d.getMinutes().zf(2); // 분 (2자리)

            case "ss": return d.getSeconds().zf(2); // 초 (2자리)

            case "a/p": return d.getHours() < 12 ? "am" : "pm"; // 오전/오후 구분

            default: return $1;

        }

    });

};



String.prototype.string = function (len) { var s = '', i = 0; while (i++ < len) { s += this; } return s; };

String.prototype.zf = function (len) { return "0".string(len - this.length) + this; };

Number.prototype.zf = function (len) { return this.toString().zf(len); };