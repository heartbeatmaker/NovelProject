<?php
    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;
    accessLog();

    //편집 모드인지 확인한다
    $isEditMode='';
    if(isset($_GET['mode'])&&$_GET['mode']=='edit'){
        $isEditMode=true;
    }

    //편집모드: 이미 저장된 글을 고치는 것이기 때문에, 해당 episode의 db id를 get방식으로 가져온다
    $episode_db_id_inEditMode='';
    $title_retrieved='';
    $content_retrieved='';
    if(isset($_GET['ep_id'])){
        $episode_db_id_inEditMode=$_GET['ep_id'];

        $query_retrieveSavedContent = "SELECT*FROM novelProject_episodeInfo WHERE id ='$episode_db_id_inEditMode'";
        $result_retrieveSavedContent = mysqli_query($db,$query_retrieveSavedContent);
        $row_retrieveSavedContent = mysqli_fetch_array($result_retrieveSavedContent);

        $title_retrieved = $row_retrieveSavedContent['title'];
        $content_retrieved = $row_retrieveSavedContent['content'];
    }


    //공통: 이 episode가 속한 story가 story db에 어떤 id로 저장되어 있는지, get방식으로 받아온다
    $story_db_id='';
    $board_name='';
    if(isset($_GET['id'])){
        $story_db_id = $_GET['id'];
        $board_name='fiction';
    }


    //story 정보를 가져온다
    $sql = "SELECT*FROM novelProject_storyInfo WHERE id='$story_db_id'";

    global $db;
    $result = mysqli_query($db, $sql);

    $storyTitle='';
    $storyGenre='';//편집모드에서는 필요x
    $author_username=''; //편집모드에서는 필요x
    $author_email=''; //편집모드에서는 필요x
    $numberOfEpisode=''; //편집모드에서는 필요x
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result);

        $storyTitle = $row['title'];
        $storyGenre=$row['genre'];
        $author_username=$row['author_username'];
        $author_email=$row['author_email'];
        $numberOfEpisode=$row['numberOfEpisode']+1;
    }


    //글 작성 후 확인버튼을 눌렀을 때
    if(isset($_POST['btn_submit'])){

        $episodeTitle = $_POST['title'];
        $content = $_POST['content'];

        $time = date("Y-m-d H:i:s");
        $date = date("Y/m/d");

        push_log('episodeTitle='.$episodeTitle);
        push_log('content='.$content);
        push_log('storyTitle='.$storyTitle);
        push_log('author_email='.$author_email);
        push_log('author_name='.$author_username);
        push_log('time='.$time);
        push_log('date='.$date);
        push_log('noOfEpisodes + 1='.$numberOfEpisode);


        if($isEditMode == true){ //편집모드일때 - story db 업데이트, episode db 업데이트

            //story db - 마지막 업데이트 시각 수정
            $sql_storyInfo = "UPDATE novelProject_storyInfo SET lastUpdate='$date' WHERE id='$story_db_id'";

            //episode db - 제목, 내용, 수정시각 수정
            $sql_episodeInfo = "UPDATE novelProject_episodeInfo SET title='$episodeTitle', content='$content', editDate='$time' WHERE id='$episode_db_id_inEditMode'";


            $result_storyDB_edit = mysqli_query($db, $sql_storyInfo) or die(mysqli_error($db));
            $result_episodeDB_edit = mysqli_query($db, $sql_episodeInfo) or die(mysqli_error($db));


            if($result_storyDB_edit){
                push_log('edit) story query succeeded');

                if($result_episodeDB_edit){

                    $inserted_id = mysqli_insert_id($db);
                    push_log('edit) episode query succeeded');

                    //글 확인창으로 이동
                    header("location: read_post.php?board=$board_name&ep_id=$episode_db_id_inEditMode");


                }else{
                    push_log('edit) error: episode');
                }
            }else{
                push_log('edit) error: story');
            }


        }else{ //새로 글 쓸 때 - story db 업데이트, episode 저장

            $initial_number=0;

            $sql_episodeInfo = "INSERT INTO novelProject_episodeInfo(genre, title, content, storyTitle, author_email, author_username, date, story_db_id, numberOfViews, numberOfComments, numberOfLikes, bookmark)VALUES
    ('$storyGenre','$episodeTitle','$content','$storyTitle','$author_email','$author_username','$time','$story_db_id', 0, 0, 0, 0)";

            $sql_storyInfo = "UPDATE novelProject_storyInfo SET lastUpdate='$date', numberOfEpisode='$numberOfEpisode' WHERE id='$story_db_id'";

            //story db 업데이트
            $result_storyDB = mysqli_query($db, $sql_storyInfo);

            //episode 저장
            $result_episodeDB = mysqli_query($db, $sql_episodeInfo) or die(mysqli_error($db));


            if($result_storyDB){
                push_log('story query succeeded');

                if($result_episodeDB){

                    $inserted_id = mysqli_insert_id($db);
                    push_log('episode query succeeded. db id='.$inserted_id);

                    header("location: read_post.php?board=$board_name&ep_id=$inserted_id"); //redirect

                }else{
                    push_log('error: episode');
                }
            }else{
                push_log('error: story');
            }
        }




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
    <link href="../css/write/form-validation.css" rel="stylesheet">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="../ckeditor/ckeditor.js"></script>

    <title>ReadMe | Fiction</title>
</head>
<body>


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-8" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | <?php echo $storyTitle?>
            </div>
            <div>
                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='../index.php'">Cancel</button>
            </div>
        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

        <!--        세부사항-->
        <div class="col-md-10 blog-main" style="margin:0px auto">

            <?php
            if($isEditMode==true){
                echo'
                  <h4 style="margin-bottom: 20px">Edit Mode</h4>
                ';
            }
            ?>
            <form method="post" action="" class="needs-validation" novalidate>

                <div>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                           value="<?php echo $title_retrieved?>" required>
                    <div class="invalid-feedback">
                        Title is required.
                    </div>
                </div>

                <div class="mb-3" style="margin-top: 30px;">
                    <textarea type="text" class="form-control" name="content" id="content" required><?php echo $content_retrieved?></textarea>
                    <div class="invalid-feedback">
                        Content is required.
                    </div>
                </div>

                <button class="btn btn-info btn-lg btn-block" name="btn_submit" value="true" type="submit" style="margin-top: 50px">Publish</button>
            </form>





        </div><!-- /.blog-main -->



</main><!-- /.container -->


</body>

<script>
    CKEDITOR.replace('content');
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