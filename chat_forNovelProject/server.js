/*진짜 채팅서버*/

/*중요: 채팅 로비와 방을 서로 다른 namespace로 나눠서 관리해야함
* -> 로비에만 broadcast, 방에만 broadcast할 수 있음*/

//express: http 모듈에 여러 기능을 추가해서 쉽게 사용할 수 있게 만든 모듈
//node.js의 핵심 모듈인 http와 connect 컴포넌트를 기반으로 하는 웹 프레임워크(=미들웨어??)
//node.js 기본 모듈(http)만 이용하면 불편한 점이 있음. 이것을 개선해줌


const express = require('express');
const app = express();//애플리케이션 객체 생성
const http = require('http').createServer(app);
const io = require('socket.io')(http);//이 http를 다시 socket.io에 연결한다


//필요한 외부 모듈blog_post
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


//정적인 파일을 서비스 하는 법 = 서버에 있는 파일(이미지, 텍스트)을 클라이언트에게 전달
//public 이라는 폴더를 만들고, 그 안에 파일을 넣는다
//ex) http://localhost:3000/bento 로 접속했을때 bento.png 이미지가 출력된다
app.use(express.static('public'));


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





var roomInfo = {}; //방 정보를 담는 객체. individual_roomInfo 배열을 여러 개 포함한다
// var individual_roomInfo = new Array(); //개별 방의 정보를 담는 배열. socketInfo를 여러 개 포함한다
// var socketInfo = new Object(); //개별 사용자의 정보를 담는 객체. 사용자 id, 닉네임, 방 참여 시점을 포함한다

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

        //이 사용자의 정보를 담은 객체 생성
        var socketInfo = new Object();
        socketInfo.user_id = socket.db_id;
        socketInfo.username = socket.username;
        socketInfo.joinedTime = new Date(); //방에 최초로 참여한 시각

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


        //나의 db id를 보내놓는다
        io.to(socket.id).emit('my id', socket.db_id);


        //나의 입장 사실을 나에게 알림
        var me_entrance_message = "";

        if(isNew){ //신규 참여자 -> 환영 메시지를 띄워준다
            me_entrance_message = "Welcome.";

        }else{ //기존 참여자 -> 원래 있던 메시지를 띄워준다

            //**원래 있던 메시지 = 이 사용자가 '채팅에 최초로 참여한 시점'부터 주고받은 메시지
            //저장된 메시지를 다 보여주면 안됨


            //이 사용자가 채팅에 최초로 참여한 시각을 가져온다 -- 이거 나중에 db에 저장해서 가져오기!!
            var joinedTime = '';
            var individual_roomInfo = roomInfo[socket.room_id];

            for(var k=0; k<individual_roomInfo.length; k++){

                if(individual_roomInfo[k].user_id == socket.db_id){

                    joinedTime = individual_roomInfo[k].joinedTime;
                }
            }


            connection.query('SELECT * FROM novelProject_chatInfo WHERE id = ?', [socket.room_id], function (error, results) {
                if (error) {
                    console.log(error);
                }

                try{

                    var saved_message = results[0].message;
                    console.log('saved_message = '+saved_message);

                    if(saved_message == null || saved_message == ''){}
                    else{

                        var message_array = saved_message.split(';*;');

                        //이 사용자의 id, 채팅방 가입 시각, 메시지 내용을 해당 클라이언트에게 보낸다
                        io.to(socket.id).emit('print saved messages', socket.db_id, joinedTime, message_array);

                    }

                }catch (e) {
                    console.log('error while retrieving existing messages: '+e);
                }

            });

            // me_entrance_message = "Welcome Back.";
        }
        // io.to(socket.id).emit('me entered', me_entrance_message);


        //나의 입장 사실을 다른 사람들에게 알림
        var entrance_message = "";
        if(isNew){
            entrance_message = socket.username+" entered the room.";
        }else{
            entrance_message = socket.username+" is back to the room.";
        }
        //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
        socket.broadcast.to(socket.room_id).emit('user entered', entrance_message);


        //내가 메시지를 보냈다는 알림을 받음 -> db에 메시지 저장 후, 클라이언트에게 메시지 전달
        socket.on('send message', (msg) => {


            //저장할 메시지 내용
            var time = new Date();
            //보낸사람id;보낸사람 닉네임;메시지 내용;보낸 시각
            var data = socket.db_id+';~;'+socket.username+';~;'+msg+';~;'+time;


            //메시지 내용을 db에 저장한다
            //기존에 채팅 내용이 있는지 확인
            //없으면 -> 그냥 저장
            //있으면 -> 기존 내용 불러다가 그 뒤에 저장
            connection.query('SELECT * FROM novelProject_chatInfo WHERE id = ?', [socket.room_id], function(err, result) {
                if (err) {
                    console.log(err);
                }


                var existing_message = '';
                try{

                    //기존 메시지 내역을 가져온다
                    existing_message = result[0].message;
                    console.log("existing_message: "+existing_message);

                }catch (e) {
                    console.log('error while retrieving joinedChatRoom list')
                }

                //메시지 내역이 비어있으면 -> 이 메시지부터 저장
                if(existing_message == null || existing_message == ''){

                    connection.query('UPDATE novelProject_chatInfo SET message = ? WHERE id = ?', [data, socket.room_id], function(err, result){
                        if(err){
                            console.log(err);
                        }
                        console.log('there is no saved message in this room');
                    });

                }else{ //메시지 내역이 있으면 -> ;*; 뒤에 이 메시지를 저장

                    connection.query('UPDATE novelProject_chatInfo SET message = ? WHERE id = ?', [existing_message+";*;"+data, socket.room_id], function(err, result){
                        if(err){
                            console.log(err);
                        }
                        console.log('there are saved messages!!!');
                    });

                }

            });


            if(msg != '') { //메시지가 비어있지 않으면 메시지를 띄워준다

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
        var chatInfo_result=''; //존재하는 모든 방 목록 (object)
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
                    if(results == null || results==''){}else{
                        //사용자 정보 중에서 참여중인 방 목록만 가져와서, 배열로 만든다
                        Object.keys(results).forEach(function(key) {
                            var row = results[key];
                            console.log(row.joinedChatRooms)

                            if(row.joinedChatRooms == null || row.joinedChatRooms == ''){

                                userInfo_result = 'empty';

                            }else{
                                userInfo_result = row.joinedChatRooms.split(';');
                            }

                        });
                    }
                }catch (e) {
                    console.log('error occured:'+e);
                }


                //해당 사용자에게 방 목록 발송
                io.to(socket.id).emit('existing rooms', chatInfo_result, userInfo_result);
            });

        });



        //새로운 방을 개설했다는 알림을 받음 -> 방 정보를 db에 저장하고, socket을 방에 join 시킴
        socket.on('new room', (room_name, room_description) => {

            //db에 방 정보를 저장하기
            var time = new Date();
            var image_number = getRandomNumber(25)
            var info={room_name: room_name, description: room_description, created_time: time, numberOfMembers: 1, image: image_number};
            var room_db_id;

            connection.query('INSERT INTO novelProject_chatInfo SET ?', info, function(err, result) {
                // console.log("mysql result: "+result);
                room_db_id=result.insertId; //db에 값 들어갔는지 확인용
                // console.log("new room query: db id: "+room_db_id);

                console.log('a room created: '+room_name+'/'+room_description+'/'+result.insertId);

                //방 추가 사실을 알림(나에게)
                io.to(socket.id).emit('my room created', room_name, room_description, result.insertId, socket.username, socket.db_id, image_number);

                //방 추가 사실을 알림(나를 제외한 그룹멤버에게)
                socket.broadcast.emit('room created', room_name, room_description, result.insertId, image_number);
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
                    console.log("joinedRoomList: "+joinedRoomList_retrieved);

                }catch (e) {
                    console.log('error while retrieving joinedChatRoom list')
                }


                //참여방 목록이 비어있으면 -> 이 방 id만 저장
                if(joinedRoomList_retrieved == null || joinedRoomList_retrieved == ''){

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
        socket.on('join room', (room_db_id, room_name, room_description, image_number) => {


            //해당 사용자의 정보(참여방 목록)을 가져온다
            var joinedRoomList_retrieved='';
            connection.query('SELECT * FROM novelProject_userInfo WHERE id = ?', [socket.db_id], function(err, result) {
                if (err) {
                    console.log(err);
                }

                //참여방 목록만 추출한다
                joinedRoomList_retrieved = result[0].joinedChatRooms;
                console.log("joinedRoomList:"+joinedRoomList_retrieved);


                //db 조회/업데이트 하는 부분을 분리하지 않은 이유(코드 지저분한 이유)
                //: db조회가 느림(?) -> 코드가 순차적으로 실행되지 않음;; -> 플래그가 안 먹힘
                var isNewRoom = true; //사용자가 이미 이 방에 참여중인지 확인하는 변수

                if(joinedRoomList_retrieved == null || joinedRoomList_retrieved == ''){//참여방 목록이 비어있으면 -> 이 방 id만 저장

                    console.log(socket.username+' has never joined any chat.');

                    connection.query('UPDATE novelProject_userInfo SET joinedChatRooms = ? WHERE id = ?', [room_db_id, socket.db_id], function(err, result){
                        // console.log("mysql update_responded: "+result);
                    });

                }else{ //참여방 목록에 이미 값이 있으면 -> 중복 확인
                    console.log(socket.username+' has joined some chats.');

                    var userInfo_result = joinedRoomList_retrieved.split(';');

                    console.log('Checking if '+socket.username+' is new to this room..');
                    for(var j=0; j<userInfo_result.length; j++){

                        if(userInfo_result[j]==room_db_id){
                            isNewRoom = false;
                            console.log(socket.username+' is not new to this room. isNewRoom='+isNewRoom);
                        }
                    }


                    var new_joinedRoom = '';
                    if(isNewRoom){ // 사용자가 이 방에 새로 입장하는 것이라면 -> 방 db 업데이트, html 코드 지정

                        console.log(socket.username+' is new to this room. isNewRoom='+isNewRoom);
                        connection.query('UPDATE novelProject_userInfo SET joinedChatRooms = ? WHERE id = ?', [joinedRoomList_retrieved+';'+room_db_id, socket.db_id], function(err, result){
                            // console.log("mysql update_responded: "+result);
                        });


                        //이 방의 db 업데이트
                        connection.query('UPDATE novelProject_chatInfo SET numberOfMembers = numberOfMembers +1  WHERE id = ?', [room_db_id], function(err, result){
                            if (err) {
                                console.log(err);
                            }
                            // console.log("mysql update_responded - numberOfMembers+1: "+result);
                        });



                        console.log('image_number='+image_number);
                        new_joinedRoom = '<div class="chat_list">\n' +
                            '                        <div class="chat_people">\n' +
                            '                            <div class="chat_img"><img id="room_image" src="/'+image_number+'" style="border-radius:100px; width: 30px; height: 30px"> </div>\n' +
                            '                            <div class="chat_ib">\n' +
                            '                                <h5 id="room_name">'+room_name+'<span class="chat_date"></span></h5>\n' +
                            '                                <p id="description">'+room_description+'</p>\n' +
                            '                            </div>\n' +
                            '                            <input type="hidden" id="room_data" data-room_name="'+room_name+'" data-description="'+room_description+'" data-room_db_id="'+room_db_id+'"  data-room_image="'+image_number+'">\n'+
                            '                        </div>\n' +
                            '                    </div>';


                    }else{//이미 가입한 방에 다시 들어가는 것이라면

                        new_joinedRoom = 'old';
                    }

                    console.log('Finale) isNewRoom='+isNewRoom+' / new_joinedRoom='+new_joinedRoom);

                    //이사람의 id, 닉네임, room_db_id, 새로 참여하는 방의 html 코드를 전송한다
                    io.to(socket.id).emit('info for join', socket.db_id, socket.username, room_db_id, new_joinedRoom);

                }

            }); //db

        }); //socket.on 닫음

    }




    //사용자의 접속이 해제 되었을 때 = 해당 페이지를 나감
    socket.on('disconnect', () => {

        if(socket.room_id!=null){

            console.log(socket.username+' id disconnected from the room ('+socket.room_id+')');
            //방을 떠난다
            socket.leave(socket.room_id);

            //이 메시지를 띄우면, 퇴장메시지가 안뜸. 그래서 주석처리 함
            // //메시지를 화면에 띄운다(안 읽고있음) @@퇴장메시지 아님!!@@
            // var leave_message= socket.username+" is not reading messages.";
            // //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
            // socket.broadcast.to(socket.room_id).emit('user disconnected', leave_message);

        }else{
            console.log(socket.username+' id disconnected from the lobby');
        }

    });



    //사용자가 특정 방에서 퇴장한다는 알림을 받음
    socket.on('leave room', (room_db_id) => {


        //1. 서버 배열에서 이 사용자 정보 삭제
        var individual_roomInfo = roomInfo[room_db_id];
        console.log('Before: '+individual_roomInfo);

        console.log('numberOfMembers before update='+individual_roomInfo.length);


        var index='';
        Object.keys(individual_roomInfo).forEach(function(key) {
            var row = individual_roomInfo[key];

            console.log('individual_roomInfo['+key+'].user_id='+row.user_id);

            if(row.user_id==socket.db_id){
                index = key;
                console.log('found the key: '+index);
            }
        });

        individual_roomInfo.splice(index, 1); //이 배열에서, index 부터 1개 원소를 지운다


        console.log('updated numberOfMembers='+individual_roomInfo.length);



        //2. db 업데이트 - user db, chat db

            //2-1. user db 업데이트
            var updated_joinedChatRooms_string='';

            //사용자가 참여중인 채팅방 목록 가져오기
            connection.query('SELECT * FROM novelProject_userInfo WHERE id = ?', [socket.db_id], function (error, results) {

                var joinedChatRooms_array = '';
                try{

                    //사용자 정보 중에서 참여중인 방 목록만 가져와서, 배열로 만든다
                    joinedChatRooms_array = results[0].joinedChatRooms.split(';');
                    console.log('joinedChatRooms_string='+results[0].joinedChatRooms);
                    console.log('joinedChatRooms_array='+joinedChatRooms_array);

                    //배열에서 이 방을 삭제한다
                    for(var n=joinedChatRooms_array.length; n>0; n--){
                        if(joinedChatRooms_array[n] == room_db_id || joinedChatRooms_array[n] == ''){
                            console.log('JoinedChatRooms_array['+n+']'+joinedChatRooms_array[n]+'/ room_db_id='+room_db_id);
                            delete joinedChatRooms_array[n];
                        }
                    }

                    //이 배열을 다시 string 형태로 만든다
                    //join하고 나면 맨 뒤에 ;가 붙어있다. 이것을 없애준다
                    var updated_joinedChatRooms_string_before = joinedChatRooms_array.join(';');
                    console.log('updated_joinedChatRooms_string_before='+updated_joinedChatRooms_string_before);

                    var lastChar = updated_joinedChatRooms_string_before.charAt(updated_joinedChatRooms_string_before.length-1);

                    if(lastChar == ';'){
                        updated_joinedChatRooms_string = updated_joinedChatRooms_string_before.slice(0, updated_joinedChatRooms_string_before.length-1);
                    }
                    console.log('updated_joinedChatRooms_string='+updated_joinedChatRooms_string);



                    //수정된 참여방 목록을 사용자 db에 저장한다
                    connection.query('UPDATE novelProject_userInfo SET joinedChatRooms = ? WHERE id = ?', [updated_joinedChatRooms_string, socket.db_id], function(err, result){
                        if(err){
                            console.log(err);
                        }
                    });

                }catch (e) {
                    console.log('error occured:'+e);
                }

            });


            // 2-2. chat db 업데이트
            console.log('about to update chat db. updated numberOfMembers='+individual_roomInfo.length);
            if(individual_roomInfo.length == 0){//최후의 1인이 떠났으면 -> 서버객체, chat db, 클라이언트 방 목록 업데이트

                console.log('roomInfo key length before update='+Object.keys(roomInfo).length);

                //2-2-1. 서버 객체에서 이 방을 삭제
                delete roomInfo[room_db_id];
                console.log('roomInfo updated. There shouldnt be a room with id '+room_db_id+'. roomInfo key length: '+Object.keys(roomInfo).length);

                //2-2-2. chat db에서 이 방을 삭제
                connection.query('DELETE FROM novelProject_chatInfo WHERE id = ?', [room_db_id], function(err, result) {
                });

                //2-2-3. 방 목록(all rooms; 오른쪽 방 목록)에서 이 방을 삭제하라고 모든 클라이언트에게 메시지 보내기
                io.emit('delete room', room_db_id);


            }else{ //아직 이 방에 사람이 남아있으면 -> 사람 수만 -1 해준다

                connection.query('UPDATE novelProject_chatInfo SET numberOfMembers = numberOfMembers -1 WHERE id = ?', [room_db_id], function(err, result){
                    if(err){
                        console.log(err);
                    }
                });

            }



        //3. 남은 사람들에게 퇴장 메시지 보여주기
        var leave_message= socket.username+" left the room.";
        //나를 제외한 그룹멤버에게 이 이벤트를 전달한다
        socket.broadcast.to(room_db_id).emit('user left', leave_message);


        //4. 채팅방을 닫는다 - 이 방 사람들 모두에게 방을 닫으라고 시킴
        //브라우저는 브라우저에서 가지고있는 user id == leave를 누른 사용자의 user id(=socket.db_id) 인지 확인해야함
        socket.broadcast.to(room_db_id).emit('close window', socket.db_id);


        // 중요) 채팅 팝업창을 종료해서 chat 소켓을 종료시켜야됨!!
        // socket.leave(socket.room_id); - 이 소켓=로비 소켓. 로비소켓을 종료시키면 안됨
    });


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


//1~n까지 수 중에서 랜덤 숫자를 반환
function getRandomNumber(maxNum){
    var result = Math.floor(Math.random() * maxNum) + 1;
    return result+'.jpg';
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