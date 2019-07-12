<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';

global $db;
accessLog();

//게시판 이름을 get 방식으로 전달받는다
$board_name = $_GET['board'];


//db에 저장된 베스트셀러 분야를 가져온다
$sql_genre = "SELECT*FROM novelProject_boardInfo WHERE name='bestseller'";

$result_genre = mysqli_query($db, $sql_genre);

$genre_string='';
if(mysqli_num_rows($result_genre)==1){
    $row_genre = mysqli_fetch_array($result_genre);
    $genre_string = $row_genre['category'];
}




if(isset($_POST['signout_btn'])) {

    $email = $_SESSION['email'];
    push_log($email . " sign out");

    //해당 사용자의 db정보를 수정한다
    global $db;
    $query_deleteInfo = "UPDATE novelProject_userInfo SET session_id=null WHERE email='$email'";
    mysqli_query($db, $query_deleteInfo);

    $_SESSION = array(); //세션 변수 전체를 초기화한다

    //자동로그인 상태면 -> 세션 아이디가 저장된 쿠키 해제
    if($_COOKIE['session_id']){
        setcookie("session_id", "", time(), "/"); //만료시각=지금시각
    }

    echo "<script>alert(\"Bye! \");</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!--    stylesheets-->
    <link rel="stylesheet" href="../css/write/items.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>ReadMe | <?php echo $board_name?></title>
</head>
<body>


    <div class="container">
        <header class="blog-header py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-6" style="font-size: 30px; font-family: Times New Roman;">
                    <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | <?php echo $board_name?>
                </div>

                <div>

                    <?php
                    if(isset($_SESSION['email'])){
                        echo '
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    '.$_SESSION['user'].'
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="page_MyStories.php">My Stories</a>
                                    <a class="dropdown-item" href="myPage.php">Library</a>
                                    <form method="post" action=""><button class="dropdown-item" name="signout_btn" value="true">Sign-out</button></form>
                                </div>
                            </div>
                            ';
                    }else{
                        echo '<button class="btn btn-outline-secondary" onclick="location.href=\'../login/login.php\'" style="margin-right: 20px">Sign-in</button>';
                    }
                    ?>

                </div>
            </div>
        </header>

    </div>

    <main role="main" style="margin-top: 30px">

        <section class="jumbotron text-center bg-dark">
            <div class="container">
                <h1 class="jumbotron-heading" style="margin-bottom: 20px; color: lightgrey">Find out the Trend of Published Writings</h1>
                <p class="lead" style="color:gainsboro">The New York Times Best Sellers of the Week :</p>
                <p class="lead" style="color:gainsboro">Authoritatively ranked lists of books sold in the United States, sorted by format and genre.</p>
            </div>
        </section>

        <div class="album py-5">
            <div class="container">

                <?php

                //string으로 이어서 가져온 장르를 개별로 분할하여 출력한다
                $genre_array = explode(';', $genre_string);

                for ($i = 0; $i < count($genre_array); $i++) {

                    $genre = $genre_array[$i];

                echo '<h1 style="margin-top: 40px; margin-bottom: 20px">'.$genre.'</h1>
                      <div class="row">';

                $today = date('w'); //오늘 요일을 숫자로 나타낸다 (월~일 = 1~7)

                    //금주 베스트셀러 목록을 가져온다
                    //월요일에 크롤링을 하기 때문에, interval을 today(=정수)로 설정하면 월요일~오늘까지의 자료를 가져올 수 있다
                    $sql_book = "SELECT*FROM novelProject_bestseller WHERE listed_date BETWEEN DATE_SUB(now(), INTERVAL ".$today." DAY) AND NOW()";
                    $result_book = mysqli_query($db, $sql_book);

                    while($row_book = mysqli_fetch_array($result_book)){

                        if($genre == $row_book['genre']){
                            echo 'blog_test_data
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm" style="height: 530px;">
                                    <img src="'.$row_book['img_src'].'" width="160" height="250" background="#55595c" color="#eceeef" text="Thumbnail"
                                    style="margin:0px auto; padding-top: 20px"/>
                                    <div class="card-body">
                                       <div style="font-size: 18px; font-family: Times New Roman; margin-bottom: 10px;">'.$row_book['title'].'</div>
                                       <p class="card-text">'.$row_book['author'].'</p>
                                        <p class="card-text">'.$row_book['description'].'</p>
                                        <div class="d-flex justify-content-between align-items-center">

                                        </div>
                                    </div>
                                </div>
                            </div>
                           ';
                        }
                    }

                echo '</div>';

                } ?>


            </div>
        </div>

    </main>
    <!--        스크롤 맨 위로 올리는 버튼-->
    <div class="gotop" style="position: fixed; bottom: 50px; right: 50px">
        <a href class="btn btn-outline-info my-2 my-sm-0">Top</a>
    </div>

</body>
<script>
    $(document).ready(function () {

        //스크롤 맨 위로
        var speed = 100; // 스크롤속도
        $(".gotop").css("cursor", "pointer").click(function()
        {
            $('body, html').animate({scrollTop:0}, speed);
        });

    });
</script>

</html>