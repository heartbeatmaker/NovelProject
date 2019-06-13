<?php
//    session_start();
    //session이란 웹 사이트의 여러 페이지에 걸쳐 사용되는 사용자 정보를 저장하는 방법이다
    //사용자가 브라우저를 닫아 서버와의 연결을 끝내는 지점까지를 세션이라고 함
    //session_start() 함수: 세션 아이디가 이미 존재하는지 확인하고, 존재하지 않으면 새로운 아이디를 만든다

    require_once '../connect.php';

//브라우저가 보낸 값을 받아서, 처리하는 코드이다
    if(isset($_POST['signup_btn'])){

        $username = ($_POST['username']);
        $email = ($_POST['email']);
        $password = ($_POST['password']);
        $password2 = ($_POST['password_check']);

        if($password == $password2){

            //WHERE 다음에 조건식이 온다. 조건식에 만족하는 정보를 조회한다
            $sql = "SELECT*FROM userinfo WHERE email='$email'";
            $result = mysqli_query($db, $sql);

//            mysqli_num_rows() : 행의 개수
            if(mysqli_num_rows($result) == 1){
                //사용자가 입력한 email과 같은 것이 이미 있다는 뜻

                echo 'The email already exists';
                $_SESSION['message'] = "email or password is incorrect";
                //message라는 key를 가진 변수를 $_SESSION 배열에 등록한다. 이 내용은 서버 측에 저장된다
            }else{
                //행의 개수가 0이다 = 해당 email을 가진 데이터가 없다
                //-> create user
                $hash = md5($password);

//                $password = md5($password); //hash password before storing for security purposes
                $sql_info = "INSERT INTO userinfo(email, password, username)VALUES('$email','$hash','$username')";
                mysqli_query($db, $sql_info);
                $_SESSION['message'] = "You are logged in";

                //사용자의 이름과 이메일을 세션에 저장한다
                $_SESSION['user'] = $username;
                $_SESSION['email'] = $email;
                header("location: ../home.php"); //redirect to home page
            }

        }else{
            echo 'passwords do not match';
            //failed
            $_SESSION['message'] = "The two passwords do not match";
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

    <title>Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin/signin.css" rel="stylesheet">
</head>

<body class="text-center">
<form class="form-signin" type="post" action="">
    <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal">Sign Up</h1>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" name="email" class="form-control" style="margin-bottom: 10px;" placeholder="Email address" required autofocus>

    <label for="inputUsername" class="sr-only">Your name</label>
    <input type="text" name="username" class="form-control" style="margin-bottom: 10px;" placeholder="Username" required autofocus>

    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password" required>

    <label for="inputPassword_check" class="sr-only">Password check</label>
    <input type="password" name="password_check" class="form-control" style="margin-bottom: 50px;" placeholder="Type the password again" required>

    <button class="btn btn-lg btn-info btn-block" name="signup_btn" value="signup" type="submit" style="margin-bottom: 20px">Sign up</button>
    <button class="btn btn-lg btn-secondary btn-block" onclick="location.href='login.php'">Sign in</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>
</body>

</html>