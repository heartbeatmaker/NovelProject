<?php
//require_once 'session.php';
//require_once 'log/log.php';
//
//if(isset($_POST['signout_btn'])){
//
//    $email = $_SESSION['email'];
//    push_log("email=".$_SESSION['email'] , "index logout");
//
//    //해당 사용자의 db정보를 수정한다
//    $query_deleteInfo = "UPDATE userinfo SET session_id=null WHERE email='$email'";
//    mysqli_query($db, $query_deleteInfo);
//
//    $_SESSION = array(); //세션 변수 전체를 초기화한다
//
//    echo "<script>alert(\"로그아웃 되었습니다\");</script>";
//
//?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

    <title>saetsetu</title>
</head>
<body>

    <div class="wrapper">

        <div class="content_wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-secondary" style="height:60px">
<!--                <a class="navbar-brand" href="#">Navbar</a>-->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ss= class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item active" style="padding-right: 20px; padding-left: 20px">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item" style="padding-right: 20px">
                            <a class="nav-link" href="portfolio/portfolio.php">Portfolio</a>
                        </li>
                        <li class="nav-item" style="padding-right: 20px">
                            <a class="nav-link" href="boards/main.php">Blog</a>
                        </li>
                        <li class="nav-item dropdown" style="padding-right: 25px">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Contact
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/contact/contact.php">E-mail</a>
                                <a class="dropdown-item" href="/contact/chat.php">Chat</a>
                            </div>
                        </li>

                        <li>
                        <?php
                        if(isset($_SESSION['user'])){
                            echo '<form action="" method="post"><button name="signout_btn" class="login_button">Sign-out</button></form>';
                        }else{
                            echo '<a class="nav-link" href="/login/login.php">Sign-in</a>';
                        }
                        ?>
                        </li>

                    </ul>
                </div>
            </nav>


            <div class="main_content_wrapper">
                <div>
                    <div class="profile_wrapper">
                        <img src="/images/me.jpg" style="width:180px; height:180px; border-radius: 100px">
                    </div>
                    <div class="profile_wrapper">
                        <br><br>
                        <h2>aaa</h2>
                        <h5>Sofseeasaeper</h5>
                    </div>
                    <div class="profile_wrapper">
                        <div class="button" onclick="location.href='contact/contact.php'"><span>E-MAIL</span></div>
                        <br><br>
<!--                        <div class="button" onclick="window.open('http://192.168.133.131:3000','CHAT WITH ME','width=430,height=500,location=no,status=no,scrollbars=yes')"><span>CHAT</span></div>-->
                        <div class="button" onclick="location.href='contact/chat.php'"><span>CHAT</span></div>
                    </div>
                </div>
            </div>

            <br><br><br><br><br><br><br><br><div class="line"></div><br>

            <div class="main_content_wrapper">

                <h2>About Me</h2>
                <br>
                <p>I am a&nbsp;<strong>full-stack software developer</strong>&nbsp;with&nbsp;<strong>four months of experience</strong>.</p>
                <p>I have maintained and developed multiple projects from scratch, carrying the development of its' back-end and front-end code bases.</p>
                <p>My current toolset includes&nbsp;<strong><span class="red">Java &amp; Android &amp; PHP &amp; JAVASCRIPT</span></strong>&nbsp;and all the other various frameworks, libraries and technologies related to them.</p>
                <p>Feel free to ask me any questions. I can help you in your project in all from the UI mockups, back-end and front-end development to fixing the design and installing &amp; configuration of the application on staging/production environments.</p>

            </div>

            <div class="line"></div><br>

            <div class="main_content_wrapper">

                <h2>Latest Projects</h2>
                <div>
                    <br><br>
                    <div class="profile_wrapper">
                        <video src="/videos/swear.mp4" controls style="height:300px; width:200px"></video>
                        <h4><br>SWEAR</h4>
                    </div>
                    <div class="profile_wrapper">
                        <video src="/videos/miniGame_with_music.mp4" controls style="height:300px; width:200px"></video>
                        <h4><br>SAVING THE PIG</h4>
                    </div>
                    <div class="profile_wrapper">
                        <video src="/videos/weather.mp4" controls style="height:300px; width:200px"></video>
                        <h4><br>WEATHER</h4>
                    </div>
                </div>
                <div>
                    <br><br>
                    <div class="video_wrapper">
                        <ul>
                            <li><strong>Freelance Project</strong> - A commitment platform created to help people achieve their goals and interact with people with similar interests.</li>
                            <li><strong>Technology Stack</strong> - Java, Android Platform, APIs</li>
                            <li><strong>Team Size - 1</strong></li>
                        </ul>
                    </div>
                    <div class="video_wrapper">
                        <ul>
                            <li><strong>Freelance Project</strong> - A game where users have fun to save a pig from flooding danger.</li>
                            <li><strong>Technology Stack</strong> - Java, Android Platform</li>
                            <li><strong>Team Size - 1</strong></li>
                        </ul>
                    </div>
                    <div class="video_wrapper">
                        <ul>
                            <li><strong>Freelance Project</strong> - An app which provides users with accurate, real-time weather information globally. </li>
                            <li><strong>Technology Stack</strong> - Java, Android Platform, APIs</li>
                            <li><strong>Team Size - 1</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <br><br><br>
    <div class="line"></div>
    <br><br><br>
    <footer style="text-align: center;">
    </footer>
    <br><br>
</body>
</html>