<?php

//현재 사용하지 않음. ajax로 아이디 중복 확인하려고 함


require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';

//이메일 중복체크용
if(isset($_POST['register'])){

    $email_input = $_POST['email'];

    $result_isEmailValid = ''; //같은 메일이 있는지 없는지 결과값

    global $db;
    $sql = "SELECT*FROM novelProject_userInfo WHERE email='$email_input'";
    $result = mysqli_query($db, $sql);

//            mysqli_num_rows() : 행의 개수
    if(mysqli_num_rows($result) == 1){ //사용자가 입력한 email과 같은 것이 이미 있다는 뜻
        $result_isEmailValid = false;
    }else if(mysqli_num_rows($result) == 0){ //같은 이메일이 없을 떄

        $result_isEmailValid = true;
    }else{ //오류
        $result_isEmailValid = false;
        push_log('email check error');
    }

    echo $result_isEmailValid;

    exit();

}