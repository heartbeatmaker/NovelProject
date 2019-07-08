<?php
    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';
    require_once 'comment_server.php';

    global $db;
    accessLog();

//    var_dump($_SESSION);
    $board_name = $_GET['board'];

    $column_name_like='likeHistory';
    $column_name_bookmark='bookmarkHistory';

    if($board_name=='fiction'){
        $sql_tableName = 'novelProject_episodeInfo';
        $column_name_like .= '_fiction';
        $column_name_bookmark .= '_fiction';

    }else if($board_name=='non-fiction'){
        $sql_tableName = 'novelProject_nonfiction';
        $column_name_like .= '_nonfiction';
        $column_name_bookmark .= '_nonfiction';

    }else if($board_name=='community'){
        $sql_tableName = 'novelProject_community';
        $column_name_like .= '_community';
        $column_name_bookmark .= '_community';
    }


    //이 episode가 db에 어떤 id값으로 저장되어 있는지 get방식으로 받아온다
    $episode_db_id='';
    if(isset($_GET['ep_id'])){
        $episode_db_id = $_GET['ep_id'];
    }




    //정보 추출
    $sql = "SELECT*FROM ".$sql_tableName." WHERE id='$episode_db_id'";

    $result = mysqli_query($db, $sql);

    $storyTitle='';
    $episodeTitle='';
    $content='';
    $author_username='';
    $author_email='';
    $publishedTime='';
    $story_db_id='';
    $numberOfLikes='';
    $numberOfBookmarks='';

    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result);

        $episodeTitle = $row['title'];
        $storyTitle = $row['storyTitle'];
        $content=$row['content'];
        $author_username=$row['author_username'];
        $author_email=$row['author_email'];
        $publishedTime =$row['date'];
        $story_db_id=$row['story_db_id'];
        $numberOfLikes=$row['numberOfLikes'];
        $numberOfBookmarks=$row['bookmark'];

        //조회수 +1 저장
        $query_episodeInfo = "UPDATE ".$sql_tableName." SET numberOfViews= numberOfViews + 1 WHERE id='$episode_db_id'";
        $result_episodeDB = mysqli_query($db, $query_episodeInfo);
    }


    $isAlreadyLiked = false;
    $isAlreadyBookmarked = false;
    //좋아요, 북마크 버튼 처리 - 이 사용자가 이 글에 좋아요 or 북마크를 이미 눌렀는지 확인한다
    if(isset($_SESSION['user'])){

        $currentUser_email = $_SESSION['email'];
        $query_userInfo = "SELECT*FROM novelProject_userInfo WHERE email ='$currentUser_email'";
        $result_userInfo = mysqli_query($db, $query_userInfo) or die(mysqli_error($db));

        //사용자의 좋아요, 북마크 목록을 가져온다
        $currentLikeHistory='';
        $currentBookmark='';
        if(mysqli_num_rows($result_userInfo) == 1){
            $row_userInfo = mysqli_fetch_array($result_userInfo);
            $currentLikeHistory = $row_userInfo[$column_name_like];
            $currentBookmark = $row_userInfo[$column_name_bookmark];
        }
        push_log('읽기화면) 사용자의 좋아요, 북마크 목록을 가져온다');
        push_log('읽기화면) 최초 currentLikeHistory='.$currentLikeHistory.' // currentBookmark='.$currentBookmark);


        //좋아요 값 확인
        if($currentLikeHistory == null){ //좋아요 목록이 비어있으면 -> isAlreadyLiked=false로 내비둠
            push_log('읽기화면) 좋아요 목록이 비어있음');

        }else{ //좋아요 목록에 값이 있으면 -> 이 글에 좋아요를 이미 누른 상태인지 확인한다
            push_log('읽기화면) 좋아요 목록에 값이 있음. 이 글에 좋아요를 이미 누른 상태인지 확인한다');

            $currentLikeHistory_split = explode(';', $currentLikeHistory);

            for($i=0; $i<count($currentLikeHistory_split); $i++){

                push_log('읽기화면) '.$i.'번째 좋아요 글의 db id: '.$currentLikeHistory_split[$i]);
                if($currentLikeHistory_split[$i] == $episode_db_id){
                    $isAlreadyLiked=true;
                    push_log('읽기화면) 이미 좋아요 목록에 있음');
                }
            }
        }
        push_log('읽기화면) 최종확인: isAlreadyLiked='.$isAlreadyLiked);


        //북마크 값 확인
        if($currentBookmark == null){ //북마크 목록이 비어있으면 -> isAlreadyBookmarked=false로 내비둠
            push_log('읽기화면) 북마크 목록이 비어있음');

        }else{ //북마크 목록에 값이 있으면 -> 이 글에 북마크를 이미 누른 상태인지 확인한다
            push_log('읽기화면) 북마크 목록에 값이 있음. 이 글에 북마크를 이미 누른 상태인지 확인한다');

            $currentBookmark_split = explode(';', $currentBookmark);

            for($i=0; $i<count($currentBookmark_split); $i++){

                push_log('읽기화면) '.$i.'번째 북마크 글의 db id: '.$currentBookmark_split[$i]);
                if($currentBookmark_split[$i] == $episode_db_id){
                    $isAlreadyBookmarked=true;
                    push_log('읽기화면) 이미 북마크 목록에 있음');
                }
            }
        }
        push_log('읽기화면) 최종확인: isAlreadyBookmarked='.$isAlreadyBookmarked);

    }




    //글 삭제 버튼을 누르면
    if(isset($_POST['post_delete_btn'])){

        //episode db에서 해당 데이터 삭제
        $sql_episodeInfo = "DELETE FROM ".$sql_tableName." WHERE id='$episode_db_id'";
        mysqli_query($db, $sql_episodeInfo);

        if($board_name == 'fiction'){
            //story db에서 episode 개수 수정
            $sql_storyInfo = "UPDATE novelProject_storyInfo SET numberOfEpisode= numberOfEpisode - 1 WHERE id='$story_db_id'";
            mysqli_query($db, $sql_storyInfo);
        }

        header("location: ../index.php");
    }

    //글 수정 버튼을 누르면
    if(isset($_POST['post_edit_btn'])){

        //수정 화면으로 이동(GET 방식으로 전달)
        if($board_name == 'fiction'){
            header("location: page_writeNewEpisode.php?id=$story_db_id&ep_id=$episode_db_id&mode=edit");
        }else{
            header("location: page_writeNewPost.php?board=$board_name&id=$episode_db_id&mode=edit");
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
    //    header("location: ../index.php"); //redirect
    }


    //공유기능에 필요한 부분
    //현재 url
    $http_host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $current_page_url = 'http://' . $http_host . $request_uri;

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!--    stylesheets-->
    <link rel="stylesheet" href="../css/write/button.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>ReadMe</title>

    <script src="global.js"></script>
<!--    <link rel="stylesheet" href="../css/comment.css">-->
    <link rel="stylesheet" href="../css/comment_styles.css">
    <style>
        img{
            max-width: 100%;
            height: auto !important;
        }
    </style>


</head>
<body>
    <div style="padding:20px 40px; background-color: #343a40">
        <header class="blog-header">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-8">
                    <a class="blog-header-logo" style="color:lightgrey; font-size: 30px; font-family: Times New Roman; text-transform: initial" href="../index.php">ReadMe</a>
                    <?php
                    if($board_name=='fiction'){
                     echo'
                     <a class="blog-header-logo" style="color:lightgrey; font-size: 30px; font-family: Times New Roman; text-transform: initial" href="page_TableOfContents.php?id='.$story_db_id.'">
                        | '.$storyTitle.'</a>';
                    }else{
                        echo '
                        <a class="blog-header-logo" style="color:lightgrey; font-size: 30px; font-family: Times New Roman; text-transform: initial" href="mainPage_nonFiction.php?board='.$board_name.'">
                        | '.$board_name.'</a>
                        ';
                    }
                    ?>
                </div>

                <div>

                    <?php
                    if(isset($_SESSION['email'])){
                        echo '
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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



    <main role="main" class="container" style="margin-top: 100px;">
        <div class="col-9" style="margin:0px auto">
    <!--            글 내용-->
            <div class="content" style="margin-bottom: 100px">


                <!--<div style="width: 100%; margin: 0px auto;">-->
                <!--    --><?php
                //    //    echo '<h1>('.var_dump($id).')</h1>';
                //    $query = "SELECT*FROM blog_post WHERE id ='$id'";
                //    $result = mysqli_query($db,$query);
                //    $row = mysqli_fetch_array($result);
                //    ?>

<!--                <div style="font-size: 20px; font-family: 'Times New Roman'; color: grey;">Lord of the Rings by JRR Tolkin</div>-->
                <h3 style="border-bottom:1px solid rgba(1,1,1,0.2); font-family: Arial, Helvetica, sans-serif; word-break:break-all;"><?php echo $episodeTitle?></h3>
                <?php

                //글쓴이만 수정삭제 가능
                if(isset($_SESSION['email'])&& $_SESSION['email']==$author_email){
                    echo '
                    <div style="float:right; margin-top: 20px;">
                    <form method="post" action="">
                        <button class= "btn btn-outline-secondary" type="submit" name="post_edit_btn">Edit</button>
                        <button class= "btn btn-outline-warning" type="submit" name="post_delete_btn">Delete</button>
                    </form>
                </div>';
                }
                ?>

                    <div style="margin-top: 20px;">
                        <p>by <?php echo $author_username?><br></p>
                    </div>
                    <div style="color: #b2b2b2; margin-top: 10px;">
                        <p>Published: <?php echo $publishedTime?>
                        <br>
                        <?php
                        if(isset($row['editTime'])){
                            echo $row['editTime']." [edit]";
                        }
                        ?></p>
                    </div>
                <div style="margin-top: 70px; word-break:break-all;">
                    <?php echo $content?>
                </div>
            </div>


<!--            각종 버튼-->


            <nav class="nav d-flex justify-content-center" style="margin:0px auto; width:90%;">
                <input type="hidden" id="board_name" value="<?php echo $board_name?>">

                <?php
                    if($isAlreadyLiked==true){
                        echo '<button type="button" class="btn-like liked" id="like" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                <i class="fa fa-heart"></i><span id="like_span">Like ('.$numberOfLikes.')</span>
                             ';
                    }else{
                        echo '<button type="button" class="btn-like" id="like" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                <i class="fa fa-heart"></i><span id="like_span">Like ('.$numberOfLikes.')</span>
                             ';
                    }
                    ?>

                </button>
                    <?php
                    if($isAlreadyBookmarked==true){
                        echo '<button type="button" class="btn-like liked" id="bookmark" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                    <i class="fa fa-bookmark"></i><span id="bookmark_span">Bookmark ('.$numberOfBookmarks.')</span>
                                 ';
                    }else{
                        echo '<button type="button" class="btn-like" id="bookmark" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                    <i class="fa fa-bookmark"></i><span id="bookmark_span">Bookmark ('.$numberOfBookmarks.')</span>
                                 ';
                    }
                    ?>

                </button>
                <button type="button" class="btn-like" data-db_id="<?php echo $episode_db_id?>" id="share" data-toggle="modal" data-target="#modal_share">
                    <i class="fa fa-share-alt"></i>
                    <span>Share</span>
                </button>

<!--                <a class="p-3 text-muted" style="font-size: 20px;" href="#">Subscribe</a>-->
<!--                <a class="p-3 text-muted" style="font-size: 20px" href="#">Support</a>-->
            </nav>


<!--            글의 접근 경로가 다양해서 back 버튼 지움-->
<!--            <div style="text-align: center; margin-top: 50px">-->
<!--                <button class="btn btn-outline-info" type="button" onclick="location.href='main.php'">Back to List</button>-->
<!--            </div>-->


<!--댓글-->
            <div id="comment_body" style="margin-top:50px; margin-bottom: 100px">

                <!--        댓글을 작성하는 폼-->
                <form class="comment_form">

<!--                    이 글이 저장된 db id / 게시판 이름을 form에 숨겨놓음-->
                    <input type="hidden" name="episode_db_id" id="episode_db_id" value="<?php echo $episode_db_id?>">
                    <input type="hidden" id="board_name_comment" value="<?php echo $board_name?>">

                    <?php
                    //로그인을 해야 댓글 작성 가능
                    if(isset($_SESSION['user'])){
                        echo'
                        <div>
                            <label for="comment">Comments</label>
                            <textarea name="comment" id="comment" cols="30" rows="5"></textarea>
                        </div>
                        <button type="button" id="submit_btn" style="background-color: #343a40">POST</button>
                        <button type="button" id="update_btn" style="display: none;">UPDATE</button>
                     ';}else{
                        echo'<div>You must sign-in to leave comments</div>';
                    }?>

                </form>
                <?php //db에 저장되어 있는 댓글을 화면에 출력한다. $comments 는 server.php의 변수이다.

//                global $comments;
//                echo $comments
                ?>

            </div>


        </div>

    </main><!-- /.container -->



    <a id="footer"></a>
</body>


</html>

<div class="modal fade" id="modal_share" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Share the post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>
                <div class="modal-body">
                    <input style="width: 100%; white-space: nowrap;"
                            type="text" name="action" id="input_url" value="<?php echo $current_page_url?>" readonly/>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="copy_url_btn" onclick="copy_to_clipboard()" class="btn btn-primary">Copy to Clipboard</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="scripts.js"></script><!--submit 버튼을 클릭했을 때, ajax로 서버에 해당 댓글의 정보를 보낸다-->

<!--클립보드로 복사-->
<script>
    function copy_to_clipboard() {
        var copyText = document.getElementById("input_url");
        copyText.select();
        document.execCommand("Copy");
    }
</script>


<script>
    $(document).ready(function(){

        $('.btn-like').click(function(){

            let board = $('#board_name').val();
            var identity = $(this).attr('id'); //.btn-like 클래스를 가진 element가 3개 있는데, 그 중에 어떤 버튼인지 확인
            var db_id = $(this).data('db_id'); // episodeInfo table 에서 이 글의 id

            console.log('board='+board);

            if(board == 'non-fiction'){
                board = 'nonfiction';
            }

            if(identity=='like' || identity=='bookmark'){

                console.log(identity+' btn is clicked. episode_db_id='+db_id);

                $.ajax({
                    url: 'button_server.php', //서버측에서 가져올 페이지
                    type: 'POST', //통신타입 설정. GET 혹은 POST. 아래의 데 이터를 get 방식으로 넘겨준다.
                    data: { //서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                        'button':1,
                        'board' : board,
                        'identity': identity,
                        'episode_db_id': db_id
                    },
                    //http 요청 성공 시 발생하는 이벤트
                    success: function(response){
                        console.log('response: '+response);

                        var result = response.split(';')[0];
                        var number = response.split(';')[1];

                        console.log('result'+result+'number'+number);

                        switch(result){
                            //success -> 버튼 색 바꿔주기 + 처리 되었다고 알림 띄워주기

                            case 'like':
                                $('#like').toggleClass('liked');
                                console.log('like succeeded');
                                // alert('Liked')
                                $("#like_span").text("Like (" + number +")");
                                break;

                            case 'unlike':
                                $('#like').toggleClass('liked');
                                $("#like_span").text("Like (" + number +")");
                                // alert('Unliked')
                                break;



                            case 'bookmark':
                                $('#bookmark').toggleClass('liked');
                                $("#bookmark_span").text("Bookmark (" + number +")");
                                console.log('bookmark succeeded');
                                // alert('Bookmarked')
                                break;

                            case 'unbookmark':
                                $('#bookmark').toggleClass('liked');
                                $("#bookmark_span").text("Bookmark (" + number +")");
                                // alert('Unbookmarked')
                                break;



                            case 'login':
                                console.log('login is needed');
                                alert('Please sign-in.')
                                break;
                        }

                    }
                });

            }else{

                //url 보여주기
                console.log('share btn is clicked');
                // alert('Share this Story!')
            }

        });


    });

</script>