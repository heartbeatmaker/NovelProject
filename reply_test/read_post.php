<?php

require_once '../connect.php'; //db에 연결, timezone 설정
require_once 'functions.php'; //참조할 메소드가 있는 곳

include 'server.php';//댓글 저장을 담당


//앞에서 form 에 담아 보내지 않았으므로, GET을 써야함
$id = $_GET['id'];

//글 삭제 버튼을 누르면
if(isset($_POST['post_delete_btn'])){

    //db에서 해당 데이터 삭제 후, 글 목록으로 이동
    $sql_info = "DELETE FROM blog_post WHERE id='$id'";
    mysqli_query($db, $sql_info);

    header("location: main.php");

    //글 수정 버튼을 누르면
}else if(isset($_POST['post_edit_btn'])){

    //수정 화면으로 이동(GET 방식으로 전달)
    header("location: edit_post.php?id=$id");

    //글 목록 버튼을 누르면
}


?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>Software Developer YonJu</title>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <!--    script tag 는 두 가지 방향으로 사용 가능-->
    <!--    1. 위와 같이 외부에 있는 js를 불러와 사용 -->
    <!--    2. 아래와 같이 javascript 코드를 넣어 사용-->
    <script src="global.js"></script>
    <link rel="stylesheet" href="../css/comment.css">
    <link rel="stylesheet" href="../css/comment_styles.css">


</head>
<body>


<div style="width: 100%; margin: 0px auto;">
    <?php
    //    echo '<h1>('.var_dump($id).')</h1>';
    $query = "SELECT*FROM blog_post WHERE id ='$id'";
    $result = mysqli_query($db,$query);
    $row = mysqli_fetch_array($result);
    ?>

    <h1><?php echo "Title: ".$row['title']?></h1>
</div><br>


<div class="content">

    <div>
        <?php
        //관리자만 글 수정삭제 가능
        if(isset($_SESSION['email'])&& $_SESSION['email']=='admin@gmail.com'){
            echo '<div style="float:right;">
            <form method="post" action="">
                <button class= "button" type="submit" name="post_edit_btn">Edit</button>
                <button class= "button" type="submit" name="post_delete_btn">Delete</button>
            </form>
        </div>';
        }

        ?>
        <div style="color: #b2b2b2;">
            <?php echo $row['date']?>
            <br>
            <?php
            if(isset($row['update_time'])){
                echo $row['update_time']." [edit]";
            }
            ?>
        </div>
        <br><br>
        <div>
            <?php echo $row['content']?>
        </div>
    </div>
    <br><br><br><br>
    <div class="back">
        <button class="button_backToList" type="button" onclick="location.href='main.php'">Back to List</button>
    </div>

</div>
<br><br>
<div class="wrapper">

    <!--        댓글을 작성하는 폼-->
    <form class="comment_form">
        <div>
<!--            <label for="name">Name:</label>-->
<!--                            label: input태그만으로 선택하기 어려울 경우, 더 좋은 사용자 경험을 제공하기 위해 사용한다. -->
<!--                            for는 input 태그의 id와 함께 동작한다-->
<!--            <input type="text" name="name" id="name">-->
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


</body>


</html>
<script src="scripts.js"></script><!--submit 버튼을 클릭했을 때, ajax로 서버에 해당 댓글의 정보를 보낸다-->
<script src="jquery.min.js"></script>
