<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once 'session.php';
require_once 'log/log.php';

//var_dump($_SESSION);

if(isset($_POST['signout_btn'])) {

    $email = $_SESSION['email'];
    push_log($email . " sign out");

    //해당 사용자의 db정보를 수정한다
    global $db;
    $query_deleteInfo = "UPDATE novel_userinfo SET session_id=null WHERE email='$email'";
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

<!--    dataTables-->
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

<!--    stylesheets-->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>ReadMe</title>
</head>
<body>
<!--    <div class="wrapper">-->
<!---->
<!--        <div class="content_wrapper">-->
<!---->
<!--            <nav class="navbar navbar-expand-lg navbar-light bg-light">-->
<!---->
<!--                buttons-->
<!--                <a class="navbar-brand" href="#" style="margin-left: 20px; margin-right: 50px">ReadMe</a>-->
<!--                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                    <span class="navbar-toggler-icon"></span>-->
<!--                </button>-->
<!--                <div class="collapse navbar-collapse" id="navbarNavDropdown">-->
<!--                    <ul class="navbar-nav">-->
<!--                        <li class="nav-item active">-->
<!--                            <a class="nav-link" href="#">Novels <span class="sr-only">(current)</span></a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="#"></a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="#">Community</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item dropdown">-->
<!--                            <a style="margin-left: 10px; margin-right: 10px" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                                Hot50-->
<!--                            </a>-->
<!--                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">-->
<!--                                <a class="dropdown-item" href="#">Daily</a>-->
<!--                                <a class="dropdown-item" href="#">Weekly</a>-->
<!--                                <a class="dropdown-item" href="#">Monthly</a>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="#">About</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!---->
<!--                search form & button-->
<!--                <form class="form-inline">-->
<!--                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">-->
<!--                    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>-->
<!--                </form>-->
<!--                <form class="form-inline" style="margin-left: 50px">-->
<!--                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Sign-in</button>-->
<!--                </form>-->
<!---->
<!---->
<!--            </nav>-->
<!---->
<!--        </div>-->
<!---->
<!--    </div>-->
<!---->
<!--    <div class="line" style="margin-top: 20px; margin-bottom: 20px; width: 100%; height: 1px;-->
<!--    border-bottom: 1px dashed #ddd;"></div>-->
<!---->
<!--    <footer style="text-align: center; margin-top: 50px">Footer</footer>-->



    <div class="container">
        <header class="blog-header py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4">
                    <a class="blog-header-logo text-dark" style="font-size: 45px; font-family: Times New Roman;" href="#">ReadMe</a>
                </div>

                <form class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <a class="text-muted" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                    </a>
                </form>
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Write
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Create a New Story</a>
                            <a class="dropdown-item" href="#">My Stories</a>
                        </div>
                    </div>
                    <?php
                    if(isset($_SESSION['email'])){
                        echo '
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                '.$_SESSION['user'].'
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">My Page</a>
                                <form method="post" action="index.php"><button class="dropdown-item" name="signout_btn" value="true">Sign-out</button></form>
                            </div>
                        </div>
                        ';
                    }else{
                        echo '<button class="btn btn-outline-secondary" onclick="location.href=\'login/login.php\'" style="margin-right: 20px">Sign-in</button>';
                    }
                    ?>

                </div>
            </div>
        </header>

        <div class="nav-scroller py-1 mb-2">
            <nav class="nav d-flex justify-content-between bg-light">
                <a class="p-2 text-muted" style="margin-left: 80px;" href="#">Fandom</a>
                <a class="p-2 text-muted" href="boards/mainPage.php">Fiction</a>
                <a class="p-2 text-muted" href="#">Non-fiction</a>
                <a class="p-2 text-muted" href="#">Community</a>
                <a class="p-2 text-muted" href="#">Hot 100</a>
                <a class="p-2 text-muted" style="margin-right: 80px;" href="#">About</a>
            </nav>
        </div>

        <div class="jumbotron p-3 p-md-5 text-white rounded bg-dark" style="margin-top: 20px; margin-bottom: 30px;
background-image: url('images/book.jpg')">
            <div class="col-md-6 px-0">
                <h1 class="display-4 font-italic" style="font-family: 'Times New Roman'">
                    Share your stories.</h1>
                <p class="lead my-3"><strong>ReadMe</strong> supports brilliant writers to share their stories and communication with readers.</p>
            </div>
        </div>

    </div>

    <main role="main" class="container">
        <div class="row">
            <div class="col-md-8 blog-main" id="hot_post_list">
                <h2 style="margin-bottom: 20px">Popular on ReadMe</h2>

                <?php

                for($i=0; $i<10; $i++){
                    echo
                    '<div class="hot_post">
                        <div class="card flex-md-row mb-4 box-shadow h-md-250">
                            <img src="images/1.jpg" style="border-radius: 0 3px 3px 0; width:150px; height:180px; margin:10px" alt="Card image cap"/>
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">Non-fiction</strong>
                                <h3 class="mb-0">
                                    <a class="text-dark" href="#">Featured post</a>
                                </h3>
                                <div class="mb-1 text-muted">Nov 12</div>
                                <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                                <a href="#">Continue reading</a>
                            </div>
                        </div>
                    </div>';
                }
                ?>


            </div><!-- /.blog-main -->


            <aside class="col-md-4 blog-sidebar">

                <div style="margin-top: 50px; margin-bottom:30px">
                    <div>
                        <div><h4 class="font-italic">Weekly Best</h4></div>
                        <div style="text-align: right; margin-bottom: 10px"><a href="#" class="text-black font-weight-light">More >>></a></div>
                    </div>
                    <ul class="list-group mb-3">
                        <?php
                        for($i=0; $i<5; $i++) {
                                echo '
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">Harry Potter - '.$i.'화</h6>
                                        <small class="text-muted">J.K. Rowling</small>
                                    </div>
                                    <span class="text-muted">Fantasy</span>
                                </li>';
                        }
                        ?>
<!--                        <li class="list-group-item d-flex justify-content-between bg-light">-->
<!--                            <div class="text-success">-->
<!--                                <h6 class="my-0">Promo code</h6>-->
<!--                                <small>EXAMPLECODE</small>-->
<!--                            </div>-->
<!--                            <span class="text-success">-$5</span>-->
<!--                        </li>-->
<!--                        <li class="list-group-item d-flex justify-content-between">-->
<!--                            <span>Total (USD)</span>-->
<!--                            <strong>$20</strong>-->
<!--                        </li>-->
                    </ul>
                </div>


                <div style="margin-top: 50px; margin-bottom:30px">
                    <div>
                        <div><h4 class="font-italic">Monthly Best</h4></div>
                        <div style="text-align: right; margin-bottom: 10px"><a href="#" class="text-black font-weight-light">More >>></a></div>
                    </div>
                    <ul class="list-group mb-3">
                        <?php
                        for($i=0; $i<5; $i++) {
                            echo '
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">Lord of the Rings - '.$i.'화</h6>
                                        <small class="text-muted">J.K. Rowling</small>
                                    </div>
                                    <span class="text-muted">Fantasy</span>
                                </li>';
                        }
                        ?>
                    </ul>
                </div>

            </aside><!-- /.blog-sidebar -->

        </div><!-- /.row -->

<!--        스크롤 맨 위로 올리는 버튼-->
        <div class="gotop" style="position: fixed; bottom: 50px; right: 50px">
            <a href class="btn btn-outline-info my-2 my-sm-0">Top</a>
        </div>
    </main><!-- /.container -->

<!--    <footer class="blog-footer">-->
<!--        <p>2019 by <a href="https://twitter.com/mdo">@mdo</a>.</p>-->
<!--        <p>-->
<!--            <a href="#">Back to top</a>-->
<!--        </p>-->
<!--    </footer>-->

</body>
<script>
    // function signout(){
    //
    //     console.log('signout pressed');
    //
    //     var form = document.createElement("form");
    //     form.setAttribute("method", "post");
    //     form.setAttribute("action", "index.php");
    //
    //     //히든으로 값을 주입시킨다.
    //     var hiddenField = document.createElement("input");
    //     hiddenField.setAttribute("type", "hidden");
    //     hiddenField.setAttribute("value", "signout");
    //     form.appendChild(hiddenField);
    //
    //     document.body.appendChild(form);
    //     form.submit();
    // }
</script>

<script>
    $(document).ready(function() {

        //무한 스크롤
        $(document).scroll(function() { //스크롤 함수

            var currentHeight = $(document).height(); //현재 문서의 높이

            var currentScroll = $(window).scrollTop() + $(window).height(); // 브라우저의 스크롤 위치값

            if (currentHeight <= currentScroll + 100) { //맨 밑에서 100보다 높은 위치에 도달하면, 다음페이지 로드
                $.ajax({
                    type: 'post',
                    url: 'index/infinite_scroll.php',
                    data: {
                        'scroll': 1
                    },
                    success: function(data){
                        $('#hot_post_list').append(data);
                    }

                    //localStorage, sessionStorage 또는 쿠키 등을 사용하여 새롭게 로딩된 콘텐츠 개수를 기억해야 한다
                });
            }
        });


        //스크롤 맨 위로
        var speed = 100; // 스크롤속도
        $(".gotop").css("cursor", "pointer").click(function()
        {
            $('body, html').animate({scrollTop:0}, speed);
        });

    });
</script>

</html>