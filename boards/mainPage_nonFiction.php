<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';

global $db;
accessLog();

//게시판 이름을 get 방식으로 전달받는다
$board_name = $_GET['board'];

//db에 저장된 fiction 장르를 가져온다
$sql_tableName='';
if($board_name=='non-fiction'){
    $sql_tableName='novelProject_nonfiction';
}else if($board_name=='community'){
    $sql_tableName='novelProject_community';
}

$genre ='';
$sql = "SELECT*FROM novelProject_boardInfo WHERE name='$board_name'";

$result = mysqli_query($db, $sql);

if(mysqli_num_rows($result)==1){
    $row = mysqli_fetch_array($result);
    $genre_string = $row['category'];
}


//페이징 용도
if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}


if(isset($_GET['tag'])){
    $tag = $_GET['tag'];


    if(isset($_GET['sort'])){ //태그를 설정한 상태에서 인기순 or 최신순으로 분류할 때
        $sort = $_GET['sort'];

        //db에서 글을 가져온다
        $sql_episode_tag_sort = "SELECT*FROM ".$sql_tableName." WHERE genre='$tag'";
        $result = mysqli_query($db, $sql_episode_tag_sort);
        $number_of_results = mysqli_num_rows($result); //결과 행의 갯수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        $sql='';
        if($sort=='Rating'){
            $sql = "SELECT*FROM ".$sql_tableName." WHERE genre='$tag' ORDER BY numberOfLikes DESC LIMIT ".$start_from .",".$results_per_page;

        }else if($sort=='New'){
            $sql = "SELECT*FROM ".$sql_tableName." WHERE genre='$tag' ORDER BY date DESC LIMIT ".$start_from .",".$results_per_page;
        }

        //10개씩만 가져온다
        $result = mysqli_query($db, $sql);


    }else{ //인기순 or 최신순 분류 없이 태그만 설정했을 때 - 일단 최신순으로 정렬

        //db에서 글을 가져온다
        $sql_episode_tag = "SELECT*FROM ".$sql_tableName." WHERE genre='$tag'";
        $result = mysqli_query($db, $sql_episode_tag);
        $number_of_results = mysqli_num_rows($result); //결과 행의 갯수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        //10개씩만 가져온다
        $sql = "SELECT*FROM ".$sql_tableName." WHERE genre='$tag' ORDER BY date DESC LIMIT ".$start_from .",".$results_per_page;
        $result = mysqli_query($db, $sql);


    }


}else{ //처음 이 페이지에 들어오거나, 분류 없이 페이지 넘길때


    if(isset($_GET['sort'])){
        $sort = $_GET['sort'];

        //db에서 글을 가져온다
        $sql_episode_sort = "SELECT*FROM ".$sql_tableName;
        $result = mysqli_query($db, $sql_episode_sort);
        $number_of_results = mysqli_num_rows($result); //결과 행의 갯수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        $sql='';
        if($sort=='Rating'){
            $sql = "SELECT*FROM ".$sql_tableName." ORDER BY numberOfLikes DESC LIMIT ".$start_from .",".$results_per_page;

        }else if($sort=='New'){
            $sql = "SELECT*FROM ".$sql_tableName." ORDER BY date DESC LIMIT ".$start_from .",".$results_per_page;
        }

        //10개씩만 가져온다
        $result = mysqli_query($db, $sql);

    }else{ //아무 분류를 하지 않았을 때 - 최신순으로 정렬

        $sort = 'New';

        //db에서 글을 가져온다
        $sql_episode_only = "SELECT*FROM ".$sql_tableName;
        $result = mysqli_query($db, $sql_episode_only);
        $number_of_results = mysqli_num_rows($result); //결과 행의 갯수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        //10개씩만 가져온다
        $sql = "SELECT*FROM ".$sql_tableName." ORDER BY date DESC LIMIT ".$start_from .",".$results_per_page;
        $result = mysqli_query($db, $sql);

    }

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

            <div class="form-inline">

                <input class="form-control mr-sm-2" onkeypress="if(event.keyCode==13) {sendGet(); return false;}" id="search_input" type="search" placeholder="Search" aria-label="Search">
                <a class="text-muted" href="javascript:void(0);" onclick="sendGet();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                </a>
            </div>

            <div>
                <div class="btn-group">
                    <button type="button" class="btn btn-success" onclick="location.href='page_writeNewPost.php?board=<?php echo $board_name?>'">
                        New Post
                    </button>
                </div>
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

<main role="main" class="container">

    <div class="jumbotron p-3 text-white rounded bg-dark" style="margin-top: 40px; margin-bottom: 30px;">
        <p>Refine by genre</p>
        <button class="btn btn-outline-success" style="margin:10px"
                onclick="location.href='mainPage_nonFiction.php?board=<?php echo $board_name?>'">All</button>
        <?php

        //string으로 이어서 가져온 장르를 개별로 분할하여 화면에 출력한다
        $genre_split_array = explode(';', $genre_string);

        for($i=0; $i<count($genre_split_array); $i++){

            $genre_after_split = $genre_split_array[$i];

            //선택된 장르의 버튼색깔을 active로 바꿔준다
            if($genre_after_split==$tag){
                echo '<button class="btn btn-outline-success active" style="margin:10px"
            onclick="location.href=\'mainPage_nonFiction.php?board='.$board_name.'&tag='.$genre_after_split.'\'">'.$genre_after_split.'</button>';

            }else{
                echo '<button class="btn btn-outline-success" style="margin:10px"
            onclick="location.href=\'mainPage_nonFiction.php?board='.$board_name.'&tag='.$genre_after_split.'\'">'.$genre_after_split.'</button>';
            }
        }
        ?>
    </div>

    <div class="row" style="margin-top: 50px">
        <div class="col-md-8 blog-main" id="hot_post_list" style="margin:0px auto;">
            <div style="margin-bottom: 50px">
                <h4 style="float:left; width: 80%;"> <?php
                    if($number_of_results>1){
                        echo $number_of_results.' Stories';
                    }else{
                        echo $number_of_results.' Story';
                    }
                    ?></h4>
                <div class="btn-group" style="float:left; width:20%">
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort by : <?php echo $sort?>
                    </button>
                    <div class="dropdown-menu">
                        <?php
                        if($tag!=null){
                            echo '
                              <a class="dropdown-item" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&sort=Rating">Rating</a>
                              <a class="dropdown-item" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&sort=New">New</a>
                            ';
                        }else{
                            echo '
                              <a class="dropdown-item" href="mainPage_nonFiction.php?board='.$board_name.'&sort=Rating">Rating</a>
                              <a class="dropdown-item" href="mainPage_nonFiction.php?board='.$board_name.'&sort=New">New</a>
                            ';
                        }

                        ?>
                    </div>
                </div>
            </div>


            <?php

            while($row = mysqli_fetch_array($result)){

                $title=$row['title'];
                $description=$row['description'];
                $author_username=$row['author_username'];
                $genre = $row['genre'];
                $episode_db_id=$row['id'];//클릭 시 get 방식으로 보내주기
                $image_name = $row['image'];
                $date=$row['date'];
                $numberOfLikes = $row['numberOfLikes'];
                $numberOfComments = $row['numberOfComments'];
                $numberOfViews = $row['numberOfViews'];

                $date_modified = explode(' ',$date)[0];
                $today=date("Y-m-d");

                if($date_modified==$today){
                    $date_modified = 'Today '.explode(' ',$date)[1];
                }


                $image_path = '../images/ck_uploads/'.$image_name;
                if($image_name == 'default' || $image_name == null || $image_name == ''){
                    $randomNumber = generateRandomInt(30);
                    $img_src = 'dummy ('.$randomNumber.').jpg';

                    $image_path = '../images/bookCover_dummy/'.$img_src;
                }


                echo
                    '<div class="list_item" onclick="location.href=\'read_post.php?board='.$board_name.'&ep_id='.$episode_db_id.'\'" style="margin-bottom: 20px;">
                        <div class="card flex-md-row box-shadow h-md-250">
                            <img src="'.$image_path.'" style="border-radius: 0 3px 3px 0; width:150px; height:150px; margin:10px" alt="Card image cap"/>
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">'.$genre.'</strong>
                                <h5 class="mb-0">
                                    <a class="text-dark" style="word-break: break-all">'.$title.'</a>
                                </h5>
                                <div class="mb-1 text-muted">by '.$author_username.'</div>
                                <p class="card-text mb-auto" style="word-break: break-all">'.$description.'</p>
                                <div style="margin-top: 10px; width:100%;">
                                    <div style="float:left; width:75%">'.$numberOfViews.' views * '.$numberOfLikes.' likes * '.$numberOfComments.' comments</div>
                                    <div class="text-muted" style="float:left; width:25%">'.$date_modified.'</div>
                                </div>
                            </div>
             
                        </div>
                     </div>';
            }

            ?>


            <nav aria-label="Page navigation example" style="margin-top: 80px; margin-bottom: 100px;">
                <ul class="pagination">
                    <?php

                    if($tag!=null){ //장르(tag)별로 분류해서 페이징


                        if($sort!=null){ //인기순 or 최신순으로 분류할 때


                            if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                                if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                                    //1~5페이지를 보여준다
                                    for ($i=1; $i<=5; $i++){
                                        if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page='.$number_of_pages.'">Last</a></li>';

                                }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                                    for ($i=$page-2; $i<=$page+2; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page='.$number_of_pages.'">Last</a></li>';

                                }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    //마지막에서 5개 페이지를 보여준다
                                    for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php_nonFiction?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                }

                            }else{
                                for ($i=1; $i<=$number_of_pages; $i++){
                                    if($i==$page){
                                        echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                    }
                                }
                            }




                        }else{//장르별로만 분류

                            if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                                if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                                    //1~5페이지를 보여준다
                                    for ($i=1; $i<=5; $i++){
                                        if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page='.$number_of_pages.'">Last</a></li>';

                                }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                                    for ($i=$page-2; $i<=$page+2; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page='.$number_of_pages.'">Last</a></li>';

                                }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    //마지막에서 5개 페이지를 보여준다
                                    for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                }

                            }else{
                                for ($i=1; $i<=$number_of_pages; $i++){
                                    if($i==$page){
                                        echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&tag='.$tag.'&page=' . $i . '">'. $i .'</a></li>';
                                    }
                                }
                            }

                        }

                    }else{ //장르(tag) 미분류


                        if($sort!=null){ //인기순, 최신순으로 분류


                            if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                                if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                                    //1~5페이지를 보여준다
                                    for ($i=1; $i<=5; $i++){
                                        if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page='.$number_of_pages.'">Last</a></li>';

                                }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                                    for ($i=$page-2; $i<=$page+2; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page='.$number_of_pages.'">Last</a></li>';

                                }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    //마지막에서 5개 페이지를 보여준다
                                    for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                }

                            }else{
                                for ($i=1; $i<=$number_of_pages; $i++){
                                    if($i==$page){
                                        echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&sort='.$sort.'&page=' . $i . '">'. $i .'</a></li>';
                                    }
                                }
                            }



                        }else{//아무 분류도 안함

                            if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                                if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                                    //1~5페이지를 보여준다
                                    for ($i=1; $i<=5; $i++){
                                        if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page='.$number_of_pages.'">Last</a></li>';

                                }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                                    for ($i=$page-2; $i<=$page+2; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                    //마지막 페이지로 가는 버튼
                                    echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="_nonFiction?board='.$board_name.'&page='.$number_of_pages.'">Last</a></li>';

                                }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                                    //첫 페이지로 가는 버튼
                                    echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page=1">First</a></li><li class="page-item">. . .</li>';

                                    //마지막에서 5개 페이지를 보여준다
                                    for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                                        if($i==$page){
                                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page=' . $i . '">'. $i .'</a></li>';
                                        }
                                    }

                                }

                            }else{
                                for ($i=1; $i<=$number_of_pages; $i++){
                                    if($i==$page){
                                        echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="mainPage_nonFiction.php?board='.$board_name.'&page=' . $i . '">'. $i .'</a></li>';
                                    }
                                }
                            }


                        }


                    }


                    ?>

                </ul>
            </nav>


        </div><!-- /.blog-main -->



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

    //검색 결과 페이지로 이동
    function sendGet(){
        var search_input = $('#search_input').val();
        location.href="page_searchResult.php?key="+search_input;
    }

</script>

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