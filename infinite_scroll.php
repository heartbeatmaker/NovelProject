<?php
    //메인 화면 무한스크롤 처리할 때, 데이터를 보내주는 코드

    require_once  '/usr/local/apache/security_files/connect.php';
    require_once 'log/log.php';
    require_once 'functions.php';

    global $db;

    //원래 7일로 제한하려고 했는데, 데이터가 적어서 30일로 늘림
    //최근 30일동안의 소설 데이터 중에, 10개를 인기순으로 가져온다
    $sql_getData = "SELECT*FROM novelProject_episodeInfo WHERE date(date) >= date(subdate(now(), INTERVAL 30 DAY)) and date(date) <= date(now()) and numberOfLikes >= 50 ORDER BY numberOfLikes DESC LIMIT 10";
    $result = mysqli_query($db, $sql_getData) or die(mysqli_error($db));


    $first_page ='';

    //10개의 새로운 소설 데이터를 보내준다
    while($row = mysqli_fetch_array($result)){

        $board_name='fiction';
        $story_db_id=$row['story_db_id'];
        $genre = $row['genre'];
        $episode_db_id=$row['id'];//클릭 시 get 방식으로 보내주기

        $title=$row['title'];
        $author_username=$row['author_username'];
        $date=$row['date'];
        $numberOfLikes = $row['numberOfLikes'];
        $numberOfComments = $row['numberOfComments'];
        $numberOfViews = $row['numberOfViews'];

        $date_modified = explode(' ',$date)[0];
        $today=date("Y-m-d");

        if($date_modified==$today){
            $date_modified = 'Today '.explode(' ',$date)[1];
        }

        //storyInfo db에서 title, description 가져오기
        $story_title='';
        $story_description='';
        $sql_description = "SELECT*FROM novelProject_storyInfo WHERE id='$story_db_id'";
        $result_story = mysqli_query($db, $sql_description) or die(mysqli_error($db));
        $story_img_file_name='';

        if(mysqli_num_rows($result_story)==1){
            $row_story = mysqli_fetch_array($result_story);
            $story_title=$row_story['title'];
            $story_description = $row_story['description'];
            $story_img_file_name = $row_story['image'];
        }


        $image_path = 'boards/upload/'.$story_img_file_name;
        if($story_img_file_name == 'default' || $story_img_file_name == null || $story_img_file_name == ''){
            $randomNumber = generateRandomInt(25);
            $img_src = $randomNumber.'.jpg';

            $image_path = 'images/bookCover_dummy/'.$img_src;
        }


        $first_page .=
            '<div class="list_item" onclick="location.href=\'boards/read_post.php?board='.$board_name.'&ep_id='.$episode_db_id.'\'" style="margin-bottom: 20px;">
                            <div class="card flex-md-row box-shadow h-md-250">
                                <img src="'.$image_path.'" style="border-radius: 0 3px 3px 0; width:130px; height:190px; margin:10px" alt="Card image cap"/>
                                <div class="card-body d-flex flex-column align-items-start">
                                    <strong class="d-inline-block mb-2 text-primary">'.$genre.'</strong>
                                    <h5 class="mb-0">
                                        <a class="text-dark" style="word-break: break-all">'.$story_title.' : '.$title.'</a>
                                    </h5>
                                    <div class="mb-1 text-muted">by '.$author_username.'</div>
                                    <p class="card-text mb-auto" style="word-break: break-all">'.$story_description.'</p>
                                    <div style="margin-top: 10px; width:100%;">
                                        <div style="float:left; width:80%">'.$numberOfViews.' views * '.$numberOfLikes.' likes * '.$numberOfComments.' comments</div>
                                        <div class="text-muted" style="float:left; width:20%">'.$date_modified.'</div>
                                    </div>
                                </div>
                 
                            </div>
                         </div>';
    }




    //브라우저로부터 post 방식으로 요청을 받았을 때, 데이터를 보내주는 코드
    if(isset($_POST['scroll'])){

        $page = $_POST['scroll']; //지금이 몇번째 페이지인지

        push_log('scroll:'.$page);

        global $db;

        //최근 30일동안 쌓인 소설 데이터의 개수를 조회한다
        $sql_num = "SELECT*FROM novelProject_episodeInfo WHERE date(date) >= date(subdate(now(), INTERVAL 30 DAY)) and date(date) <= date(now()) and numberOfLikes >= 50 ";
        $result_num = mysqli_query($db, $sql_num);
        $number_of_results = mysqli_num_rows($result_num);//결과 행의 개수

        $results_per_page = 5;//한 페이지당 5개로 제한
        $number_of_pages = ceil($number_of_results/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지
        $start_from = ($page - 1)*$results_per_page;

        push_log('count:'.$number_of_results.' start_from:'.$start_from);

        //최근 30일동안의 소설 데이터 중에, 10개를 인기순으로 가져온다
        $sql_getData = "SELECT*FROM novelProject_episodeInfo WHERE date(date) >= date(subdate(now(), INTERVAL 30 DAY)) and date(date) <= date(now()) and numberOfLikes >= 50 ORDER BY numberOfLikes DESC LIMIT ".$start_from .",".$results_per_page;
        $result_getData = mysqli_query($db, $sql_getData) or die(mysqli_error($db));
        $num = mysqli_num_rows($result_getData);

        $new_page ='';

        //10개의 새로운 소설 데이터를 보내준다
        while($row = mysqli_fetch_array($result_getData)){

            $board_name='fiction';
            $story_db_id=$row['story_db_id'];
            $genre = 'Fiction - '.$row['genre'];
            $episode_db_id=$row['id'];//클릭 시 get 방식으로 보내주기

            $title=$row['title'];
            $author_username=$row['author_username'];
            $date=$row['date'];
            $numberOfLikes = $row['numberOfLikes'];
            $numberOfComments = $row['numberOfComments'];
            $numberOfViews = $row['numberOfViews'];

            $date_modified = explode(' ',$date)[0];
            $today=date("Y-m-d");

            if($date_modified==$today){
                $date_modified = 'Today '.explode(' ',$date)[1];
            }

            //storyInfo db에서 title, description 가져오기
            $story_title='';
            $story_description='';
            $sql_description = "SELECT*FROM novelProject_storyInfo WHERE id='$story_db_id'";
            $result_story = mysqli_query($db, $sql_description) or die(mysqli_error($db));
            $story_img_file_name='';

            if(mysqli_num_rows($result_story)==1){
                $row_story = mysqli_fetch_array($result_story);
                $story_title=$row_story['title'];
                $story_description = $row_story['description'];
                $story_img_file_name = $row_story['image'];
            }


            $image_path = 'boards/upload/'.$story_img_file_name;
            if($story_img_file_name == 'default' || $story_img_file_name == null || $story_img_file_name == ''){
                $randomNumber = generateRandomInt(25);
                $img_src = $randomNumber.'.jpg';

                $image_path = 'images/bookCover_dummy/'.$img_src;
            }


            $new_page .=
                '<div class="list_item" onclick="location.href=\'boards/read_post.php?board='.$board_name.'&ep_id='.$episode_db_id.'\'" style="margin-bottom: 20px;">
                            <div class="card flex-md-row box-shadow h-md-250">
                                <img src="'.$image_path.'" style="border-radius: 0 3px 3px 0; width:130px; height:190px; margin:10px" alt="Card image cap"/>
                                <div class="card-body d-flex flex-column align-items-start">
                                    <strong class="d-inline-block mb-2 text-primary">'.$genre.'</strong>
                                    <h5 class="mb-0">
                                        <a class="text-dark" style="word-break: break-all">'.$story_title.' : '.$title.'</a>
                                    </h5>
                                    <div class="mb-1 text-muted">by '.$author_username.'</div>
                                    <p class="card-text mb-auto" style="word-break: break-all">'.$story_description.'</p>
                                    <div style="margin-top: 10px; width:100%;">
                                        <div style="float:left; width:80%">'.$numberOfViews.' views * '.$numberOfLikes.' likes * '.$numberOfComments.' comments</div>
                                        <div class="text-muted" style="float:left; width:20%">'.$date_modified.'</div>
                                    </div>
                                </div>
                 
                            </div>
                         </div>';
        }

        if($num < 5){
            $page = 'end';
        }

        $result_data = array('page' => $page, 'item' => $new_page);

        echo json_encode($result_data);

        exit();

    }

?>