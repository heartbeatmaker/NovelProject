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

    $results_per_page = 9;
    $number_of_pages = ceil($number_of_results/$results_per_page);
    //페이지마다 몇번째 행부터 데이터를 출력할 지
    $start_from = ($page - 1)*$results_per_page;

    $sql = "SELECT*FROM blog_post LIMIT ".$start_from .",".$results_per_page;
    $result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portfolio site</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/blog.css">
</head>
<body>
<div id="wrapper">
    <!--        Sidebar-->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <!--                ul = unordered list-->
            <!--                li = list item-->
            <li><a href="../index.php">Home</a></li>
<!--            <li><a href="#">About Me</a></li>-->
<!--            <li><a href="#">Portfolio</a></li>-->
            <li><a href="../image_test/main.php">Study</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="../contact/contact.php">Contact</a></li>
        </ul>
    </div>

    <!--        Page content-->

    <div class="content-pagination-wrapper" >
        <!--                        <a href="#" class="btn btn-success" id="menu-toggle">Toggle Menu</a>-->
        <h1>Blog</h1>
        <div class="button" onclick="location.href='new_post.php'">
            <span>New Post</span>
        </div>


        <?php while($row = mysqli_fetch_array($result)){ ?>
            <div onclick="location.href='read_post.php?id=<?php echo $row['id']?>'" class="gallery">
                <img src="../images/sky.jpg"/>
                <div class="desc">
                    <?php echo $row['title']?>
                    <br>
                    <?php
                    $full_date = new dateTime($row['date']);
                    $post_date = date_format($full_date, 'M j, Y');
                    echo $post_date?>
                </div>
            </div>
        <?php } ?>

    </div>

    <div id="pagination-wrapper">

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
                        echo '<a href="blog.php?page=' . $i . '">'.$i.'</a>';
                    }
                }

                echo '<a href="blog.php?page='.$number_of_pages.'">Last</a>';

            }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                //첫 페이지로 가는 버튼
                echo '<a href="blog.php?page=1">First</a>';

                // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                for ($i=$page-2; $i<=$page+2; $i++){
                    if($i==$page){
                        echo '<a class="active">'. $i .'</a>';
                    }else{
                        echo '<a href="blog.php?page=' . $i . '">'.$i.'</a>';
                    }
                }

                //마지막 페이지로 가는 버튼
                echo '<a href="blog.php?page='.$number_of_pages.'">Last</a>';

            }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                //첫 페이지로 가는 버튼
                echo '<a href="blog.php?page=1">First</a>';

                //마지막에서 5개 페이지를 보여준다
                for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                    if($i==$page){
                        echo '<a class="active">'. $i .'</a>';
                    }else{
                        echo '<a href="blog.php?page=' . $i . '">'.$i.'</a>';
                    }
                }

            }

        }else{
            for ($i=1; $i<=$number_of_pages; $i++){
                if($i==$page){
                    echo '<a class="active">'. $i .'</a>';
                }else{
                    echo '<a href="blog.php?page=' . $i . '">'.$i.'</a>';
                    //href 뒤의 주소로 이동한다(n번째 페이지)
                }
            }
        }


        echo "</div>";
        ?>

    </div>


</div>


</body>


</html>
