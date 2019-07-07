<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once 'session.php';
require_once 'log/log.php';
require_once 'functions.php';
require_once 'infinite_scroll.php';

global $db;
accessLog();



$sql_getWeeklyData = "SELECT*FROM novelProject_episodeInfo WHERE date(date) >= date(subdate(now(), INTERVAL 7 DAY)) and date(date) <= date(subdate(now(), INTERVAL 3 DAY)) ORDER BY numberOfLikes DESC LIMIT 5";
$result_weeklyData = mysqli_query($db, $sql_getWeeklyData) or die(mysqli_error($db));


$sql_getMonthlyData = "SELECT*FROM novelProject_episodeInfo WHERE date(date) >= date(subdate(now(), INTERVAL 30 DAY)) and date(date) <= date(subdate(now(), INTERVAL 8 DAY)) ORDER BY numberOfLikes DESC LIMIT 5";
$result_monthlyData = mysqli_query($db, $sql_getMonthlyData) or die(mysqli_error($db));



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

<!--    dataTables-->
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

<!--    stylesheets-->
    <link rel="stylesheet" href="css/write/items.css">

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
                <div class="col-5">
                    <a class="blog-header-logo text-dark" style="font-size: 45px; margin-right:30px; font-family: Times New Roman;" href="#">ReadMe</a>
<!--                    <div class="btn-group">-->
<!--                        <button style="background-color: transparent; font-size: 25px" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                            Browse-->
<!--                        </button>-->
<!--                        <div class="dropdown-menu">-->
<!--                            <a class="dropdown-item" href="boards/page_CreateNewStory.php">Create a New Story</a>-->
<!--                            <a class="dropdown-item" href="boards/page_MyStories.php">My Stories</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="btn-group">-->
<!--                        <button style="background-color: transparent; font-size: 25px;" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                            Community-->
<!--                        </button>-->
<!--                        <div class="dropdown-menu">-->
<!--                            <a class="dropdown-item" href="boards/page_CreateNewStory.php">Create a New Story</a>-->
<!--                            <a class="dropdown-item" href="boards/page_MyStories.php">My Stories</a>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>

                <div class="form-inline">

<!--                    <div class="btn-group" id="search_limit" style="margin-right: 10px">-->
<!--                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                            title <span class="caret"></span>-->
<!--                        </button>-->
<!---->
<!--                        <ul class="dropdown-menu">-->
<!--                            <li><a class="dropdown-item" href="javascript:void(0)">title</a></li>-->
<!--                            <li><a class="dropdown-item" href="javascript:void(0)">title+tag</a></li>-->
<!--                            <li><a class="dropdown-item" href="javascript:void(0)">writer</a></li>-->
<!--                        </ul>-->
<!--                    </div>-->


                    <input class="form-control mr-sm-2" onkeypress="if(event.keyCode==13) {sendGet(); return false;}" id="search_input" type="search" placeholder="Search" aria-label="Search">
                    <a class="text-muted" href="javascript:void(0);" onclick="sendGet();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                    </a>
                </div>

<!--                채팅-->
                <?php
                if($_SESSION['user']){

                    $user_email = $_SESSION['email'];
                    $username = $_SESSION['user'];

                    push_log('email '.$user_email.' entered the chat');

                    $sql = "SELECT*FROM novelProject_userInfo WHERE email='$user_email'";
                    $result = mysqli_query($db, $sql);
                    $row = mysqli_fetch_array($result);

                    $user_db_id = $row['id'];

//                    echo '
//                    <a class="text-muted" href="http://192.168.133.131:3000?id='.$user_db_id.'">
//                        <img src="images/chat.png" style="width:30px; height:30px;margin-right: 30px">
//                    </a>
//                    ';

                    echo '
                     <img src="images/chat.png" style="width:30px; height:30px;margin-right: 30px"
                     onclick="window.open(\'http://192.168.133.131:3000?id='.$user_db_id.'&name='.$username.'\')">
                    ';


                }
                ?>

<!--                <form action="" >-->
<!--                    <img src="images/chat.png" style="width:30px; height:30px;margin-right: 30px">-->
<!--                </form>-->

                <div>
