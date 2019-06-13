<?php
//session_start();

require_once "../log/log.php";
//require_once "../connect.php";

date_default_timezone_set('Asia/Seoul');

//방만들기 submit 버튼 눌렀을 때
//클라이언트로부터 받은 요청을 처리하는 코드이다
if(isset($_POST['create_room'])){

    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
//    $max_num = $_POST['max_num'];
    $master_nickname = $_POST['master_nickname'];
    $date = date('Y-m-d'); //현재시각

    //받은 값을 댓글 db에 저장한다
//    $query = "INSERT INTO parent (user, email, text, date, post_id) VALUES ('$name', '$email', '$comment', '$date', '$post_id')";

//    if(mysqli_query($db, $query)){

        //mysqli_insert_id: 마지막으로 삽입된 id를 반환한다. 바로 위에서 db에 값을 저장했는데, 그 row 의 id를 알고자 한 것
//        $id = mysqli_insert_id($db);

        $saved_room = '<div class="chat_list" ondblclick="openJoinForm()">
                        <div class="chat_people">
                            <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                            <div class="chat_ib">
                                <h5>'.$room_name.'<span class="chat_date">'.$date.'</span></h5>
                                <p>'.$description.'</p>
                                <p>1명</p>
                            </div>
                        </div>
                    </div>';
        echo $saved_room;// = ajax response. script.js 에서 댓글창으로 append 된다
//    }
    exit();//해당 함수가 포함된 페이지 자체를 끝낸다.
    //이거 왜 쓰는거지? : ajax response에 불필요한 코드를 추가하는 것을 막아준다
}

?>