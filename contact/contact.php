<?php require_once'../connect.php';
if(isset($_POST['signout_btn'])){

    $email = $_SESSION['email'];

    //해당 사용자의 db정보를 수정한다
    $query_deleteInfo = "UPDATE userinfo SET session_id=null WHERE email='$email'";
    mysqli_query($db, $query_deleteInfo);

    $_SESSION = array(); //세션 변수 전체를 초기화한다

    echo "<script>alert(\"로그아웃 되었습니다\");</script>";

}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>

    <title>Software Developer YonJu</title>
</head>
<body>

<div class="wrapper">

    <div class="content_wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary" style="height:60px">
            <!-- <a class="navbar-brand" href="#">Navbar</a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active" style="padding-right: 20px; padding-left: 20px">
                        <a class="nav-link" href="../home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="../portfolio/portfolio.php">Portfolio</a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="../reply_test/main.php">Blog</a>
                    </li>
                    <li class="nav-item dropdown" style="padding-right: 25px">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Contact
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">E-mail</a>
                            <a class="dropdown-item" href="chat.php">Chat</a>
                        </div>
                    </li>
                    <?php
                    if(isset($_SESSION['user'])){
                        echo '<form action="" method="post"><button name="signout_btn" class="login_button">Sign-out</button></form>';
                    }else{
                        echo '<a class="nav-link" href="/login/login.php">Sign-in</a>';
                    }
                    ?>
                </ul>
            </div>
        </nav>


        <div class="container">
            <h1>Contact Me</h1>
            <br><br>
<!--            <form class="contact-form" action="contact_form.php" method="post"> ajax 사용 안했을 때-->
            <form class="contact-form" method="post" action="contact_form.php">
                <input id="form_name" style="width:50%; float:left; padding:10px" type="text" name="name" placeholder="Name" required="required">
                <input id="form_email" style="width:50%; float:left; padding:10px" type="text" name="mail" placeholder="E-Mail" required="required">
                <input id="form_subject" style="width:100%; padding:10px" type="text" name="subject" placeholder="Subject" required="required">
                <textarea id="form_message" rows="10" style="width:100%; padding:10px" name="message" placeholder="Message" required="required"></textarea>
                <br><br>
                <button id="submit_button" style="width:20%; padding:10px; float:right" type="submit" name="submit">SEND</button>
            </form>
        </div>


    </div>

</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!--ajax 사용할 때 필요한 부분-->
<!--<script src="script.js"></script>-->
<!--<script src="../reply_test/jquery.min.js"></script>-->
<br><br><br>
<div class="line"></div>
<br><br><br>
<footer style="text-align: center;">
</footer>
<br><br>
</body>
</html>

