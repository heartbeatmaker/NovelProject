<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';

//게시판 이름을 get 방식으로 전달받는다
$board_name = $_GET['board'];



if(isset($_POST['signout_btn'])) {

    $email = $_SESSION['email'];
    push_log($email . " sign out");

    //해당 사용자의 db정보를 수정한다
    global $db;
    $query_deleteInfo = "UPDATE novelProject_userInfo SET session_id=null WHERE email='$email'";
    mysqli_query($db, $query_deleteInfo);

    $_SESSION = array(); //세션 변수 전체를 초기화한다

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

                <form class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <a class="text-muted" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                    </a>
                </form>
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Write
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="page_CreateNewStory.php">Create a New Story</a>
                            <a class="dropdown-item" href="page_MyStories.php">My Stories</a>
                        </div>
                    </div>
                    <?php
                    if(isset($_SESSION['email'])){
                        echo '
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    '.$_SESSION['user'].'
                                </button>
                                <div class="dropdown-menu">
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

        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Trends of Published Writings</h1>
                <p class="lead text-muted">The New York Times Best Sellers: Authoritatively ranked lists of books sold in the United States, sorted by format and genre.</p>
            </div>
        </section>

        <div class="album py-5">
            <div class="container">

                <div class="row">

                    <?php
                    for($i=0; $i<55; $i++){

                        echo '
                        <div class="col-md-3">
                            <div class="card mb-4 shadow-sm" >
                                <img  width="100%" height="225" background="#55595c" color="#eceeef" class="card-img-top" text="Thumbnail"/>
                                <div class="card-body">
                                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </div>
                                        <small class="text-muted">9 mins</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                    }

                    ?>


                </div>
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