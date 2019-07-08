<?php
//댓글 처리하는 파일

    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;

    $episode_db_id = $_GET['ep_id']; //read_post.php 파일에서 get방식으로 받은 값이 여기에도 전달됨
    $board_name = $_GET['board'];
//    push_log("comment) GET['ep_id']=".$episode_db_id);
//    push_log("comment) GET['board']=".$board_name);


    //댓글 최초작성 시
    //클라이언트로부터 받은 요청을 처리하는 코드이다
    //save, comment: (jquery ajax 를 통해) 서버에 요청하여 얻은 값. script.js 참고
    if(isset($_POST['save'])){


        //현재 로그인 상태인 사용자의 이름과 이메일
        $name = $_SESSION['user'];
        $email = $_SESSION['email'];

        $comment = $_POST['comment']; //사용자가 작성한 댓글 내용
        $date = date('Y-m-d H:i:s'); //현재시각
        $episode_db_id = $_POST['episode_db_id']; //댓글이 속한 글의 id -- 나중에 대댓 기능 만들 때, 수정삭제 부분에 post_id 추가할 것
        $board_name = $_POST['board_name'];


        $sql_tableName = '';

        if($board_name=='fiction'){
            $sql_tableName = 'novelProject_episodeInfo';

        }else if($board_name=='non-fiction'){
            $sql_tableName = 'novelProject_nonfiction';

        }else if($board_name=='community'){
            $sql_tableName = 'novelProject_community';
        }



        //해당 글이 속한 db에 댓글 수를 추가한다
        $query_episodeInfo = "UPDATE ".$sql_tableName." SET numberOfComments= numberOfComments + 1 WHERE id='$episode_db_id'";

        //받은 값을 댓글 db에 저장한다 - 해당 글이 속한 게시판 이름도 저장
        $query_comment = "INSERT INTO novelProject_comment (writer_username, writer_email, content, date, episode_db_id, board_name) VALUES ('$name', '$email', '$comment', '$date', '$episode_db_id', '$board_name')";


        $result_episodeDB = mysqli_query($db, $query_episodeInfo);
        $result_commentDB = mysqli_query($db, $query_comment);

        if($result_episodeDB){
//            push_log("comment) episode query succeeded");

            if($result_commentDB){
//                push_log("comment) comment query succeeded");

                //mysqli_insert_id: 마지막으로 삽입된 id를 반환한다. 바로 위에서 db에 값을 저장했는데, 그 row 의 id를 알고자 한 것
                $comment_db_id = mysqli_insert_id($db);


//                /*이 댓글을 다음 페이지로 넘겨야 하는지 확인하는 부분
//                  = 방금 추가된 이 댓글이 마지막 페이지의 첫번째 댓글(11, 21, 31번째..)이라면,
//                  원래 있는 댓글 페이지에 새로운 댓글을 추가하는 것이 아니라, 새로운 페이지를 띄워줘야 한다 */
                $results_per_page = 10;
                $query_num = "SELECT count(*) FROM novelProject_comment WHERE board_name ='$board_name' AND episode_db_id =".$episode_db_id; //각 글의 id에 해당하는 댓글만 고른다
                $result_num = mysqli_query($db, $query_num);

                $num = mysqli_fetch_row($result_num)[0];

                if($num > $results_per_page){ //댓글이 10개 초과일 때만 확인한다(1~10개까지는 의미없음)
                    $number_of_pages = ceil($num/$results_per_page);

                    //마지막 페이지의 첫번째 댓글의 id를 가져온다
                    $start_from = ($number_of_pages - 1)*$results_per_page;
                    $query_find_pageStarter = "SELECT*FROM novelProject_comment WHERE board_name ='$board_name' AND episode_db_id =".$episode_db_id." LIMIT ".$start_from .",1";
                    $result_find_pageStarter = mysqli_query($db, $query_find_pageStarter);

                    $row_find_pageStarter = mysqli_fetch_array($result_find_pageStarter);
                    $pageStarter = $row_find_pageStarter['id'];

                    //마지막 페이지의 첫번째 댓글 = 이 댓글이면 -> 새로운 페이지(=마지막 페이지)를 띄워주라고 브라우저에 전달한다
                    if($pageStarter == $comment_db_id){
                        $result = 'fetch';
                    }else{//해당 없을 경우 -> 이 댓글만 추가한다

                        //해당 댓글의 정보를 화면에 표시한다. 수정, 삭제를 위해 data-id를 id 값으로 설정해준다
                        //id를 눈에 보이지 않게 넣어놓는 방법은 여러가지이다. input type=hidden 도 가능하다.
                        //data 속성을 사용하면 이를 더 깔끔하게 처리할 수 있다.
                        //data-ㅇㅇ 형태로 아무 속성이나 추가 가능하다(문자열로 출력되므로 boolean은 x)
                        //dataset 으로 출력된다.
                        //수정삭제를 위해 댓글쓴이의 email을 숨겨놓는다
                        $result = '
                    <div class="comment_box">
                        <span class="delete" data-id="'.$comment_db_id.'">delete</span>
                        <span class="edit" data-id="'.$comment_db_id.'">edit</span>
                        <div class="display_name">'.$name.'</div>
                        <div class="comment_text" style="word-break:break-all;">'.$comment.'</div>
                        <div class="text-muted" style="font-size: small; margin-top: 10px">'.$date.'</div>
                        <input type="hidden" id="writer_email" name="writer_email" value="'.$email.'">
                    </div>';

                    }

                    /*이 댓글을 다음 페이지로 넘겨야 하는지 확인하는 부분 끝*/

                }else{
                    $result = '
                    <div class="comment_box">
                        <span class="delete" data-id="'.$comment_db_id.'">delete</span>
                        <span class="edit" data-id="'.$comment_db_id.'">edit</span>
                        <div class="display_name">'.$name.'</div>
                        <div class="comment_text" style="word-break:break-all;">'.$comment.'</div>
                        <div class="text-muted" style="font-size: small; margin-top: 10px">'.$date.'</div>
                        <input type="hidden" id="writer_email" name="writer_email" value="'.$email.'">
                    </div>';
                }


                echo $result;// = ajax response. script.js 에서 댓글창으로 append 된다

            }else{
//                push_log("comment) comment query failed");
            }

        }else{
//            push_log("comment) episode query failed");
        }

//        push_log("comment) exit()");
        exit();//해당 함수가 포함된 페이지 자체를 끝낸다.
        //이거 왜 쓰는거지? : ajax response에 불필요한 코드를 추가하는 것을 막아준다

    }


    //댓글 수정 시
    if(isset($_POST['edit'])){
//        $name = $_POST['name'];
        $name = $_SESSION['user'];
        $comment_db_id = $_POST['id'];
        $comment = $_POST['comment'];

        $comment_writer_email_edit = $_POST['writer_email'];
        $currentUser_email_edit = $_SESSION['email'];

//            push_log("id= ".$id, "수정");

        $edit_result='fail';
        //댓글 쓴 사람과 클릭한 사람의 이메일이 같으면 수정 form을 반환한다
        if($comment_writer_email_edit==$currentUser_email_edit){

            $edit_result = '<div class="edit_form" id="edit_form_'.$comment_db_id.'">
            <div>
                <label for="comment">Comment:</label>
                <textarea name="comment" id="edit_comment" cols="30" rows="5" >'.$comment.'</textarea>
            </div>
            <button type="button" id="edit_update_btn" >UPDATE</button>
            <button type="button" id="edit_cancel_btn" >CANCEL</button>
        </div>';

        }else{ //댓글쓴이 != 클릭한 사람

            $edit_result = 'stranger';
        }

//        push_log('comment_db_id='.$comment_db_id);
//        push_log('comment_writer_email'.$comment_writer_email_edit);
//        push_log('currentUser_email='.$currentUser_email_edit);
//        push_log('delete_result='.$edit_result);

        echo $edit_result;
        exit();
    }


    //댓글 수정 완료 시
    if(isset($_POST['update'])){
        $comment_db_id = $_POST['id'];
        $name = $_SESSION['user'];
        $comment = $_POST['comment'];
        $date = date('Y-m-d H:i:s'); //수정시각

        $email=$_SESSION['email'];

        //db 업데이트
        $query = "UPDATE novelProject_comment SET content='{$comment}', date='{$date}' WHERE id=".$comment_db_id;

        if(mysqli_query($db, $query)){

            $saved_comment = '<div class="comment_box">
                    <span class="delete" data-id="'.$comment_db_id.'">delete</span>
                    <span class="edit" data-id="'.$comment_db_id.'">edit</span>
                    <div class="display_name">'.$name.'</div>
                    <div class="comment_text" style="word-break:break-all;">'.$comment.'</div>
                    <div class="text-muted" style="font-size: small; margin-top: 10px">'.$date.'</div>
                    <input type="hidden" id="writer_email" name="writer_email" value="'.$email.'">
                </div>';
            echo $saved_comment; // = response
        }
        exit();
    }

    //댓글 삭제 시
    if(isset($_POST['delete'])){

        $comment_writer_email = $_POST['writer_email'];
        $comment_db_id = $_POST['id'];

        $query = "SELECT*FROM novelProject_comment WHERE id =".$comment_db_id;
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);
        $episode_db_id = $row['episode_db_id'];
        $board_name = $row['board_name'];

        $sql_tableName = '';

        if($board_name=='fiction'){
            $sql_tableName = 'novelProject_episodeInfo';

        }else if($board_name=='non-fiction'){
            $sql_tableName = 'novelProject_nonfiction';

        }else if($board_name=='community'){
            $sql_tableName = 'novelProject_community';
        }

        $currentUser_email = $_SESSION['email'];

        $delete_result='fail';
        //댓글 쓴 사람과 클릭한 사람의 이메일이 같으면 이 댓글을 DB에서 삭제한다
        if($comment_writer_email==$currentUser_email){

            $query = "DELETE FROM novelProject_comment WHERE id=".$comment_db_id;
            $query_episodeInfo = "UPDATE ".$sql_tableName." SET numberOfComments= numberOfComments - 1 WHERE id='$episode_db_id'";
            mysqli_query($db, $query);
            mysqli_query($db, $query_episodeInfo);

            $delete_result = 'success';

        }else{ //댓글쓴이 != 클릭한 사람

            $delete_result = 'stranger';

        }


        echo $delete_result;
        exit();
    }



    //전체 댓글을 화면에 표시해주는 부분
    //db에서 상위 댓글 정보를 날짜 순으로 모두 가져온다
    if(isset($_POST['fetch'])){


        $page = $_POST['page'];

        $episode_db_id = $_POST['episode_db_id']; //댓글이 속한 글의 id -- 나중에 대댓 기능 만들 때, 수정삭제 부분에 post_id 추가할 것
        $board_name = $_POST['board_name'];

        $query_num = "SELECT count(*) FROM novelProject_comment WHERE board_name ='$board_name' AND episode_db_id =".$episode_db_id; //각 글의 id에 해당하는 댓글만 고른다
        $result_num = mysqli_query($db, $query_num);

        $num = mysqli_fetch_row($result_num)[0];

        $results_per_page = 10;//한 페이지당 10개로 제한
        $number_of_pages = ceil($num/$results_per_page);
        //페이지마다 몇번째 행부터 데이터를 출력할 지

        if($page == 'last'){
            $page = $number_of_pages;
        }

//        push_log2('result_num='.$num.' / number of pages='.$number_of_pages.' / page='.$page);
        $start_from = ($page - 1)*$results_per_page;



        $comments = '<div id="display_area">';

        if($num>0){ //댓글이 있을 때만 아래 코드를 실행한다
            $query = "SELECT*FROM novelProject_comment WHERE board_name ='$board_name' AND episode_db_id =".$episode_db_id." LIMIT ".$start_from .",".$results_per_page; //각 글의 id에 해당하는 댓글만 고른다
            $result = mysqli_query($db, $query);

            //각각의 댓글을 출력한다
            while($row=mysqli_fetch_array($result)){

//        $par_code = $row['id'];

                //댓글 목록
                $comments .= '
            <div class="comment_box">
                <span class="delete" data-id="'.$row['id'].'">delete</span>
                <span class="edit" data-id="'.$row['id'].'">edit</span>
                <div class="display_name">'.$row['writer_username'].'</div>
                <div class="comment_text" style="word-break:break-all;">'.$row['content'].'</div>
                <div class="text-muted" style="font-size: small; margin-top: 10px">'.$row['date'].'</div>
                <input type="hidden" id="writer_email" name="writer_email" value="'.$row['writer_email'].'">
            </div>';
            }

        }

        $comments .= '</div>';


        //댓글 페이지
        if($number_of_pages>1){ //2 페이지 이상일 때만 화면에 페이지 숫자를 표시한다

            $comments .='
            <nav aria-label="Page navigation example" style="margin-top: 50px; margin-bottom: 100px; margin-left: 40px" id="page_nav">
                <ul class="pagination">';

            if($number_of_pages > 5){ // 전체 페이지가 5개보다 많은 경우
                if($page<=3){ //사용자가 1~3페이지까지 선택했을 경우

                    //1~5페이지를 보여준다
                    for ($i=1; $i<=5; $i++){
                        if($i==$page){ //이 페이지에 들어온 상태일 때 - active
                            $comments .= '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                        }else{
                            $comments .= '<li class="page-item"><a class="page-link" href="#" onclick="fetch_list('.$i.'); return false">'. $i .'</a></li>';
                        }
                    }

                    //마지막 페이지로 가는 버튼
                    $comments .= '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="#" onclick="fetch_list('.$number_of_pages.'); return false">Last</a></li>';

                }else if($page<=$number_of_pages-2){ //사용자가 전체페이지-2 번째 페이지까지 클릭한 경우

                    //첫 페이지로 가는 버튼
                    $comments .= '<li class="page-item"><a class="page-link" href="#" onclick="fetch_list(1); return false">First</a></li><li class="page-item">. . .</li>';

                    // 사용자가 클릭한 페이지 앞뒤로 2개씩, 총 5개 페이지를 보여준다
                    for ($i=$page-2; $i<=$page+2; $i++){
                        if($i==$page){
                            $comments .= '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                        }else{
                            $comments .= '<li class="page-item"><a class="page-link" href="#" onclick="fetch_list('.$i.'); return false">'. $i .'</a></li>';
                        }
                    }

                    //마지막 페이지로 가는 버튼
                    $comments .= '<li class="page-item">. . .</li><li class="page-item"><a class="page-link" href="#" onclick="fetch_list('.$number_of_pages.'); return false">Last</a></li>';

                }else{ //사용자가 마지막 페이지 or 마지막 전 페이지를 클릭한 경우

                    //첫 페이지로 가는 버튼
                    $comments .= '<li class="page-item"><a class="page-link" href="#" onclick="fetch_list(1); return false">First</a></li><li class="page-item">. . .</li>';

                    //마지막에서 5개 페이지를 보여준다
                    for ($i=$number_of_pages-4; $i<=$number_of_pages; $i++){
                        if($i==$page){
                            $comments .= '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                        }else{
                            $comments .= '<li class="page-item"><a class="page-link" href="#" onclick="fetch_list('.$i.'); return false">'. $i .'</a></li>';
                        }
                    }

                }

            }else{
                for ($i=1; $i<=$number_of_pages; $i++){
                    if($i==$page){
                        $comments .= '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                    }else{
                        $comments .= '<li class="page-item"><a class="page-link" href="#" onclick="fetch_list('.$i.'); return false">'. $i .'</a></li>';
                    }
                }
            }

            $comments .='
                </ul>
            </nav>';
        }


        echo $comments;
        exit();


    }

?>