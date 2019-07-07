<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';

$URL = "../index.php";
if(!isset($_GET['key'])) { //검색창을 통하지 않고 이 페이지에 들어왔을 경우, 메인화면으로 돌려보낸다
    ?>
    <script>
        alert("Wrong access");
        location.replace("<?php echo $URL?>");
    </script>
    <?php
}

global $db;

$board_name = 'Search';

//전달받은 검색어
$keyword = '';
if(isset($_GET['key'])){
    $keyword = $_GET['key'];
}

$search_limit = '';
if(isset($_GET['limit'])){
    $search_limit = $_GET['limit'];
}else{
    $search_limit = 'title';

    searchLog($keyword); //검색어 로그를 쌓는다
}

//분류를 위해 각 게시판의 이름을 가져온다
$sql_board_name = "SELECT name FROM novelProject_boardInfo";
$result_board_name = mysqli_query($db, $sql_board_name);

$tag='';
if(isset($_GET['tag'])){
    $tag = $_GET['tag'];
}else{
    $tag = 'fiction';
}


//페이징 용도
if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}


$number_of_results = 0; //결과 행의 갯수
$result = '';
switch ($tag){
    case 'fiction': //fiction 게시판 db에서, 검색어에 적합한 소설을 찾는다

        switch ($search_limit){
            case 'title':
                //소설 제목에 해당 단어가 포함되어 있는지 확인
                $sql_search_story = "SELECT*FROM novelProject_storyInfo WHERE title LIKE '%".$keyword."%'";
                break;
            case 'tnt':

                push_log2('here');
                //소설 제목, 소설 설명에 해당 단어가 포함되어 있는지 확인
                $sql_search_story = "SELECT*FROM novelProject_storyInfo WHERE description LIKE '%".$keyword."%' or title LIKE '%".$keyword."%'";
                break;
            case 'writer':
                //소설 작가명에 해당 단어가 포함되어 있는지 확인
                $sql_search_story = "SELECT*FROM novelProject_storyInfo WHERE author_username LIKE '%".$keyword."%'";
                break;
        }

        $result = mysqli_query($db, $sql_search_story);
        $number_of_results = mysqli_num_rows($result);//결과 행의 개수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        switch ($search_limit){
            case 'title':
                //제목에 해당 단어가 포함된 글을 조회한다
                $sql_search_story = "SELECT*FROM novelProject_storyInfo WHERE title LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
            case 'tnt':

                push_log2('here');
                //제목, 설명에 해당 단어가 포함된 글을 조회한다
                $sql_search_story = "SELECT*FROM novelProject_storyInfo WHERE description LIKE '%".$keyword."%' or title LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
            case 'writer':
                //작가명에 해당 단어가 포함된 글을 조회한다
                $sql_search_story = "SELECT*FROM novelProject_storyInfo WHERE author_username LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
        }

        //10개씩만 가져온다
        $result = mysqli_query($db, $sql_search_story);

        break;
    case 'non-fiction':

        switch ($search_limit){
            case 'title':
                //글 제목에 해당 단어가 포함되어 있는지 확인
                $sql_search_nonfiction = "SELECT*FROM novelProject_nonfiction WHERE title LIKE '%".$keyword."%'";
                break;
            case 'tnt':
                //글 제목, 설명에 해당 단어가 포함되어 있는지 확인
                $sql_search_nonfiction = "SELECT*FROM novelProject_nonfiction WHERE description LIKE '%".$keyword."%' or title LIKE '%".$keyword."%'";
                break;
            case 'writer':
                //글쓴이명에 해당 단어가 포함되어 있는지 확인
                $sql_search_nonfiction = "SELECT*FROM novelProject_nonfiction WHERE author_username LIKE '%".$keyword."%'";
                break;
        }

        $result = mysqli_query($db, $sql_search_nonfiction);
        $number_of_results = mysqli_num_rows($result);//결과 행의 개수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        switch ($search_limit){
            case 'title':
                //제목에 해당 단어가 포함된 글을 조회한다
                $sql_search_nonfiction = "SELECT*FROM novelProject_nonfiction WHERE title LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
            case 'tnt':
                //제목, 설명에 해당 단어가 포함된 글을 조회한다
                $sql_search_nonfiction = "SELECT*FROM novelProject_nonfiction WHERE description LIKE '%".$keyword."%' or title LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
            case 'writer':
                //글쓴이명에 해당 단어가 포함된 글을 조회한다
                $sql_search_nonfiction = "SELECT*FROM novelProject_nonfiction WHERE author_username LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
        }

        $result = mysqli_query($db, $sql_search_nonfiction);

        break;
    case 'community':

        switch ($search_limit){
            case 'title':
                //글 제목에 해당 단어가 포함되어 있는지 확인
                $sql_search_community = "SELECT*FROM novelProject_community WHERE title LIKE '%".$keyword."%'";
                break;
            case 'tnt':
                //글 제목, 설명에 해당 단어가 포함되어 있는지 확인
                $sql_search_community = "SELECT*FROM novelProject_community WHERE description LIKE '%".$keyword."%' or title LIKE '%".$keyword."%'";
                break;
            case 'writer':
                //글쓴이명에 해당 단어가 포함되어 있는지 확인
                $sql_search_community = "SELECT*FROM novelProject_community WHERE author_username LIKE '%".$keyword."%'";
                break;
        }

        $result = mysqli_query($db, $sql_search_community);
        $number_of_results = mysqli_num_rows($result);//결과 행의 개수

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;


        switch ($search_limit){
            case 'title':
                //제목에 해당 단어가 포함된 글을 조회한다
                $sql_search_community = "SELECT*FROM novelProject_community WHERE title LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
            case 'tnt':
                //제목, 설명에 해당 단어가 포함된 글을 조회한다
                $sql_search_community = "SELECT*FROM novelProject_community WHERE description LIKE '%".$keyword."%' or title LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
            case 'writer':
                //작가명에 해당 단어가 포함된 글을 조회한다
                $sql_search_community = "SELECT*FROM novelProject_community WHERE author_username LIKE '%".$keyword."%' LIMIT ".$start_from .",".$results_per_page;
                break;
        }

        $result = mysqli_query($db, $sql_search_community);

        break;
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
            <div class="col-5" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | <?php echo $board_name?>
            </div>



            <div class="form-inline">

                <input class="form-control mr-sm-2" onkeypress="if(event.keyCode==13) {sendGet(); return false;}" id="search_input" type="search" placeholder="Search" aria-label="Search">
                <a class="text-muted" href="javascript:void(0);" onclick="sendGet();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                </a>
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

