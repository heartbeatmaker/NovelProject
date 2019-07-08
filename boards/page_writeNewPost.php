<?php
//non fiction, community 글 쓰는 곳

require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';


$URL = "../login/login.php";
if(!isset($_SESSION['user'])) {
    ?>
    <script>
        alert("You must sign-in first.");
        location.replace("<?php echo $URL?>");
    </script>
    <?php
}


global $db;
accessLog();


//게시판 이름을 받아온다
//어느 게시판에서 글을 쓰는 것인지 알아야 함 - non-fiction or community
$board_name='';
if(isset($_GET['board'])){
    $board_name = $_GET['board'];
}


//게시판 이름에 따라, 테이블 이름 지정
if($board_name=='non-fiction'){
    $sql_tableName = 'novelProject_nonfiction';
}else if($board_name=='community'){
    $sql_tableName = 'novelProject_community';
}


//db에 저장된 해당 게시판의 장르를 가져온다
$genre ='';
$sql_genre = "SELECT*FROM novelProject_boardInfo WHERE name='$board_name'";

$result_genre = mysqli_query($db, $sql_genre);

if(mysqli_num_rows($result_genre)==1){
    $row_genre = mysqli_fetch_array($result_genre);
    $genre_string = $row_genre['category'];
}


//편집 모드인지 확인한다
$isEditMode='';
if(isset($_GET['mode'])&&$_GET['mode']=='edit'){
    $isEditMode=true;
}

//편집모드: 이미 저장된 글을 고치는 것이기 때문에, 해당 글의 db id를 get방식으로 가져온다
$db_id_inEditMode='';
$title_retrieved='';
$description_retrieved='';
$content_retrieved='';
$genre_retrieved='';
if(isset($_GET['id'])){
    $db_id_inEditMode=$_GET['id'];

    $query_retrieveSavedContent = "SELECT*FROM ".$sql_tableName." WHERE id ='$db_id_inEditMode'";
    $result_retrieveSavedContent = mysqli_query($db,$query_retrieveSavedContent);
    $row_retrieveSavedContent = mysqli_fetch_array($result_retrieveSavedContent);

    $title_retrieved = $row_retrieveSavedContent['title'];
    $content_retrieved = $row_retrieveSavedContent['content'];
    $genre_retrieved = $row_retrieveSavedContent['genre'];
    $description_retrieved = $row_retrieveSavedContent['description'];
}



//글 작성 후 확인버튼을 눌렀을 때
if(isset($_POST['btn_submit'])){

    $title = $_POST['title'];
    $content = $_POST['content'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];

    $time = date("Y-m-d H:i:s");
    $date = date("Y/m/d");

    $author_email = $_SESSION['email'];
    $author_username = $_SESSION['user'];


    //삽입한 이미지의 이름을 찾는다(세션에 배열로 저장해 놓음)
    $image_name='';
    $image_name_array = unserialize($_SESSION['image_name_array']);
    for($m=0; $m<count($image_name_array); $m++){

        if(strpos($content, $image_name_array[$m]) !== false) {
            push_log2('found image. name='.$image_name_array[$m]);

            $image_name = $image_name_array[$m];
            break;
        }
    }

    //사용자가 이미지를 삽입하지 않았다면 -> 디폴트 이미지를 저장한다
    if($image_name == ''){
        $image_name = 'default.jpg';
        push_log2('image not found');
    }else{
        //해당 세션 변수를 해지한다 - 굳이?
//        unset($_SESSION['image_name_array']);
    }


    if($isEditMode == true){ //편집모드일때 - db 업데이트

        //제목, 내용, 한줄소개, 장르, 수정시각, 이미지 수정
        $sql_editPost = "UPDATE ".$sql_tableName." SET title='$title', image='$image_name', description='$description', content='$content', genre='$genre', editDate='$time' WHERE id='$db_id_inEditMode'";

        $result_editPost = mysqli_query($db, $sql_editPost) or die(mysqli_error($db));


        if($result_editPost){

            $inserted_id = mysqli_insert_id($db);
            push_log('edit) post query succeeded');

            //글 확인창으로 이동
            header("location: read_post.php?board=$board_name&ep_id=$db_id_inEditMode");

        }else{
            push_log('edit) error: post');
        }


    }else{ //새로 글 쓸 때 - post 저장

        $sql_savePost = "INSERT INTO ".$sql_tableName." (image, genre, title, description, content, author_email, author_username, date, numberOfViews, numberOfComments, numberOfLikes, bookmark)VALUES
    ('$image_name', '$genre','$title', '$description', '$content','$author_email','$author_username','$time', 0, 0, 0, 0)";

        //글 저장
        $result_savePost = mysqli_query($db, $sql_savePost) or die(mysqli_error($db));

        if($result_savePost){

            $inserted_id = mysqli_insert_id($db);
            push_log('post query succeeded. db id='.$inserted_id);

            header("location: read_post.php?board=$board_name&ep_id=$inserted_id"); //redirect

        }else{
            push_log('error: post');
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

    <!--    stylesheets-->
    <link href="../css/write/form-validation.css" rel="stylesheet">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="../ckeditor/ckeditor.js"></script>

    <title>ReadMe | New Post</title>
</head>
<body>


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-8" style="font-size: 30px; font-family: Times New Roman; text-transform: initial">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | <?php echo $board_name?>
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

<!--            장르 선택-->
            <div style="width:30%; margin-bottom: 20px">
                <select class="custom-select d-block w-100" id="genre" name="genre" required>
                    <option value=""><?php
                        if($isEditMode==true){
                            echo $genre_retrieved;
                        }else{
                            echo 'Choose genre';
                        }
                        ?></option>

                    <?php
                    //string으로 이어서 가져온 장르를 개별로 분할하여 화면에 출력한다
                    $genre_split_array = explode(';', $genre_string);

                    for($i=0; $i<count($genre_split_array); $i++){
                        echo '<option>'.$genre_split_array[$i].'</option>';
                    }
                    ?>

                </select>
                <div class="invalid-feedback">
                    Please select a valid country.
                </div>
            </div>



            <div>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title here."
                       style="height:60px; font-size: 30px; font-family: 'Times New Roman';" value="<?php echo $title_retrieved?>" required>
                <div class="invalid-feedback">
                    Title is required.
                </div>
            </div>

            <div>
                <input type="text" class="form-control" name="description" id="description" placeholder="Describe your topic, or add tags related to it."
                       style="margin-top: 20px; font-size: 20px; font-family: 'Times New Roman';" value="<?php echo $description_retrieved?>" required>
                <div class="invalid-feedback">
                    Description is required.
                </div>
            </div>

            <div class="mb-3" style="margin-top: 30px;">
                <textarea type="text" class="form-control" name="content" id="content" required><?php echo $content_retrieved?></textarea>
                <div class="invalid-feedback">
                    Content is required.
                </div>
            </div>

            <div id="warning"></div>
            <button class="btn btn-info btn-lg btn-block" name="btn_submit" value="true" type="submit" style="margin-top: 50px">Publish</button>
        </form>



    </div><!-- /.blog-main -->


</main><!-- /.container -->


</body>

<script>
    CKEDITOR.replace('content');
</script>


</html>