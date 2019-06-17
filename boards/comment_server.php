<?php
//댓글 처리하는 파일

    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;

    $episode_db_id = $_GET['ep_id']; //read_post.php 파일에서 get방식으로 받은 값이 여기에도 전달됨
    push_log("comment) GET['ep_id']=".$episode_db_id);


    //댓글 최초작성 시
    //클라이언트로부터 받은 요청을 처리하는 코드이다
    //save, comment: (jquery ajax 를 통해) 서버에 요청하여 얻은 값. script.js 참고
    if(isset($_POST['save'])){
        push_log("comment) ajax로 받은 요청을 처리하는 곳");

        //현재 로그인 상태인 사용자의 이름과 이메일
        $name = $_SESSION['user'];
        $email = $_SESSION['email'];
        push_log("comment) writer_username=".$name." writer_email=".$email);

        $comment = $_POST['comment']; //사용자가 작성한 댓글 내용
        $date = date('Y-m-d H:i:s'); //현재시각
        $episode_db_id = $_POST['episode_db_id']; //댓글이 속한 글의 id -- 나중에 대댓 기능 만들 때, 수정삭제 부분에 post_id 추가할 것

        push_log("comment) comment= ".$comment);
        push_log("comment) date= ".$date);
        push_log("comment) episode_db_id= ".$episode_db_id);

        //원래 댓글 몇 개 있었는지 확인 - 댓글 개수 업데이트용
        $sql = "SELECT*FROM novelProject_episodeInfo WHERE id='$episode_db_id'";
        $result = mysqli_query($db, $sql);

        $numberOfComments='';
        if(mysqli_num_rows($result)==1){
            $row = mysqli_fetch_array($result);

            $numberOfComments=$row['comment'];
            push_log("comment) 최초 numberOfComments= ".$numberOfComments);

            if($numberOfComments==null){
                push_log("comment) numberOfComments=null 이라고 함. 확인: ".$numberOfComments);
                $numberOfComments=1;
            }else if($numberOfComments>=1){
                push_log("comment) numberOfComments>=1 이라고 함. 확인: ".$numberOfComments);
                $numberOfComments+=1;
            }
            push_log("comment) 마지막 numberOfComments= ".$numberOfComments);
        }

        //episode db에 댓글 수를 추가한다
        $query_episodeInfo = "UPDATE novelProject_episodeInfo SET comment='$numberOfComments' WHERE id='$episode_db_id'";

        //받은 값을 댓글 db에 저장한다
        $query_comment = "INSERT INTO novelProject_comment (writer_username, writer_email, content, date, episode_db_id) VALUES ('$name', '$email', '$comment', '$date', '$episode_db_id')";


        $result_episodeDB = mysqli_query($db, $query_episodeInfo);
        $result_commentDB = mysqli_query($db, $query_comment);

        if($result_episodeDB){
            push_log("comment) episode query succeeded");

            if($result_commentDB){
                push_log("comment) comment query succeeded");

                //mysqli_insert_id: 마지막으로 삽입된 id를 반환한다. 바로 위에서 db에 값을 저장했는데, 그 row 의 id를 알고자 한 것
                $comment_db_id = mysqli_insert_id($db);

//            push_log("id= ".$id, "line 14");

                //해당 댓글의 정보를 화면에 표시한다. 수정, 삭제를 위해 data-id를 id 값으로 설정해준다
                //id를 눈에 보이지 않게 넣어놓는 방법은 여러가지이다. input type=hidden 도 가능하다.
                //data 속성을 사용하면 이를 더 깔끔하게 처리할 수 있다.
                //data-ㅇㅇ 형태로 아무 속성이나 추가 가능하다(문자열로 출력되므로 boolean은 x)
                //dataset 으로 출력된다.

//            수정삭제를 위해 댓글쓴이의 email을 숨겨놓는다
                $saved_comment = '<div class="comment_box">
                <span class="delete" data-id="'.$comment_db_id.'">delete</span>
                <span class="edit" data-id="'.$comment_db_id.'">edit</span>
                <div class="display_name">'.$name.'</div>
                <div class="comment_text" style="word-break:break-all;">'.$comment.'</div>
                <div class="text-muted" style="font-size: small; margin-top: 10px">'.$date.'</div>
                <input type="hidden" id="writer_email" name="writer_email" value="'.$email.'">
            </div>';
                echo $saved_comment;// = ajax response. script.js 에서 댓글창으로 append 된다

            }else{
                push_log("comment) comment query failed");
            }

        }else{
            push_log("comment) episode query failed");
        }

        push_log("comment) exit()");
        exit();//해당 함수가 포함된 페이지 자체를 끝낸다.
        //이거 왜 쓰는거지? : ajax response에 불필요한 코드를 추가하는 것을 막아준다

    }


    //댓글 수정 시
    if(isset($_POST['edit'])){
//        $name = $_POST['name'];
        $name = $_SESSION['user'];
        $comment_db_id = $_POST['id'];
        $comment = $_POST['comment'];

//            push_log("id= ".$id, "수정");

        $onEdit_comment = '<div class="edit_form" id="edit_form_'.$comment_db_id.'">
            <div>
                <label for="comment">Comment:</label>
                <textarea name="comment" id="edit_comment" cols="30" rows="5" >'.$comment.'</textarea>
            </div>
            <button type="button" id="edit_update_btn" >UPDATE</button>
            <button type="button" id="edit_cancel_btn" >CANCEL</button>
        </div>';

        echo $onEdit_comment; // = response

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
    if(isset($_GET['delete'])){
        $comment_db_id = $_GET['id'];
//        push_log("id= ".$id, "line 32");

        //해당 데이터를 DB에서 삭제한다
        $query = "DELETE FROM novelProject_comment WHERE id=".$comment_db_id;
        mysqli_query($db, $query);
        exit();
    }

    //전체 댓글을 화면에 표시해주는 부분
    //db에서 상위 댓글 정보를 날짜 순으로 모두 가져온다
    global $episode_db_id;

    $query = "SELECT*FROM novelProject_comment WHERE episode_db_id =".$episode_db_id; //각 글의 id에 해당하는 댓글만 고른다
    $result = mysqli_query($db, $query);

    $num = mysqli_num_rows($result);

    $comments = '<div id="display_area">';

    //각각의 댓글을 출력한다
    while($row=mysqli_fetch_array($result)){

//        $par_code = $row['id'];

        $comments .= '<div class="comment_box">
                <span class="delete" data-id="'.$row['id'].'">delete</span>
                <span class="edit" data-id="'.$row['id'].'">edit</span>
                <div class="display_name">'.$row['writer_username'].'</div>
                <div class="comment_text" style="word-break:break-all;">'.$row['content'].'</div>
                <div class="text-muted" style="font-size: small; margin-top: 10px">'.$row['date'].'</div>
                <input type="hidden" id="writer_email" name="writer_email" value="'.$row['writer_email'].'">
            </div>';
    }
    $comments .= '</div>';
?>