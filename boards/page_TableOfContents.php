<?php
    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;

    //var_dump($_SESSION);

    //이 story가 db의 storyInfo 테이블에서 어떤 id로 저장되어 있는지, get방식으로 받아온다
    if(isset($_GET['id'])){
        $db_id = $_GET['id'];
    }

    //episode 페이징 용도
    if(!isset($_GET['page'])){
        $page = 1;
    }else{
        $page = $_GET['page'];
    }

    $author_email='';
    $author_username='';
    $storyTitle='';
    $storyDescription='';
    $storyGenre='';
    //story 정보를 가져온다 - author email, story title
    $sql_checkStoryInfo = "SELECT*FROM novelProject_storyInfo WHERE id ='$db_id'";
    $result = mysqli_query($db, $sql_checkStoryInfo);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_array($result);
        $author_email = $row['author_email']; //작가 email을 가져온다 -- story detail 보여줄 것인지 확인용
        $author_username=$row['author_username'];
        $storyTitle =$row['title'];
        $storyDescription=$row['description'];
        $storyGenre=$row['genre'];
    }

    //이 소설의 episode를 db에서 가져온다 - 페이징
    //이 소설의 episode가 총 몇 개인지 확인
    $sql_checkNumberOfRows = "SELECT*FROM novelProject_episodeInfo WHERE story_db_id ='$db_id'";
    $result = mysqli_query($db, $sql_checkNumberOfRows);
    $number_of_results = mysqli_num_rows($result); //결과 행의 갯수

    $results_per_page = 10;//한 페이지당 10개로 제한
    $number_of_pages = ceil($number_of_results/$results_per_page);
    //페이지마다 몇번째 행부터 데이터를 출력할 지
    $start_from = ($page - 1)*$results_per_page;


    //10개씩만 가져온다
    $sql = "SELECT*FROM novelProject_episodeInfo WHERE story_db_id ='$db_id' ORDER BY DATE DESC LIMIT ".$start_from .",".$results_per_page;
    $result = mysqli_query($db, $sql);


    //로그아웃 버튼 눌렀을 때
    if(isset($_POST['signout_btn'])) {

        $email = $_SESSION['email'];
        push_log($email . " sign out");

        //해당 사용자의 db정보를 수정한다
        global $db;
        $query_deleteInfo = "UPDATE novelProject_userInfo SET session_id=null WHERE email='$email'";
        mysqli_query($db, $query_deleteInfo);

        $_SESSION = array(); //세션 변수 전체를 초기화한다

        echo "<script>alert(\"Bye! \");</script>";
    //    header("location: ../index.php"); //redirect
    }

    //글 삭제 버튼을 누르면
    if(isset($_POST['btn_delete'])){

        //episode db에서 해당 데이터 삭제
        $sql_storyInfo_delete = "DELETE FROM novelProject_storyInfo WHERE id='$db_id'";
        mysqli_query($db, $sql_storyInfo_delete) or die(mysqli_err($db));

        header("location: page_MyStories.php");
    }

    //글 수정 버튼을 누르면
    if(isset($_POST['btn_edit'])){

        //수정 화면으로 이동(GET 방식으로 전달)
        header("location: page_CreateNewStory.php?id=$db_id&mode=edit");

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

    <title>ReadMe | Fiction</title>
</head>
<body>


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-8" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | Details
            </div>
            <!--            <div>-->
            <!--                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='mainPage.php'">Cancel</button>-->
            <!--            </div>-->
            <div>
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


        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

    <div class="row">

        <div class="col-md-10" style="margin:0px auto">

<!--            <div style="width:100%; text-align: right">-->
<!--                <button class="btn btn-outline-success" style="margin-bottom: 30px; padding-left: 20px; padding-right: 30px"">+ Add to List</button>-->
<!--            </div>-->

            <?php

            //현재 로그인한 사용자와 이 이야기를 쓴 작가가 동일인물인지 확인
            //동일인물이라면, 책 세부내용을 띄워주지 않는다(이미 전 화면에서 확인했으므로)
            if($_SESSION['email']==$author_email){
                echo '
                <div class="card flex-md-row mb-4 box-shadow h-md-250">

                    <div style="width:25%; float:left">
                        <img src="../images/1.jpg" style="border-radius: 0 3px 3px 0; width:110px; height:150px; margin: 20px 50px 20px" alt="Card image cap"/>
                    </div>
    
                    <div style="width:55%; float:left">
                        <div class="card-body d-flex flex-column align-items-start">
                            <strong class="d-inline-block mb-2 text-primary">'.$storyGenre.'</strong>
                            <h3 class="mb-0">
                                <a class="text-dark">'.$storyTitle.'</a>
                            </h3>';

                            if($author_username=='Anonymous'){
                                echo'<div class="mb-1 text-muted">Anonymous Story</div>';
                            }
                            echo '<div style="margin-top: 10px">'.$storyDescription.'</div>

                        </div>
                    </div>
    
                    <form action="" method="post" style="width:30%; float:left">
                        <button class="btn btn-outline-success" name="btn_edit" value="true" style="margin-top: 30px; padding-left: 20px; padding-right: 20px">Edit</button>
                        <button class="btn btn-outline-secondary" name="btn_delete" value="true" style="margin-top: 30px; padding-left: 20px; padding-right: 20px">Delete</button>
                    </form>
          
               </div>
                ';

            }else{
                echo '
                <div class="card flex-md-row mb-4 box-shadow h-md-250">

                    <div style="width:25%; float:left">
                        <img src="../images/1.jpg" style="border-radius: 0 3px 3px 0; width:130px; height:180px; margin: 20px 50px 20px" alt="Card image cap"/>
                    </div>
    
                    <div style="width:55%; float:left">
                        <div class="card-body d-flex flex-column align-items-start">
                            <h3 class="mb-0">
                                <a class="text-dark">Lord of the Rings</a>
                            </h3>
                            <div style="margin-top: 10px">by Jenna Doe</div>
                            <div class="mb-1 text-muted" style="margin-top: 10px">2019.01.21 ~ 2019.06.16</div>
    
                            <div style="margin-top: 10px" class="font-italic; font-weight-bold">2 Part Stories (Completed)</div>
                            <div style="margin-top: 10px">2000 likes | 100 comments</div>
                        </div>
                    </div>
    
                    <div style="width:30%; float:left">
                        <button class="btn btn-outline-success" style="margin-top: 30px; padding-left: 20px; padding-right: 30px">+ Add to List</button>
                    </div>
               </div>
                ';
            }
            ?>


            <div style="float:left; width:80%; margin-top: 50px">
                <h4><?php echo $number_of_results?> Parts</h4>
            </div>
            <div style="float:left; width:20%; margin-bottom: 20px; margin-top: 50px">
                <button class="btn btn-success" style="padding-left: 20px; padding-right: 30px;"
                onclick="location.href='page_writeNewEpisode.php?id=<?php echo $db_id?>'">+ New Part</button>
            </div>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Date</th>
                </tr>
                </thead>
                <tbody>

                <?php

                $k=0;
                while($row = mysqli_fetch_array($result)) {

                    $index = $number_of_results - ($page - 1) * $results_per_page - $k;

                    echo '
                     <tr onclick="location.href=\'read_post.php?ep_id='.$row['id'].'\'">
                        <th scope="row">' . $index . '</th>
                        <td>' . $row['title'] . '</td>
                        <td>' . $row['date'] . '</td>
                     </tr>
                    ';

                    $k++;
                }

                ?>

                </tbody>
            </table>

        </div>


<!--        양 옆 패딩 100씩 넣은 이유: 페이지가 5개 미만일 때(폭이 좁을 때), 표 오른쪽으로 올라감. 폭을 넓혀주기 위해서 넣음-->
        <nav aria-label="Page navigation example" style="margin:0px auto; margin-top: 70px; padding-left: 100px; padding-right: 100px">
            <ul class="pagination">
                <?php
                if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                    if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                        //1~5페이지를 보여준다
                        for ($i=1; $i<=5; $i++){
                            if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                        //마지막 페이지로 가는 버튼
                        echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page='.$number_of_pages.'">Last</a></li>';

                    }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                        //첫 페이지로 가는 버튼
                        echo '<li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page=1">First</a></li><li class="page-item">. . .</li>';

                        // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                        for ($i=$page-2; $i<=$page+2; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                        //마지막 페이지로 가는 버튼
                        echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page='.$number_of_pages.'">Last</a></li>';

                    }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                        //첫 페이지로 가는 버튼
                        echo '<li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page=1">First</a></li><li class="page-item">. . .</li>';

                        //마지막에서 5개 페이지를 보여준다
                        for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                    }

                }else{
                    for ($i=1; $i<=$number_of_pages; $i++){
                        if($i==$page){
                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                        }else{
                            echo '<li class="page-item"><a class="page-link" href="page_TableOfContents.php?id='.$db_id.'&page=' . $i . '">'. $i .'</a></li>';
                        }
                    }
                }

                ?>
            </ul>
        </nav>






    </div><!-- /.row -->

</main><!-- /.container -->


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