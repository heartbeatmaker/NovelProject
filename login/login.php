<?php

require_once '../session.php';
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../log/log.php';

if(isset($_POST['signin_btn'])){

    $email = ($_POST['email']);
    $password = ($_POST['password']);

//    $master_key = "admin";
    $hash = md5($password);


//    $password = md5($password);
    global $db;
    $sql = "SELECT*FROM novelProject_userInfo WHERE email='$email' AND password ='$hash'";
    $result = mysqli_query($db, $sql);

    //해당하는 id, password가 있으면
    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_array($result);

        $username = $row['username']; //username을 불러온다

        $_SESSION['message'] = "You are now logged in";

        //사용자의 이름과 이메일을 세션 변수로 등록한다
        $_SESSION['user'] = $username;
        $_SESSION['email'] = $email;

        push_log($_SESSION['user']);

        //사용자가 자동로그인에 체크를 했다면,
        if(isset($_POST['auto_login'])){

            $session_id = session_id();
            $expiry_date = time()+3600*24*7;

        //쿠키에 session id를 저장한다. 7일간
        setcookie("session_id", $session_id, $expiry_date, "/");
        //db의 해당 아이디 row에 session id와 유효기간을 저장한다
        $sql_update = "UPDATE novelProject_userInfo SET session_id='$session_id' WHERE email='$email'";
        mysqli_query($db, $sql_update);

//            7일간 쿠키를 저장한다
//            setcookie("user_id_cookie", $email, time()+3600*24*7, "/");
//            setcookie("user_pw_cookie", $hash, time()+3600*24*7, "/");
//            push_log("email_cookie=".$email , "login-cookie");
//            push_log("hash_cookie=".$hash , "login-cookie");
        }

        header("location: ../index.php");

    }else{
        $_SESSION['login_error'] = "yes";
    }

}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sign In</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin/signin.css" rel="stylesheet">

    <!--    JQUERY-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
</head>

<body class="text-center">
<form class="form-signin" method="post" action="login.php">
  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required autocomplete="true">

    <?php
    //세션에 로그인 에러 기록이 있으면(잘못된 이메일/비밀번호), 오류메시지를 띄워준다
    if(isset($_SESSION['login_error'])){
        echo'<div class="alert alert-danger" id="alert-danger">Email or password is incorrect</div>';

        //메시지를 보여줬으니 세션에서 오류내역을 삭제한다(다음에 이 메시지가 또 뜨면 안됨)
        unset($_SESSION['login_error']);
    }
    ?>

    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" name="auto_login" value="true"> Remember me
        </label>
    </div>
    <button class="btn btn-lg btn-info btn-block" name="signin_btn" value="signin" type="submit" style="margin-bottom: 20px">Sign in</button>
    <button class="btn btn-lg btn-secondary btn-block" onclick="location.href='register.php'">New here? Sign up</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>


<script>

    $(document).ready(function(){

        $("#inputEmail").keydown(function(){
            $("#alert-danger").hide();
        });

    });

</script>


</body>

</html>