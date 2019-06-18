<?php
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



    //페이징
    if(!isset($_GET['page'])){
        $page = 1;
    }else{
        $page = $_GET['page'];
    }


    $currentUser_email = $_SESSION['email'];

    //현재 사용자의 북마크 목록을 가져온다
    $query_userInfo = "SELECT*FROM novelProject_userInfo WHERE email ='$currentUser_email'";
    $result_userInfo = mysqli_query($db, $query_userInfo) or die(mysqli_error($db));

    $bookmarkList='';
    if(mysqli_num_rows($result_userInfo) == 1){
        $row_userInfo = mysqli_fetch_array($result_userInfo);
        $bookmarkList = $row_userInfo['bookmarkHistory'];
    }

    $result_count=''; //북마크 개수

    $bookmarkList_split_original = explode(';', $bookmarkList);
    $bookmarkList_split = array_reverse($bookmarkList_split_original); //최신순으로 정렬하려고 배열 뒤집음
    $result_count = count($bookmarkList_split);

    $results_per_page = 10;//한 페이지당 10개로 제한
    $number_of_pages = ceil($result_count/$results_per_page);
    //페이지마다 북마크 배열에서 몇번째 항부터 데이터를 출력할 지
    $start_from = ($page - 1)*$results_per_page;




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

//    //북마크 삭제 버튼을 누르면
//    if(isset($_POST['btn_delete'])){
//
//        //episode db에서 해당 데이터 삭제
//        $sql_storyInfo_delete = "DELETE FROM novelProject_storyInfo WHERE id='$db_id'";
//        mysqli_query($db, $sql_storyInfo_delete) or die(mysqli_err($db));
//
//        header("location: page_MyStories.php");
//    }

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
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | My Library
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

            <div style="float:left; width:80%; margin-top: 50px">
                <h4><?php echo $result_count?> Bookmarks</h4>
            </div>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Story</th>
                    <th scope="col">Episode</th>
                    <th scope="col">Author</th>
                </tr>
                </thead>
                <tbody>

                <?php

                for($i=$start_from; $i<$start_from+10; $i++){

                    $index = $result_count - ($page - 1) * $results_per_page - $k;

                    $episode_db_id = $bookmarkList_split[$i];

                    //각 episode의 정보를 가져온다
                    $sql_episodeInfo = "SELECT*FROM novelProject_episodeInfo WHERE id ='$episode_db_id'";
                    $result = mysqli_query($db, $sql_episodeInfo);

                    if(mysqli_num_rows($result) == 1){
                        $row = mysqli_fetch_array($result);

                        $author_username=$row['author_username'];
                        $storyTitle =$row['storyTitle'];
                        $episodeTitle = $row['title'];

                        echo '
                     <tr onclick="location.href=\'read_post.php?board=fiction&ep_id='.$episode_db_id.'\'">
                        <th scope="row">' . $index . '</th>
                        <td>' . $storyTitle . '</td>
                        <td>' . $episodeTitle . '</td>
                        <td>' . $author_username . '</td>
                     </tr>
                    ';
                    }

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
                                echo '<li class="page-item"><a class="page-link" href="myPage.php?page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                        //마지막 페이지로 가는 버튼
                        echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="myPage.php?page='.$number_of_pages.'">Last</a></li>';

                    }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                        //첫 페이지로 가는 버튼
                        echo '<li class="page-item"><a class="page-link" href="myPage.php?page=1">First</a></li><li class="page-item">. . .</li>';

                        // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                        for ($i=$page-2; $i<=$page+2; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="myPage.php?page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                        //마지막 페이지로 가는 버튼
                        echo '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="myPage.php?page='.$number_of_pages.'">Last</a></li>';

                    }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                        //첫 페이지로 가는 버튼
                        echo '<li class="page-item"><a class="page-link" href="myPage.php?page=1">First</a></li><li class="page-item">. . .</li>';

                        //마지막에서 5개 페이지를 보여준다
                        for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                            if($i==$page){
                                echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                            }else{
                                echo '<li class="page-item"><a class="page-link" href="myPage.php?page=' . $i . '">'. $i .'</a></li>';
                            }
                        }

                    }

                }else{
                    for ($i=1; $i<=$number_of_pages; $i++){
                        if($i==$page){
                            echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                        }else{
                            echo '<li class="page-item"><a class="page-link" href="myPage.php?page=' . $i . '">'. $i .'</a></li>';
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