<!--                    <div class="btn-group">-->
<!--                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                            Write-->
<!--                        </button>-->
<!--                        <div class="dropdown-menu">-->
<!--                            <a class="dropdown-item" href="boards/page_CreateNewStory.php">Create a New Story</a>-->
<!--                            <a class="dropdown-item" href="boards/page_MyStories.php">My Stories</a>-->
<!--                        </div>-->
<!--                    </div>-->
                    <?php
                    if(isset($_SESSION['email'])){
                        echo '
                        <div class="btn-group" style="margin-right: 15px">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                '.$_SESSION['user'].'
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="boards/page_MyStories.php">My Stories</a>
                                <a class="dropdown-item" href="boards/myPage.php">Library</a>
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
<!--                <a class="p-2 text-muted" style="margin-left: 80px;" href="#">Fandom</a>-->
                <a class="p-2 text-muted" style="margin-left: 80px;" href="boards/mainPage.php?board=fiction">Fiction</a>
                <a class="p-2 text-muted" href="boards/mainPage_nonFiction.php?board=non-fiction">Non-fiction</a>
                <a class="p-2 text-muted" href="boards/mainPage_nonFiction.php?board=community">Community</a>

<!--                trend 버튼 if else로 나눈 이유: 오른쪽 마진 때문에-->
                <?php
                if(isset($_SESSION['email']) && $_SESSION['email']=='admin@gmail.com'){
                    echo '
                        <a class="p-2 text-muted" href="boards/page_trends.php?board=trends">Trend</a>
                        <a class="p-2 text-muted" style="margin-right: 80px;" href="boards/page_analytics.php">Analytics</a>
                    ';
                }else{
                    echo '
                        <a class="p-2 text-muted" style="margin-right: 80px;" href="boards/page_trends.php?board=trends">Trend</a>
                    ';
                }
                ?>
            </nav>
        </div>

        <div class="jumbotron p-3 p-md-5 text-white rounded bg-dark" style="margin-top: 20px; margin-bottom: 30px;
background-image: url('images/book.jpg')">
            <div class="col-md-6 px-0">
                <h1 class="display-4 font-italic" style="font-family: 'Times New Roman'">
                    Share your stories.</h1>
                <p class="lead my-3"><strong>ReadMe</strong> supports brilliant writers to share their stories and communicate with readers.</p>
            </div>
        </div>

    </div>

    <main role="main" class="container">
        <div class="row">
            <div class="col-md-8 blog-main" id="hot_post_list" style="margin-bottom: 50px">
                <h2 style="margin-bottom: 20px">Popular on ReadMe</h2>

                <?php //10개의 소설 아이템을 화면에 출력한다. $comments 는 infinite_scroll.php의 변수이다.

                global $first_page;
                echo $first_page ?>

            </div><!-- /.blog-main -->


            <aside class="col-md-4 blog-sidebar">

                <div style="margin-top: 50px; margin-bottom:30px">
                    <div>
                        <div><h4 class="font-italic">Weekly Best</h4></div>
<!--                        <div style="text-align: right; margin-bottom: 10px"><a href="#" class="text-black font-weight-light">More >>></a></div>-->
                    </div>
                    <ul class="list-group mb-3">
                        <?php

                        //주간 인기글 목록
                        while($row = mysqli_fetch_array($result_weeklyData)){

                            $board_name='fiction';
                            $genre = $row['genre'];
                            $episode_db_id=$row['id'];//클릭 시 get 방식으로 보내주기

                            $title=$row['title'];
                            $author_username=$row['author_username'];
                            $story_title=$row['storyTitle'];


                            echo '
                                <li class="list_item_sm list-group-item d-flex justify-content-between lh-condensed" onclick="location.href=\'boards/read_post.php?board='.$board_name.'&ep_id='.$episode_db_id.'\'">
                                    <div>
                                        <h6 class="my-0">'.$story_title.' - '.$title.'</h6>
                                        <small class="text-muted">'.$author_username.'</small>
                                    </div>
                                    <span class="text-muted">'.$genre.'</span>
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
<!--                        <div style="text-align: right; margin-bottom: 10px"><a href="#" class="text-black font-weight-light">More >>></a></div>-->
                    </div>
                    <ul class="list-group mb-3">
                        <?php

                        //월간 인기글 목록
                        while($row = mysqli_fetch_array($result_monthlyData)){

                            $board_name='fiction';
                            $genre = $row['genre'];
                            $episode_db_id=$row['id'];//클릭 시 get 방식으로 보내주기

                            $title=$row['title'];
                            $author_username=$row['author_username'];
                            $story_title=$row['storyTitle'];


                            echo '
                                <li class="list_item_sm list-group-item d-flex justify-content-between lh-condensed" onclick="location.href=\'boards/read_post.php?board='.$board_name.'&ep_id='.$episode_db_id.'\'">
                                    <div>
                                        <h6 class="my-0">'.$story_title.' - '.$title.'</h6>
                                        <small class="text-muted">'.$author_username.'</small>
                                    </div>
                                    <span class="text-muted">'.$genre.'</span>
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

    //사용자가 선택한 검색 제한 값을 가져온다 - 사용x
    // var search_limit = 'title';
    // $("#search_limit a").click(function(e){
    //     e.preventDefault();
    //     search_limit = $(this).text();
    // });
    //
    // //dropdown 활성화: 선택한 아이템을 버튼 위에 띄워준다
    // $('#search_limit .dropdown-menu li > a').bind('click', function (e) {
    //     var html = $(this).html();
    //     $('#search_limit button.dropdown-toggle').html(html + ' <span class="caret"></span>');
    // });

    //검색 결과 페이지로 이동
    function sendGet(){
        var search_input = $('#search_input').val();
        location.href="boards/page_searchResult.php?key="+search_input;
    }


    //자바스크립트로 post 보내기 - 사용하지 않음
    // function sendPost(){
    //
    //     var form = document.createElement("form");
    //     form.setAttribute("method", "post");
    //     form.setAttribute("action", "boards/page_searchResult.php");
    //     // document.charset = "utf-8"; //꼭 해야 하나?
    //
    //     var search_input = $('#search_input').val();
    //     //히든으로 값을 주입시킨다.
    //     var hiddenField = document.createElement("input");
    //     hiddenField.setAttribute("type", "hidden");
    //     hiddenField.setAttribute("name", "search"); //post key
    //     hiddenField.setAttribute("value", search_input);
    //     form.appendChild(hiddenField);
    //
    //     document.body.appendChild(form);
    //     form.submit();
    //     search_input.val('');
    // }
</script>

<script>
    $(document).ready(function() {

        var page = 1;
        var new_items ='';
        var currentScroll='';

        //무한 스크롤
        $(document).scroll(function() { //스크롤 함수

            var currentHeight = $(document).height(); //현재 문서의 높이

            currentScroll = $(window).scrollTop() + $(window).height(); // 브라우저의 스크롤 위치값

            if (currentHeight <= currentScroll + 50) { //맨 밑에서 100보다 높은 위치에 도달하면, 다음페이지 로드

                if(page != 'end'){

                    $.ajax({
                        type: 'post',
                        url: 'infinite_scroll.php',
                        dataType: 'JSON',
                        data: {
                            'scroll': ++page <?php /*첫번째 페이지는 원래 띄워져 있으므로, 2페이지부터 시작한다*/?>
                        },
                        success: function(data){

                            <?php /*json 데이터를 파싱한다*/?>
                            page = data['page']; <?php /*지금이 몇 페이지인지*/?>
                            console.log('page='+page);

                            new_items = data['item']; <?php /*화면에 띄워줄 목록 아이템*/?>

                            $('#hot_post_list').append(new_items);

                            if(page =='end'){
                                $('#hot_post_list').append('<div style="margin-top:50px; color:darkolivegreen; font-size: 30px; font-family: \'Times New Roman\'">*** End of Results ***</div>');
                            }

                        }
                        <?php /*localStorage, sessionStorage 또는 쿠키 등을 사용하여 새롭게 로딩된 콘텐츠 개수를 기억해야 한다*/?>

                    });

                }

            }
        });


        window.onbeforeunload = function() {
            sessionStorage.setItem('page', page);
            sessionStorage.setItem('scroll', currentScroll)
        }


        <?php /*스크롤 맨 위로 올리는 함수*/?>
        var speed = 100; <?php /*스크롤속도*/?>
        $(".gotop").css("cursor", "pointer").click(function()
        {
            $('body, html').animate({scrollTop:0}, speed);
        });

    });
</script>

</html>