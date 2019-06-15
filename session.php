<?php
    //자동로그인 여부 확인해서 세션에 저장하는 코드

    require_once '/usr/local/apache/security_files/connect.php'; //db연결
    require_once 'log/log.php';

    //사용자가 로그인 시 자동로그인을 설정하면, 쿠키에 session id를 7일간 저장한다.
    // db의 userinfo 테이블에 에 해당사용의 자동로그인 여부를 적어놓는다 session_id를 가졌다고 표시해놓는다
    //session_id라는 쿠키가 있다 = 사용자가 자동로그인을 설정했다
    if(isset($_COOKIE['session_id'])){

        //db에  세션 아이디를 가진 사용자가 있는지 확인한다
        $session_id = $_COOKIE['session_id'];
        $sql_checkAutoLogin = "SELECT*FROM novel_userinfo WHERE session_id='$session_id'";

        global $db;
        $result = mysqli_query($db, $sql_checkAutoLogin);

        //존재한다면, 그 사용자의 이름과 이메일을 세션에 추가한다
        if(mysqli_num_rows($result)==1){
            $row = mysqli_fetch_array($result);
            $session_id_retrieved = $row['session_id'];

            if($session_id_retrieved == $session_id){//세션id가 일치하는지 확인
                $_SESSION['user'] = $row['username'];
                $_SESSION['email'] = $row['email'];
            }
        }

//        echo "user=".$user," / cookie_pw=".$_COOKIE['user_pw_cookie']," / password=".$password," / session_user=".$_SESSION['user'];

    } else { //자동로그인 설정 안 했을 경우

        session_start();
//        $user = $_SESSION['user'];
//        $email = $_SESSION['email'];

        push_log('user='.$_SESSION['user']);
        push_log('email='.$_SESSION['email']);

    }

?>
