<?php
//page_CreateNewStory.php 에서 북커버 이미지를 저장하는 코드

require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';
require_once '../functions.php';

global $db;


//이미지 최초 저장
if(isset($_POST)){
    //temp_name에 특수문자가 포함되어 있을 경우, 이것을 backslash 를 사용하여 escape 해준다
    //파일이 업로드되기 전까지, 파일은 웹서버의 임시경로에 저장된다. 이 때 저장된 이름이 tmp_name
    //업로드 완료되면 임시파일은 삭제된다

    if($_POST['action']=='insert'){

        push_log2('insert');

        $save_dir = "./upload";
        $name = generateRandomString().'_'.$_FILES['image']['name'];
        $dest = $save_dir."/".$name;

        $file_name = $_FILES['image']['tmp_name'];

        //파일에 새로운 이름을 붙여서, 원하는 업로드 경로로 옮긴다
        //move_uploaded_file: 서버로 전송된 파일을 저장할 때 사용하는 함수
        if(move_uploaded_file($_FILES['image']['tmp_name'], $dest)){
            push_log2('succeed');
        }else{
            push_log2('failed');
        }

        $_SESSION['book_cover'] = $name; //파일 이름을 세션에 저장한다
    }


    if($_POST['action']=='fetch'){

        push_log2('fetch');
        if(isset($_SESSION['book_cover'])){
            push_log2('file exists in session');
        }

        $output = '<img src="upload/'.$_SESSION['book_cover'].'" height="300" width="200"
        style="margin-bottom: 20px"/>';

        echo $output;
    }

//        $query = "INSERT INTO images(name) VALUES ('$file')";
//        if(mysqli_query($db, $query)){
//            echo 'Image Inserted into Database';
//        }
}

//
//if(isset($_POST['action'])){
//
//    //표와 버튼 이미지 등 전체 레이아웃을 출력해준다
//    if(($_POST['action']) == 'fetch'){
//
//
//        //이미지를 포함한 각 행을 출력한다
//        //base64: BINARY 데이터를 ASCII 문자열(=TEXT)로 바꾸는 인코딩방식
//        //DB에 이미지가 BINARY 형식으로 저장되어 있음
//        //이 문자열 자체가 이미지이기 때문에 이미지 파일이 없어도 브라우저에서 이미지를 렌더링한다
//        //URI(=외부자원)를 사용하여 이미지를 화면에 이미지를 표시하기 위해서는, HTTP 요청이 필요하다
//        //반면 data URI는 따로 서버에 요청을 할 필요가 없다
//        //브라우저마다 글자 수 제한이 있다. 큰 파일 전송 X
//
//            $output = '<img src="data:image/jpeg;base64,'.base64_encode($_SESSION['book_cover']).'" height="300" width="200"
//            style="margin-bottom: 20px"/>';
//
//        echo $output; //완성된 데이터를 브라우저로 보냄
//    }
//
//
//    //이미지 변경
//    if($_POST['action'] == "update"){
//        $file = addslashes(file_get_contents($_FILES['image']['tmp_name']));
//        $query = "UPDATE images SET name= '$file' WHERE id= '".$_POST["image_id"]."'";
//        if(mysqli_query($db, $query)){
//            echo 'Image Updated into Database';
//        }
//    }
//
//    //이미지 삭제
//    if($_POST['action'] == "delete"){
//        $query = "DELETE FROM images WHERE id= '".$_POST["image_id"]."'";
//        if(mysqli_query($db, $query)){
//            echo 'Image Deleted from Database';
//        }
//    }
//}
?>