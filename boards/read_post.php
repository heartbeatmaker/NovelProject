<?php
//
//require_once '../session.php'; //db에 연결, timezone 설정
//require_once 'functions.php'; //참조할 메소드가 있는 곳
//
//include 'server.php';//댓글 저장을 담당
//
//
////앞에서 form 에 담아 보내지 않았으므로, GET을 써야함
//$id = $_GET['id'];
//
////글 삭제 버튼을 누르면
//if(isset($_POST['post_delete_btn'])){
//
//    //db에서 해당 데이터 삭제 후, 글 목록으로 이동
//    $sql_info = "DELETE FROM blog_post WHERE id='$id'";
//    mysqli_query($db, $sql_info);
//
//    header("location: main.php");
//
//    //글 수정 버튼을 누르면
//}else if(isset($_POST['post_edit_btn'])){
//
//    //수정 화면으로 이동(GET 방식으로 전달)
//    header("location: edit_post.php?id=$id");
//
//    //글 목록 버튼을 누르면
//}
//
//
//?>

<!DOCTYPE html>
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

    <script src="global.js"></script>
    <link rel="stylesheet" href="../css/comment.css">
    <link rel="stylesheet" href="../css/comment_styles.css">


</head>
<body>
    <div class="container" style="margin-top: 20px">
        <header class="blog-header">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4">
                    <a class="blog-header-logo text-dark" style="font-size: 30px; font-family: Times New Roman;" href="../index.php">ReadMe</a>
                </div>

                <form class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <a class="text-muted" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                    </a>
                </form>
                <button class="btn btn-outline-info my-2 my-sm-0" onclick="location.href='../login/login.php'" style="margin-right: 20px">Sign-in</button>
            </div>
        </header>

    </div>



    <main role="main" class="container" style="margin-top: 50px;">
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

                <div style="font-size: 20px; font-family: 'Times New Roman'; color: grey;">Lord of the Rings by JRR Tolkin</div>
                <h1><?php echo "Title: ".$row['title']?></h1>
                <?php
                //관리자만 글 수정삭제 가능
                if(isset($_SESSION['email'])&& $_SESSION['email']=='admin@gmail.com'){
                    echo '
                    <div style="float:right; margin-top: 20px;">
                    <form method="post" action="">
                        <button class= "btn btn-outline-secondary" type="submit" name="post_edit_btn">Edit</button>
                        <button class= "btn btn-outline-warning" type="submit" name="post_delete_btn">Delete</button>
                    </form>
                </div>';
                }
                ?>

                <div style="float:right; margin-top: 20px;">
                    <form method="post" action="">
                        <button class= "btn btn-outline-secondary" type="submit" name="post_edit_btn">Edit</button>
                        <button class= "btn btn-outline-warning" type="submit" name="post_delete_btn">Delete</button>
                    </form>
                </div>
                <div style="color: #b2b2b2; margin-top: 20px;">
                    <p>2019.11.2<br>
                        2019.11.5 [edit]</p>
                </div>

                   <div style="color: #b2b2b2; margin-top: 20px;">
                        <p><?php echo $row['date']?>
                        <br>
                        <?php
                        if(isset($row['update_time'])){
                            echo $row['update_time']." [edit]";
                        }
                        ?></p>
                   </div>
                <div>

                    Qabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;abaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjet;slabaslkjetsl

                    <?php echo $row['content']?>
                </div>
            </div>


<!--            각종 버튼-->


            <nav class="nav d-flex justify-content-center bg-light" style="margin:0px auto; width:90%;">
                <a class="p-3 text-muted" style="font-size: 20px" href="#">Like</a>
                <a class="p-3 text-muted" style="font-size: 20px" href="#">Bookmark</a>
                <a class="p-3 text-muted" style="font-size: 20px" href="#">Subscribe</a>
                <a class="p-3 text-muted" style="font-size: 20px" href="#">Support</a>
            </nav>

            <div style="text-align: center; margin-top: 50px">
                <button class="btn btn-outline-info" type="button" onclick="location.href='main.php'">Back to List</button>
            </div>


<!--댓글-->
            <div style="margin-top:50px; margin-bottom: 100px">

                <!--        댓글을 작성하는 폼-->
                <form class="comment_form">
                    <div>
                        <label for="name"></label>
                        <input type="hidden" name="post_id" id="post_id" value="<?php echo $id?>">
                    </div>
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
                    <!--        글의 id를 댓글 form에 포함시킨다-->
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



</body>


</html>
<script src="scripts.js"></script><!--submit 버튼을 클릭했을 때, ajax로 서버에 해당 댓글의 정보를 보낸다-->
<script src="jquery.min.js"></script>
