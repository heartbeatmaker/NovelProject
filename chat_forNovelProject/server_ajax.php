<?php
//사용x
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';


//채팅방 참여하기 submit 버튼 눌렀을 때
//클라이언트로부터 받은 요청을 처리하는 코드이다
if (isset($_POST['join_room'])) {

    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $room_db_id = $_POST['room_db_id'];

    //받은 값을 댓글 db에 저장한다
//    $query = "INSERT INTO parent (user, email, text, date, post_id) VALUES ('$name', '$email', '$comment', '$date', '$post_id')";

//    if(mysqli_query($db, $query)){

    //mysqli_insert_id: 마지막으로 삽입된 id를 반환한다. 바로 위에서 db에 값을 저장했는데, 그 row 의 id를 알고자 한 것
//        $id = mysqli_insert_id($db);

    $joined_room = '
            <div class="chat_list">
                <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="chat_ib">
                            <h5 id="room_name">'.$room_name.'<span class="chat_date">1명</span></h5>
                            <p id="description">'.$description.'</p>
                        </div>
                    <input type="hidden" id="room_data" data-room_name="'.$room_name.'" data-description="'.$description.'" data-room_db_id="'.$room_db_id.'">
                </div>
            </div>';
    echo $joined_room;// = ajax response. script.js 에서 댓글창으로 append 된다


//    }
    exit();//해당 함수가 포함된 페이지 자체를 끝낸다.
    //이거 왜 쓰는거지? : ajax response에 불필요한 코드를 추가하는 것을 막아준다
}




//클라이언트에서 요청하는 코드
//$('form').submit(() => { //form이 submit 되면(=사용자가 메시지를 입력하고 엔터를 치면)
//
//    var message = $('#msg_input').val();
//    //사용자의 메시지를 서버로 전달(emit)한다
//    // socket.emit('send message', message);
//
//    //화면에 내가 보낸 메시지를 띄워준다
//    $.ajax({
//                url: 'server_ajax.php', //서버측에서 가져올 페이지
//                type: 'POST',//통신타입 설정. GET 혹은 POST. 아래의 데이터를 post 방식으로 넘겨준다.
//                data: {//서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
//        'my_message': 1,
//                    'message': message
//                },
//
//                //http 요청 성공 시 발생하는 이벤트
//                success: function(response){
//
//        $('#msg_input').val(''); //입력창 초기화
//
//        //response=서버에서 받아온 데이터=$saved_comment=추가된 댓글
//        //response를 display_area(=전체 댓글 div)의 마지막에 붙인다
//        $('.msg_history').append(response);
//    }
//            });
//
//            return false; //이건 왜 하는거지?
//
//        });



//채팅방에서 내가 보낸 메시지를 처리하는 곳
if (isset($_POST['my_message'])) {

    $message = $_POST['message'];
    $time = date('h:i A | M j');

    $message_box = '
               <div class="outgoing_msg">
                <div class="sent_msg">
                    <p>'.$message.'</p>
                    <span class="time_date">'.$time.'</span> </div>
              </div>';
    echo $message_box;// = ajax response. script.js 에서 댓글창으로 append 된다


//    }
    exit();//해당 함수가 포함된 페이지 자체를 끝낸다.
    //이거 왜 쓰는거지? : ajax response에 불필요한 코드를 추가하는 것을 막아준다
}

