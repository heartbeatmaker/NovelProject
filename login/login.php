<?php
//session_start();
//새로운 세션을 시작하거나, 기존의 세션을 다시 시작할 수 있다
//세션 아이디가 이미 존재하는지 확인하고, 존재하지 않으면 새로운 아이디를 만든다
//이미 존재하는 세션 아이디가 있을 때는, 원래 있던 세션 변수를 다시 불러와서 사용할 수 있도록 한다
//세션 아이디= 웹 서버에 의해 무작위로 만들어진 숫자
//어떤 헤더보다 먼저 생성해야 한다

require_once '../connect.php';
require_once '../log/log.php';

if(isset($_POST['signin_btn'])){
    $email = ($_POST['email']);
    $password = ($_POST['password']);
//    $master_key = "admin";
    $hash = md5($password);

//    $password = md5($password);
    $sql = "SELECT*FROM userinfo WHERE email='$email' AND password ='$hash'";
    $result = mysqli_query($db, $sql);

//    push_log("hash=".$hash , "login");

    //해당하는 id, password가 있으면
    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_array($result);

        push_log("password=".$row['password'] , "login");
        $username = $row['username']; //username을 불러온다

        $_SESSION['message'] = "You are now logged in";

        //사용자의 이름과 이메일을 세션 변수로 등록한다
        $_SESSION['user'] = $username;
        $_SESSION['email'] = $email;

        //사용자가 자동로그인에 체크를 했다면,
        if(isset($_POST['auto_login'])){

            $session_id = session_id();
            $expiry_date = time()+3600*24*7;
            push_log("session_id=".$session_id , "login");
            push_log("date=".$expiry_date , "login");

        //쿠키에 session id를 저장한다. 7일간
        setcookie("session_id", $session_id, $expiry_date, "/");
        //db의 해당 아이디 row에 session id와 유효기간을 저장한다
        $sql_update = "UPDATE userinfo SET session_id='$session_id' WHERE email='$email'";
        mysqli_query($db, $sql_update);


//            7일간 쿠키를 저장한다
//            setcookie("user_id_cookie", $email, time()+3600*24*7, "/");
//            setcookie("user_pw_cookie", $hash, time()+3600*24*7, "/");
//            push_log("email_cookie=".$email , "login-cookie");
//            push_log("hash_cookie=".$hash , "login-cookie");
        }

        header("location: ../index.php");
    }else{
        echo 'email or password is incorrect';
        $_SESSION['message'] = "email or password is incorrect";
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
    <link rel="icon" href="../../../../favicon.ico">

    <title>Sign In</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin/signin.css" rel="stylesheet">
</head>

<body class="text-center">
<form class="form-signin" type="post" action="">
    <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" name="auto_login" value="true"> Remember me
        </label>
    </div>
    <button class="btn btn-lg btn-info btn-block" name="signin_btn" value="signin" type="submit" style="margin-bottom: 20px">Sign in</button>
    <button class="btn btn-lg btn-secondary btn-block" onclick="location.href='register.php'">New here? Sign up</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>
</body>

</html>