<main role="main" class="container">

    <div class="jumbotron p-3 text-white rounded bg-dark" style="margin-top: 40px; margin-bottom: 30px;">
        <p style="font-size: 20px">Search Options</p>

        <div style="margin-bottom: 10px">Search Range
        <?php

        //검색 범위 설정
        //제목, 제목+태그, 글쓴이로 분류
        $search_options = ['title', 'title+tag', 'writer'];
        for($k=0; $k<count($search_options); $k++){

            $option = '';
            if($search_options[$k]=='title+tag'){
                $option = 'tnt';
            }else{
                $option = $search_options[$k];
            }

            //이미 선택된 버튼색깔을 active로 바꿔준다
            if($option==$search_limit){
                echo '<button class="btn btn-outline-success active" style="margin:10px">'.$search_options[$k].'</button>';

            }else{
                echo '<button class="btn btn-outline-success" style="margin:10px"
               onclick="location.href=\'page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$option.'&page='.$page.'\'">'.$search_options[$k].'</button>';
            }
        }
        ?>
        </div>
        <div>Search by Board
        <?php

        //게시판별 분류
        //각 게시판의 이름을 버튼형식으로 나열한다
        while($row_board_name = mysqli_fetch_array($result_board_name)){

            if($row_board_name[0] != 'bestseller'){

                //이미 선택된 버튼의 색을 active로 바꿔준다
                if($row_board_name[0]==$tag){
                    echo '<button class="btn btn-outline-warning active" style="margin:10px">'.$row_board_name[0].'</button>';

                }else{
                    echo '<button class="btn btn-outline-warning" style="margin:10px"
                   onclick="location.href=\'page_searchResult.php?tag='.$row_board_name[0].'&key='.$keyword.'&limit='.$search_limit.'&page='.$page.'\'">'.$row_board_name[0].'</button>';
                }


            }

        }
        ?>
        </div>
    </div>


    <div class="row" style="margin-top: 50px">
        <div class="col-md-8 blog-main" id="hot_post_list" style="margin:0px auto;">
            <div style="margin-bottom: 50px">
                <h4 style="float:left; width: 80%;"> <?php
                    if($number_of_results>1){
                        echo $number_of_results.' results for \''.$keyword.'\'';
                    }else{
                        echo $number_of_results.' result for \''.$keyword.'\'';
                    }
                    ?></h4>

            </div>


            <?php

            while($row = mysqli_fetch_array($result)){

                if($tag=='fiction'){

                    $story_db_id=$row['id'];
                    $genre = $row['genre'];

                    //image 받아야함
                    $title=$row['title'];
                    $description = $row['description'];
                    $author_username=$row['author_username'];
                    $period = $row['startDate'].'~'.$row['lastUpdate'];
                    $numberOfEpisodes = $row['numberOfEpisodes'];


                    //이 story의 총 조회수, 좋아요 수, 북마크 수를 계산한다
                    $sql_episodeInfo = "SELECT*FROM novelProject_episodeInfo WHERE story_db_id ='$story_db_id'";
                    $result_episode = mysqli_query($db, $sql_episodeInfo);

                    $numberOfViews=0;
                    $numberOfLikes=0;
                    $numberOfBookmarks=0;

                    while($row_episode = mysqli_fetch_array($result_episode)){

                        $numberOfViews+=$row_episode['numberOfViews'];
                        $numberOfBookmarks+=$row_episode['bookmark'];
                        $numberOfLikes+=$row_episode['numberOfLikes'];
                    }

                    $randomNumber = generateRandomInt(25);
                    $img_src = $randomNumber.'.jpg';

                    echo
                        '<div class="list_item" onclick="location.href=\'page_TableOfContents.php?&id='.$story_db_id.'\'" style="margin-bottom: 20px;">
                        <div class="card flex-md-row box-shadow h-md-250">
                            <img src="../images/bookCover_dummy/'.$img_src.'" style="border-radius: 0 3px 3px 0; width:130px; height:190px; margin:10px" alt="Card image cap"/>
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">'.$genre.'</strong>
                                <h5 class="mb-0">
                                    <a class="text-dark">'.$title.' : '.$title.'</a>
                                </h5>
                                <div class="mb-1 text-muted">by '.$author_username.'</div>
                                <p class="card-text mb-auto">'.$description.'</p>
                                <div style="margin-top: 10px; width:100%;">
                                    <div style="float:left; width:65%">'.$numberOfViews.' views * '.$numberOfLikes.' likes * '.$numberOfBookmarks.' bookmarks</div>
                                    <div class="text-muted" style="float:left; width:35%">'.$period.'</div>
                                </div>
                            </div>
             
                        </div>
                     </div>';

                }else{


                    $title=$row['title'];
                    $description=$row['description'];
                    $author_username=$row['author_username'];
                    $genre = $row['genre'];
                    $episode_db_id=$row['id'];//클릭 시 get 방식으로 보내주기
                    //image 받아야함
                    $date=$row['date'];
                    $numberOfLikes = $row['numberOfLikes'];
                    $numberOfComments = $row['numberOfComments'];
                    $numberOfViews = $row['numberOfViews'];

                    $date_modified = explode(' ',$date)[0];
                    $today=date("Y-m-d");

                    if($date_modified==$today){
                        $date_modified = 'Today '.explode(' ',$date)[1];
                    }


                    $randomNumber = generateRandomInt(30);
                    $img_src = 'dummy ('.$randomNumber.').jpg';

                    echo
                        '<div class="list_item" onclick="location.href=\'read_post.php?board='.$tag.'&ep_id='.$episode_db_id.'\'" style="margin-bottom: 20px;">
                        <div class="card flex-md-row box-shadow h-md-250">
                            <img src="../images/bookCover_dummy/'.$img_src.'" style="border-radius: 0 3px 3px 0; width:150px; height:150px; margin:10px" alt="Card image cap"/>
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">'.$genre.'</strong>
                                <h5 class="mb-0">
                                    <a class="text-dark">'.$title.'</a>
                                </h5>
                                <div class="mb-1 text-muted">by '.$author_username.'</div>
                                <p class="card-text mb-auto">'.$description.'</p>
                                <div style="margin-top: 10px; width:100%;">
                                    <div style="float:left; width:80%">'.$numberOfViews.' views * '.$numberOfLikes.' likes * '.$numberOfComments.' comments</div>
                                    <div class="text-muted" style="float:left; width:20%">'.$date_modified.'</div>
                                </div>
                            </div>
             
                        </div>
                     </div>';

                }

            }

            ?>


            <nav aria-label="Page navigation example" style="margin-top: 80px; margin-bottom: 100px;">
                <ul class="pagination">
                    <?php

                    if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                        if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                            //1~5페이지를 보여준다
                            for ($i=1; $i<=5; $i++){
                                if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                    echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                }else{
                                    echo '<li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page=' . $i . '">'. $i .'</a></li>';
                                }
                            }

                            //마지막 페이지로 가는 버튼
                            echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page='.$number_of_pages.'">Last</a></li>';

                        }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                            //첫 페이지로 가는 버튼
                            echo '<li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page=1">First</a></li><li class="page-item">. . .</li>';

                            // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                            for ($i=$page-2; $i<=$page+2; $i++){
                                if($i==$page){
                                    echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                }else{
                                    echo '<li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page=' . $i . '">'. $i .'</a></li>';
                                }
                            }

                            //마지막 페이지로 가는 버튼
                            echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page='.$number_of_pages.'">Last</a></li>';

                        }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                            //첫 페이지로 가는 버튼
                            echo '<li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page=1">First</a></li><li class="page-item">. . .</li>';

                            //마지막에서 5개 페이지를 보여준다
                            for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                                if($i==$page){
                                    echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                                }else{
                                    echo '<li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page=' . $i . '">'. $i .'</a></li>';
                                }
                            }

                        }

                    }else{
                        for ($i=1; $i<=$number_of_pages; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_searchResult.php?tag='.$tag.'&key='.$keyword.'&limit='.$search_limit.'&page=' . $i . '">'. $i .'</a></li>';
                            }
                        }
                    }


                    ?>

                </ul>
            </nav>


        </div>


    </div><!-- /.row -->



    <!--        스크롤 맨 위로 올리는 버튼-->
    <div class="gotop" style="position: fixed; bottom: 50px; right: 50px">
        <a href class="btn btn-outline-info my-2 my-sm-0">Top</a>
    </div>
</main><!-- /.container -->



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