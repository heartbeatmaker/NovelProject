<?php

    require_once '../connect.php';
    require_once 'functions.php';

    //앞에서 form 에 담아 보내지 않았으므로, GET을 써야함
    $id = $_GET['id'];

    //글쓰기 완료 버튼을 누르면
    if(isset($_POST['submit_btn'])) {

        $id = $_POST['id'];

        $title = ($_POST['title']);
        $content = ($_POST['content']);
        $update_time = date('Y-m-d H:i:s'); //수정시각


        //내용 업데이트(수정시각, 제목, 내용)
        $sql_info = "UPDATE blog_post SET title='$title', content='$content', update_time='$update_time' 
        WHERE id='$id'";
        mysqli_query($db, $sql_info);

        //글 읽는 창으로 이동
        header("location: read_post.php?id=".$id."");
    }

    //취소 버튼을 누르면 -> 이전 화면(글 읽기)로 이동
    if(isset($_POST['cancel_btn'])) {

        $id = $_POST['id'];

        header("location: read_post.php?id=".$id."");
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
    <link rel="stylesheet" href="../css/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="../css/blog.css">

    <title>Software Developer YonJu</title>

    <script src="../ckeditor/ckeditor.js"></script>
    <!--    <script src="../ckeditor/adapters/jquery.js"></script> 필요한가?-->
</head>
<body>

<div class="wrapper">

    <div class="content_wrapper">


        <nav class="navbar navbar-expand-lg navbar-light bg-secondary" style="height:60px">
            <!--                <a class="navbar-brand" href="#">Navbar</a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active" style="padding-right: 20px; padding-left: 20px">
                        <a class="nav-link" href="../home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown" style="padding-right: 25px">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Career
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="../portfolio/portfolio.php">Portfolio</a>
                            <a class="dropdown-item" href="../portfolio/cv.html">CV</a>
                        </div>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="#">Study</a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="main.php">Blog</a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="../contact/contact.php">Contact</a>
                    </li>
                    <?php
                    if(isset($_SESSION['user'])){
                        echo '<form action="" method="post"><button name="signout_btn" class="login_button">Sign-out</button></form>';
                    }else{
                        echo '<a class="nav-link" href="/login/login.php">Sign-in</a>';
                    }
                    ?>
                </ul>
            </div>
        </nav>

        <div style="width:80%; height:auto; margin: 0px auto;">
            <h1>Edit Post</h1><br><br>

            <?php
            $query = "SELECT*FROM blog_post WHERE id ='$id'";
            $result = mysqli_query($db,$query);
            $row = mysqli_fetch_array($result);
            ?>


            <form action="edit_post.php" method="post">
                <!-- required: 공백 허용x -->
                <input type="text" name="title" placeholder="TITLE" style="width:100%" value = "<?php echo $row['title']?>"><p></p>
                <textarea name="content" id="content" placeholder="Content"><?php echo $row['content']?></textarea>
                <br>
                <input type="hidden" name="id" value = "<?php echo $id?>">
                <!-- id를 숨겨서 post로 보낸다-->
                <div style="float:right">
                    <button type="submit" name="submit_btn" style="width:150px; height:50px; margin-right: 20px">Submit</button>
                    <button type="submit" name="cancel_btn" style="width:150px; height:50px;">Cancel</button>
                </div>
            </form>
        </div>

    </div>

</div>

<script>
    CKEDITOR.replace('content');
</script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<br><br><br>
<div class="line"></div>
<br><br><br>
<footer style="text-align: center;">
</footer>
<br><br>
</body>
