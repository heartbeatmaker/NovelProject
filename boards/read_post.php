<?php
    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    require_once 'comment_server.php';

//    var_dump($_SESSION);
    $board_name = $_GET['board'];

    if($board_name=='fiction'){
        $sql_tableName = 'novelProject_episodeInfo';
    }

    //이 episode가 db에 어떤 id값으로 저장되어 있는지 get방식으로 받아온다
    $episode_db_id='';
    if(isset($_GET['ep_id'])){
        $episode_db_id = $_GET['ep_id'];
    }

    global $db;


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

    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result);

        $episodeTitle = $row['title'];
        $storyTitle = $row['storyTitle'];
        $content=$row['content'];
        $author_username=$row['author_username'];
        $author_email=$row['author_email'];
        $publishedTime =$row['date'];
        $story_db_id=$row['story_db_id'];

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
            $currentLikeHistory = $row_userInfo['likeHistory'];
            $currentBookmark = $row_userInfo['bookmarkHistory'];
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

        //story db에서 episode 개수 수정
        $sql_storyInfo = "UPDATE novelProject_storyInfo SET numberOfEpisode= numberOfEpisode - 1 WHERE id='$story_db_id'";
        mysqli_query($db, $sql_storyInfo);

        header("location: ../index.php");
    }

    //글 수정 버튼을 누르면
    if(isset($_POST['post_edit_btn'])){

        //수정 화면으로 이동(GET 방식으로 전달)
        header("location: page_writeNewEpisode.php?id=$story_db_id&ep_id=$episode_db_id&mode=edit");

    }

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


</head>
<body>
    <div class="container" style="margin-top: 20px">
        <header class="blog-header">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-8" >
                    <a class="blog-header-logo text-dark" style="font-size: 30px; font-family: Times New Roman; text-transform: initial" href="../index.php">ReadMe</a>
                    <a class="blog-header-logo text-dark" style="font-size: 30px; font-family: Times New Roman; text-transform: initial" href="page_TableOfContents.php?id=<?php echo $story_db_id?>">
                        | <?php echo $storyTitle?></a> by <?php echo $author_username?>
                </div>
                <?php
                if(isset($_SESSION['email'])){
                    echo '
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                '.$_SESSION['user'].'
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">My Page</a>
                                <form method="post" action=""><button class="dropdown-item" name="signout_btn" value="true">Sign-out</button></form>
                            </div>
                        </div>
                        ';
                }else{
                    echo '<button class="btn btn-outline-secondary" onclick="location.href=\'../login/login.php\'" style="margin-right: 20px">Sign-in</button>';
                }
                ?>
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
                <h3 style="border-bottom:1px solid rgba(1,1,1,0.2); font-family: Arial, Helvetica, sans-serif"><?php echo $episodeTitle?></h3>
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

                   <div style="color: #b2b2b2; margin-top: 20px;">
                        <p>Published: <?php echo $publishedTime?>
                        <br>
                        <?php
                        if(isset($row['editTime'])){
                            echo $row['editTime']." [edit]";
                        }
                        ?></p>
                   </div>
                <div style="margin-top: 70px">
                    <?php echo $content?>
                </div>
            </div>


<!--            각종 버튼-->


            <nav class="nav d-flex justify-content-center" style="margin:0px auto; width:90%;">
                    <?php
                    if($isAlreadyLiked==true){
                        echo '<button type="button" class="btn-like liked" id="like" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                <i class="fa fa-heart"></i>
                             ';
                    }else{
                        echo '<button type="button" class="btn-like" id="like" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                <i class="fa fa-heart"></i>
                             ';
                    }
                    ?>
                    <span>Like</span>
                </button>
                    <?php
                    if($isAlreadyBookmarked==true){
                        echo '<button type="button" class="btn-like liked" id="bookmark" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                    <i class="fa fa-bookmark"></i>
                                 ';
                    }else{
                        echo '<button type="button" class="btn-like" id="bookmark" data-db_id="'.$episode_db_id.'" style="margin-right: 40px">
                                    <i class="fa fa-bookmark"></i>
                                 ';
                    }
                    ?>
                    <span>Bookmark</span>
                </button>
                <button type="button" class="btn-like" data-db_id="<?php echo $episode_db_id?>" id="share">
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

<!--                    이 글이 저장된 db id를 form에 숨겨놓음-->
                    <input type="hidden" name="episode_db_id" id="episode_db_id" value="<?php echo $episode_db_id?>">

                    <?php
                    //로그인을 해야 댓글 작성 가능
                    if(isset($_SESSION['user'])){
                        echo'
                        <div>
                            <label for="comment">Comments</label>
                            <textarea name="comment" id="comment" cols="30" rows="5"></textarea>
                        </div>
                        <button type="button" id="submit_btn" >POST</button>
                        <button type="button" id="update_btn" style="display: none;">UPDATE</button>
                     ';}else{
                        echo'<div>You must sign-in to leave comments</div>';
                    }?>

                </form>
                <?php //db에 저장되어 있는 댓글을 화면에 출력한다. $comments 는 server.php의 변수이다.

                global $comments;
                echo $comments ?>
            </div>


        </div>

    </main><!-- /.container -->


    <!--        스크롤 맨 위로 올리는 버튼-->
<!--    <div class="gotop" style="position: fixed; bottom: 100px; right: 50px">-->
<!--        <a href class="btn btn-outline-info my-2 my-sm-0">Top</a>-->
<!--    </div>-->
<!--    <div class="gobottom" style="position: fixed; bottom: 50px; right: 50px">-->
<!--        <a href class="btn btn-outline-info my-2 my-sm-0">BTM</a>-->
<!--    </div>-->
<script>
$(document).ready(function(){

    $('.btn-like').click(function(){

        var identity = $(this).attr('id'); //.btn-like 클래스를 가진 element가 3개 있는데, 그 중에 어떤 버튼인지 확인
        var db_id = $(this).data('db_id'); // episodeInfo table 에서 이 글의 id

        if(identity=='like' | identity=='bookmark'){

            console.log(identity+' btn is clicked. episode_db_id='+db_id);

            $.ajax({
                url: 'button_server.php', //서버측에서 가져올 페이지
                type: 'POST', //통신타입 설정. GET 혹은 POST. 아래의 데이터를 get 방식으로 넘겨준다.
                data: { //서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                    'button':1,
                    'identity': identity,
                    'episode_db_id': db_id
                },
                //http 요청 성공 시 발생하는 이벤트
                success: function(response){
                    console.log('response: '+response);

                    switch(response){
                        //success -> 버튼 색 바꿔주기 + 처리 되었다고 알림 띄워주기

                        case 'like':
                            $('#like').toggleClass('liked');
                            console.log('like succeeded');
                            alert('Liked')
                            break;

                        case 'unlike':
                            $('#bookmark').toggleClass('liked');
                            alert('Unliked')
                            break;
                        case 'bookmark':
                            $('#bookmark').toggleClass('liked');
                            console.log('bookmark succeeded');
                            alert('Bookmarked')
                            break;

                        case 'unbookmark':
                            $('#bookmark').toggleClass('liked');
                            alert('Unbookmarked')
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
            alert('Share this Story!')
        }

    });


});

</script>


</body>


</html>
<script src="scripts.js"></script><!--submit 버튼을 클릭했을 때, ajax로 서버에 해당 댓글의 정보를 보낸다-->

