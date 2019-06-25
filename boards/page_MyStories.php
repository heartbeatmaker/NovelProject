<?php
require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';

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

if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}


if(isset($_SESSION['user'])){ //로그인 된 상태라면, 해당 사용자의 story를 db에서 가져온다

    $author_email=$_SESSION['email'];

    //이 사용자의 story가 총 몇 개인지 확인
    $sql_checkNumberOfRows = "SELECT*FROM novelProject_storyInfo WHERE author_email ='$author_email'";
    $result = mysqli_query($db, $sql_checkNumberOfRows);
    $number_of_results = mysqli_num_rows($result); //결과 행의 갯수

    $results_per_page = 10;//한 페이지당 10개로 제한
    $number_of_pages = ceil($number_of_results/$results_per_page);
    //페이지마다 몇번째 행부터 데이터를 출력할 지
    $start_from = ($page - 1)*$results_per_page;


    //10개씩만 가져온다
    $sql = "SELECT*FROM novelProject_storyInfo WHERE author_email ='$author_email' LIMIT ".$start_from .",".$results_per_page;
    $result = mysqli_query($db, $sql);

}else{ //로그인 되어있지 않으면, 로그인 페이지로 보낸다
    header("location: ../login/login.php"); //redirect
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
    <link rel="stylesheet" href="../css/write/items.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>ReadMe | Fiction</title>
</head>
<body>


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-8" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | My Stories
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
<!--            <div>-->
<!--                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='mainPage.php'">Cancel</button>-->
<!--            </div>-->
        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

    <div class="row">

        <div class="col-md-10" style="margin:0px auto">

            <div style="width:100%; text-align: right">
                <button class="btn btn-info" style="margin-bottom: 30px;"
                onclick="location.href='page_CreateNewStory.php'">+ New Story</button>
            </div>
            <h4>
                <?php
                if($number_of_results>1){
                    echo $number_of_results.' Stories';
                }else{
                    echo $number_of_results.' Story';
                }
                ?>
            </h4>


           <?php

           while($row = mysqli_fetch_array($result)){

               $db_id=$row['id'];
               $title=$row['title'];
//               $author_username=$row['author_username']; //익명인지 확인할 것
               $period=$row['startDate'].'~'.$row['lastUpdate'];
               $number_of_episode=$row['numberOfEpisode'];
               $isCompleted=$row['isCompleted'];

               //image 받아야 함

               if($isCompleted=='Y'){
                   $isCompleted='Completed';
               }else{
                   $isCompleted='inProgress';
               }


               //이 story에 총 몇 개의 like와 comment가 달렸는지 계산한다
               $sql_episode = "SELECT*FROM novelProject_episodeInfo WHERE story_db_id ='$db_id'";
               $result_episode = mysqli_query($db, $sql_episode);

               $numberOfComments=0;
               $numberOfLikes=0;
               while($row_episode = mysqli_fetch_array($result_episode)){

                   $numberOfComments+=$row_episode['numberOfComments'];
                   $numberOfLikes+=$row_episode['numberOfLikes'];
               }


               $randomNumber = generateRandomInt(25);
               $img_src = $randomNumber.'.jpg';

                echo
                    '<div class="list_item" style="margin-bottom: 20px" onclick="location.href=\'page_TableOfContents.php?id='.$db_id.'\'">
                        <div class="card flex-md-row box-shadow h-md-250">
                            
                            <div style="width:25%; float:left">
                                <img src="../images/bookCover_dummy/'.$img_src.'" style="border-radius: 0 3px 3px 0; width:110px; height:150px; margin: 20px 50px 20px" alt="Card image cap"/>
                                
                            </div>
                            
                            <div style="width:55%; float:left">
                                <div class="card-body d-flex flex-column align-items-start">
                                    <h3 class="mb-0">
                                        <a class="text-dark" >'.$title.'</a>
                                    </h3>
                                    <div class="mb-1 text-muted" style="margin-top: 10px">'.$period.'</div>
                                    <div style="margin-top: 10px">'.$number_of_episode.' Part Stories <strong>('.$isCompleted.')</strong></div>
                                    <div style="margin-top: 10px">'.$numberOfLikes.' likes | '.$numberOfComments.' comments</div>
                                </div>
                            </div>
                            
                         <div style="width:30%; float:left">
                         
                         </div>
                            
                        </div>   
                    </div>';
            }

//                         <div style="width:30%; float:left">
//                             <button class="btn btn-outline-success" style="margin-top: 30px; padding-left: 20px; padding-right: 30px">+ New Part</button>
//                         </div>
            ?>

        </div>

        <nav aria-label="Page navigation example" style="margin:0px auto; margin-top: 100px; padding-left: 100px; padding-right: 100px">
            <ul class="pagination">
                <?php
                if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                    if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                        //1~5페이지를 보여준다
                        for ($i=1; $i<=5; $i++){
                            if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_MyStories.php?page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                        //마지막 페이지로 가는 버튼
                        echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="page_MyStories.php?page='.$number_of_pages.'">Last</a></li>';

                    }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                        //첫 페이지로 가는 버튼
                        echo '<li class="page-item"><a class="page-link" href="page_MyStories.php?page=1">First</a></li><li class="page-item">. . .</li>';

                        // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                        for ($i=$page-2; $i<=$page+2; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_MyStories.php?page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                        //마지막 페이지로 가는 버튼
                        echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="page_MyStories.php?page='.$number_of_pages.'">Last</a></li>';

                    }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                        //첫 페이지로 가는 버튼
                        echo '<li class="page-item"><a class="page-link" href="page_MyStories.php?page=1">First</a></li><li class="page-item">. . .</li>';

                        //마지막에서 5개 페이지를 보여준다
                        for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_MyStories.php?page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                    }

                }else{
                    for ($i=1; $i<=$number_of_pages; $i++){
                        if($i==$page){
                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                        }else{
                            echo '<li class="page-item"><a class="page-link" href="page_MyStories.php?page=' . $i . '">'. $i .'</a></li>';
                        }
                    }
                }
                ?>



<!--                <li class="page-item"><a class="page-link" href="#">First</a></li>-->
<!--                <li class="page-item">. . .</li>-->
<!--                <li class="page-item"><a class="page-link" href="#">Previous</a></li>-->
<!--                <li class="page-item"><a class="page-link" href="#">1</a></li>-->
<!--                <li class="page-item"><a class="page-link" href="#">2</a></li>-->
<!--                <li class="page-item"><a class="page-link" href="#">3</a></li>-->
<!--                <li class="page-item"><a class="page-link" href="#">4</a></li>-->
<!--                <li class="page-item"><a class="page-link" href="#">5</a></li>-->
<!--                <li class="page-item"><a class="page-link" href="#">Next</a></li>-->
<!--                <li class="page-item">. . .</li>-->
<!--                <li class="page-item"><a class="page-link" href="#">Last</a></li>-->
            </ul>
        </nav>


    </div><!-- /.row -->

</main><!-- /.container -->

<!--    <footer class="blog-footer">-->
<!--        <p>2019 by <a href="https://twitter.com/mdo">@mdo</a>.</p>-->
<!--        <p>-->
<!--            <a href="#">Back to top</a>-->
<!--        </p>-->
<!--    </footer>-->

</body>
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