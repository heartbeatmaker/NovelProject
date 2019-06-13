<?php

//htdocs 상위폴더로 빼기
    $db = mysqli_connect("192.168.133.131", "root", "vhxmvhffldh@2019", "webdata")
    or die("error: could not connect to database");


//    if(isset($_COOKIE['user_id_cookie'])&& isset($_COOKIE['user_pw_cookie'])){
//        $user = $_COOKIE['user_id_cookie'];
////        $master_key = "admin";
//        $query = "SELECT*FROM userinfo WHERE email='$user'";
//        $result = mysqli_query($db, $query);
//        $row = mysqli_fetch_array($result);
//        $password = $row['password'];
//        session_start();
//
//        if($_COOKIE['user_pw_cookie']==$password){
//            $_SESSION['user'] = $row['username'];
//            $_SESSION['email'] = $user;
//        }

    if(isset($_COOKIE['session_id'])){

        //db에  세션 아이디를 가진 사용자가 있는지 확인한다
        $session_id = $_COOKIE['session_id'];
        $sql_checkAutoLogin = "SELECT*FROM userinfo WHERE session_id='$session_id'";

        $result = mysqli_query($db, $sql_checkAutoLogin);

        //존재한다면, 그 사용자의 이름과 이메일을 세션에 추가한다
        if(mysqli_num_rows($result)==1){
            $row = mysqli_fetch_array($result);
            $session_id_retrieved = $row['session_id'];

            if($session_id_retrieved == $session_id){
                $_SESSION['user'] = $row['username'];
                $_SESSION['email'] = $row['email'];
            }
        }

//        echo "user=".$user," / cookie_pw=".$_COOKIE['user_pw_cookie']," / password=".$password," / session_user=".$_SESSION['user'];
    }else {
        session_start();
        $user = $_SESSION['user'];
        $email = $_SESSION['email'];
    }


    date_default_timezone_set('Asia/Seoul');
?>
