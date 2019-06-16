<?php

    require_once '../session.php';
    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../log/log.php';

if(isset($_POST['signup_btn'])){

    $username = ($_POST['username']);
    $email = ($_POST['email']);
    $password = ($_POST['password']);

    global $db;
    $sql = "SELECT*FROM novelProject_userInfo WHERE email='$email'";
    $result = mysqli_query($db, $sql);

//            mysqli_num_rows() : 행의 개수
    if(mysqli_num_rows($result) == 1){
        //사용자가 입력한 email과 같은 것이 이미 있다는 뜻

        //세션에 오류 내역을 저장한다
        $_SESSION['signup_error'] = "invalid email";

    }else{

        //행의 개수가 0이다 = 해당 email을 가진 데이터가 없다
        //-> create user
        $hash = md5($password);

//                $password = md5($password); //hash password before storing for security purposes
        $sql_info = "INSERT INTO novelProject_userInfo(email, password, username)VALUES('$email','$hash','$username')";
        mysqli_query($db, $sql_info);
        $_SESSION['message'] = "You are logged in";

        //사용자의 이름과 이메일을 세션에 저장한다
        $_SESSION['user'] = $username;
        $_SESSION['email'] = $email;

        header("location: ../index.php"); //redirect to home page
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

    <title>Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin/signin.css" rel="stylesheet">

<!--    JQUERY-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>



</head>

<body class="text-center">
<form class="form-signin" method="post" action="register.php">
    <h1 class="h3 mb-3 font-weight-normal">Sign Up</h1>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" name="email" id="email" class="form-control" style="margin-bottom: 10px;" placeholder="Email address" required autofocus>

    <div>
<!--        <div class="alert alert-success" id="email-alert-success">The email is valid.</div>-->
        <?php
        //세션에 회원가입 에러 기록이 있으면(중복된 이메일), 오류메시지를 띄워준다
        if(isset($_SESSION['signup_error'])){
            echo'<div class="alert alert-danger" id="email-alert-danger">The email already exists.</div>';

            //메시지를 보여줬으니 세션에서 오류내역을 삭제한다(다음에 이 메시지가 또 뜨면 안됨)
            unset($_SESSION['signup_error']);
//            push_log(var_dump($_SESSION));
        }
        ?>
    </div>

    <label for="inputUsername" class="sr-only">Your name</label>
    <input type="text" name="username" class="form-control" style="margin-bottom: 10px;" placeholder="Username" required autofocus>

    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required autocomplete="true">

    <label for="inputPassword_check" class="sr-only">Password check</label>
    <input type="password" name="password_check" id="password_check" class="form-control" placeholder="Type the password again" required autocomplete="true">

    <div style="margin-bottom: 50px">
        <div class="alert alert-success" id="alert-success">Passwords match.</div>
        <div class="alert alert-danger" id="alert-danger">The two passwords do not match.</div>
    </div>


    <button class="btn btn-lg btn-info btn-block" name="signup_btn" id="signup_btn" value="signup_btn" type="submit" style="margin-bottom: 20px" disabled="disabled">Sign up</button>
    <button class="btn btn-lg btn-secondary btn-block" onclick="location.href='login.php'">Sign in</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>

<script>

    $(document).ready(function(){

        // $("#email-alert-success").hide();
        // $("#email-alert-danger").hide();

        $("#alert-success").hide();
        $("#alert-danger").hide();

        $("#password_check").keyup(function(){
            var pwd1=$("#password").val();
            var pwd2=$("#password_check").val();

            if(pwd1 != "" && pwd2 != ""){
                if(pwd1 == pwd2){
                    $("#alert-success").show();
                    $("#alert-danger").hide();
                    $("#signup_btn").removeAttr("disabled");
                }else{
                    $("#alert-success").hide();
                    $("#alert-danger").show();
                    $("#signup_btn").attr("disabled", "disabled");
                }
            }else{
                $("#alert-success").hide();
                $("#alert-danger").hide();

            }
        });

        $("#email").keydown(function(){
            $("#email-alert-danger").hide();
        });


        // $("#signup_btn").click(function() {
        //     var email_input = $("#email").val();
        //     var pwd=$("#password").val();
        //
        //     //input에 required 속성을 넣었기 때문에, 여기서 빈값 검사는 하지 않는다
        //
        //     var isEmailValid='';

        //     $.ajax({
        //         type: 'post',
        //         url: 'email_check.php',
        //         data: {
        //             'register': 1,
        //             'email': email_input,
        //             'password': pwd
        //         },
        //         success: function (data) {
        //             isEmailValid = data;
        //
        //             if (isEmailValid == false) {
        //                 $("#email-alert-danger").show();
        //             } else if(isEmailValid == true){
        //                 $("#email-alert-success").show();
        //                 //로그인 화면으로 넘어가기
        //                 // window.location.href = 'http://192.168.133.131/novel_project/login/login.php';
        //             }else{
        //                 $('#email').val('hahaha');
        //             }
        //         }
        //     });
        //
        // });


    });

</script>

</body>

</html>