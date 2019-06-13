<?php
require_once '../connect.php';

if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}

$query = "select*from blog_post";
$result = mysqli_query($db,$query);
$number_of_results = mysqli_num_rows($result); //결과 행의 갯수

$results_per_page = 8;
$number_of_pages = ceil($number_of_results/$results_per_page);
//페이지마다 몇번째 행부터 데이터를 출력할 지
$start_from = ($page - 1)*$results_per_page;

$sql = "SELECT*FROM blog_post LIMIT ".$start_from .",".$results_per_page;
$result = mysqli_query($db, $sql);

if(isset($_POST['signout_btn'])){

    $email = $_SESSION['email'];

    //해당 사용자의 db정보를 수정한다
    $query_deleteInfo = "UPDATE userinfo SET session_id=null WHERE email='$email'";
    mysqli_query($db, $query_deleteInfo);

    $_SESSION = array(); //세션 변수 전체를 초기화한다

    echo "<script>alert(\"로그아웃 되었습니다\");</script>";

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
</head>
<body>

<div class="wrapper">

    <div class="content_wrapper">


        <nav class="navbar navbar-expand-lg navbar-light bg-secondary" style="height:60px">
            <!-- <a class="navbar-brand" href="#">Navbar</a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active" style="padding-right: 20px; padding-left: 20px">
                        <a class="nav-link" href="../home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="../portfolio/portfolio.php">Portfolio</a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item dropdown" style="padding-right: 25px">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Contact
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="../contact/contact.php">E-mail</a>
                            <a class="dropdown-item" href="../contact/chat.php">Chat</a>
                        </div>
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

        <div style="width:80%; margin:0px auto;">
            <!-- <a href="#" class="btn btn-success" id="menu-toggle">Toggle Menu</a>-->
            <h1>Blog</h1><br>

            <?php
            //관리자만 새 글 작성 가능
            if(isset($_SESSION['email'])&& $_SESSION['email']=='admin@gmail.com'){
                echo '<div class="button" style="margin-right:0" onclick="location.href=\'new_post.php\'">
<span>New Post</span></div><br><br>';
            }?>

            <div style="width:25%; float:left; padding-right: 40px; word-break:break-all;">
                <h4>Category</h4><br>
                <div><a class="nav-link" href="#" style="font-size: 25px; color:#2c3e50">-Java</a></div>
                <div><a class="nav-link" href="#" style="font-size: 25px; color:#2c3e50">-Android</a></div>
                <div><a class="nav-link" href="#" style="font-size: 25px; color:#2c3e50">-PHP</a></div>
                <div><a class="nav-link" href="#" style="font-size: 25px; color:#2c3e50">-Javascript</a></div>
                <div><a class="nav-link" href="#" style="font-size: 25px; color:#2c3e50">-Diary</a></div>

            </div>
            <div style="width:75%; float:left;">
                <div style="width:100%;" >

                    <?php while($row = mysqli_fetch_array($result)){ ?>
                        <div onclick="location.href='read_post.php?id=<?php echo $row['id']?>'" style="text-align: left;" >
                            <!-- <img src="../images/sky.jpg"/>-->
                            <div class="desc" style="width:75%; float:left; font-size: 20px">#<?php echo $row['id']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['title']?></div>
                            <div style="width:25%; float:left; font-size:15px; color:grey; margin-bottom: 0px; text-align: right">
                                <?php
                                $full_date = new dateTime($row['date']);
                                $post_date = date_format($full_date, 'Y. m. j');
                                echo $post_date?></div>
                        </div>
                    <?php } ?>

                </div>

                <div style="float:left; width:100%">

                    <?php

                    echo "<div class='pagination'>";
                    //페이지에 대한 링크를 보여준다


                    if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                        if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

//1~5페이지를 보여준다
                            for ($i=1; $i<=5; $i++){
                                if($i==$page){
                                    echo '<a class="active">'. $i .'</a>';
                                }else{
                                    echo '<a href="main.php?page=' . $i . '">'.$i.'</a>';
                                }
                            }

                            echo '<a href="main.php?page='.$number_of_pages.'">Last</a>';

                        }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

//첫 페이지로 가는 버튼
                            echo '<a href="main.php?page=1">First</a>';

// 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                            for ($i=$page-2; $i<=$page+2; $i++){
                                if($i==$page){
                                    echo '<a class="active">'. $i .'</a>';
                                }else{
                                    echo '<a href="main.php?page=' . $i . '">'.$i.'</a>';
                                }
                            }

//마지막 페이지로 가는 버튼
                            echo '<a href="main.php?page='.$number_of_pages.'">Last</a>';

                        }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

//첫 페이지로 가는 버튼
                            echo '<a href="main.php?page=1">First</a>';

//마지막에서 5개 페이지를 보여준다
                            for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                                if($i==$page){
                                    echo '<a class="active">'. $i .'</a>';
                                }else{
                                    echo '<a href="main.php?page=' . $i . '">'.$i.'</a>';
                                }
                            }

                        }

                    }else{
                        for ($i=1; $i<=$number_of_pages; $i++){
                            if($i==$page){
                                echo '<a class="active">'. $i .'</a>';
                            }else{
                                echo '<a href="main.php?page=' . $i . '">'.$i.'</a>';
//href 뒤의 주소로 이동한다(n번째 페이지)
                            }
                        }
                    }


                    echo "</div>";
                    ?>

                </div>
            </div>

        </div>
    </div>

</div>


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
</html>