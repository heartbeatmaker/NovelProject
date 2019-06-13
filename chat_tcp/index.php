<?php
//고유 코드를 생성하는 함수
function generateRandomString($length = 10){
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $characterLength = count($characters);
    $randomString = '';

    for($i = 0; $i < $length; $i++){
        //rand(최소값, 최대값)
        $randomString .= $characters[mt_rand(0, $characterLength -1)];
    }
    return $randomString;
}

?>

<html>
<head>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet"/>
    <link href="../css/chat_tcp/chat_bootstrap.css" type="text/css" rel="stylesheet"/>
    <link href="../css/chat_tcp/modal.css" type="text/css" rel="stylesheet"/>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>
<body>
<br><br>
<div class="container">
<!--    <h3 class=" text-center">Chat</h3>-->
    <div class="messaging">
        <div class="inbox_msg">
            <!--inbox_msg는 방목록이랑 방이랑 한 페이지에 함께 보여주기 위해 사용한 div임 지금은 굳이 필요x-->

            <div class="inbox_people">
                <div class="headind_srch">
                    <div style="text-align: right">
                        <button class="new_room_btn" type="button" onclick="openForm()"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </button>
                    </div>
                    <div class="recent_heading">
                        <h4>Open Chat</h4>
                    </div>
                    <div class="srch_bar">
                        <div class="stylish-input-group">
                            <input type="text" class="search-bar"  placeholder="Search" >
                            <span class="input-group-addon">
                            <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button></span>
                        </div>
                    </div>
                </div>

                <div class="inbox_chat">

                    <div class="chat_list active_chat" ondblclick="openJoinForm()">
                        <div class="chat_people">
                            <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                            <div class="chat_ib">
                                <h5>재밌게 놀아요<span class="chat_date">Dec 25</span></h5>
                                <p>#청주 #여행 #게임 #소모임</p>
                            </div>
                        </div>
                    </div>

                    <div class="chat_list" ondblclick="openJoinForm()">
                        <div class="chat_people">
                            <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                            <div class="chat_ib">
                                <h5>하하하하하 웃기는 모임<span class="chat_date">Dec 25</span></h5>
                                <p>#유머 #재미 #코미디 #연기 #모임</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

<!--        <p class="text-center top_spac"> Design by <a target="_blank" href="#">Sunil Rajput</a></p>-->

    </div></div>

<!--모달창-->
    <div class="form-popup" id="form_newRoom">
        <form action="" method="post" class="form-container">
            <h3>Create a Room</h3>

            <label for="room_name"><b>Room Name</b></label>
            <input type="text" placeholder="" name="room_name" id="room_name" required>

            <label for="description"><b>Description</b></label>
            <input type="text" placeholder="" name="description" id="description" required>

            <label for="max_num"><b>Max number of Participants</b></label>
            <input type="number" placeholder="" name="max_num" id="max_num"  required>

            <label for="username"><b>Your nickname</b></label>
            <input type="text" placeholder="" name="username" id="master_username" required>

            <button type="submit" id="btn_submit" class="btn">Submit</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
    </div>

    <div class="form-popup" id="form_joinRoom">
        <form action="" class="form-container">
            <h3>Join the Chat</h3>

            <label for="username"><b>Your name</b></label>
            <input type="text" placeholder="" name="username" id="username" required>

            <button type="submit" id="join_btn" class="btn">Join</button>
            <button type="button" class="btn cancel" onclick="closeJoinForm()">Cancel</button>
        </form>
    </div>


    <script>
        //모달창 열고 닫음
        function openForm() {
            document.getElementById("form_newRoom").style.display = "block";
        }

        function closeForm() {
            document.getElementById("form_newRoom").style.display = "none";
        }

        function openJoinForm() {
            document.getElementById("form_joinRoom").style.display = "block";
        }

        function closeJoinForm() {
            document.getElementById("form_joinRoom").style.display = "none";
        }
    </script>

    <script>
        $(document).ready(function(){

            $(document).on('click', '#join_btn', function() {
                var username = $('#username').val();

                window.open('room.php?username='+username,'<?php echo generateRandomString()?>', 'width=520,height=600,location=no,status=no,scrollbars=yes');
            });

            //사용자가 POST 버튼을 클릭했을 때, ajax를 호출한다
            $(document).on('click', '#btn_submit', function(){

                var room_name = $('#room_name').val();
                var description = $('#description').val();
                var master_nickname = $('#master_username').val();

                //ajax: 서버에 요청을 하는 방식 중 하나
                //서버와 비동기적으로 통신한다. 비동기: 서버와 통신하는 동안 다른 작업을 할 수 있다는 의미
                //jquery에서 ajax() 함수를 사용하면 편리하게 서버와 통신할 수 있다
                $.ajax({
                    url: 'server.php', //서버측에서 가져올 페이지
                    type: 'POST',//통신타입 설정. GET 혹은 POST. 아래의 데이터를 post 방식으로 넘겨준다.
                    data: {//서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                        'create_room': 1,
                        'room_name': room_name,
                        'description': description,
                        'master_nickname': master_nickname
                    },

                    //http 요청 성공 시 발생하는 이벤트
                    success: function(response){
                        //데이터 처리 후 -> 입력창 초기화하고, 모달창 닫음
                        $('#room_name').val('');
                        $('#description').val('');
                        $('#master_username').val('');
                        closeForm();

                        //response=서버에서 받아온 데이터=$saved_comment=추가된 댓글
                        //response를 display_area(=전체 댓글 div)의 마지막에 붙인다
                        $('.inbox_chat').append(response);
                    }
                });
            });
        });
    </script>


</body>
</html>