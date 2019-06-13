<?php
//AJAX 이용했을 때 쓰는 코드

    require_once '../log/log.php';

if(isset($_POST['send'])){
    push_log("result=".$result, "contact");

    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $mailFrom = $_POST['mail'];
    $message = $_POST['message'];

    $mailTo = "yonlee13@citizen.seoul.kr";
//    gmail, naver doesn't work
    $headers = "From: ".$mailFrom;
    $txt = "You have received an e-mail from ".$name.".\n\n".$message;

    $send_mail = mail($mailTo, $subject, $txt, $headers);

    if($send_mail){
        $result= "Mail sent successfully.";
    }else{
        $result="Failed to send e-mail. Please try again with another account.";
    }

    push_log("result=".$result, "contact");

    echo $result;
    exit();
}

?>