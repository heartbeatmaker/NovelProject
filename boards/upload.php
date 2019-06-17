<?php
//ckeditor로 이미지를 업로드하는 코드(서버측)
//이 파일 이름 바꾸면 안됨. ckeditor config에 upload.php라는 파일에서 이미지 처리하겠다고 적어놓음

//ckeditor 소스 폴더는 novel_project 상위폴더에 있음
//거기서 config.js 수정가능

//require_once '../log/log.php';
require_once '../functions.php'; //랜덤 문자열을 만드는 함수가 여기에 있음

//에러 해결법
//절대경로와 상대경로를 구분할 것('/가 없는것', '/', './' 차이점)
//이미지를 저장하는 폴더에 777 권한을 부여해야함
//config.filebrowserUploadMethod = 'form' 있어야함

$uploadfullPath = "/usr/local/apache/htdocs/novel_project/images/ck_uploads/";
$imageBaseUrl = "/novel_project/images/ck_uploads/";
$CKEditor = $_GET['CKEditor'] ;
$funcNum = $_GET['CKEditorFuncNum'] ;
$langCode = $_GET['langCode'] ;
$url = '' ;
$message = '';


if (isset($_FILES['upload'])) {

    //html의 form태그를 이용하여 전송한 파일은 $_FILES를 통해 접근할 수 있다

    //파일이 업로드되기 전에, 파일은 웹서버의 임시 디렉토리에 저장된다. 이 때 저장된 이름이 tmp_name
    //업로드 완료되면 임시파일은 삭제된다

    //$_FILES["upload"]["tmp_name"]: 파일이 임시로 저장된 경로
    //$_FILES["upload"]["name"]: 파일의 실제 이름

    //파일의 새 이름
    //파일 이름이 겹치는 것을 방지하기 위해, 이름에 랜덤 문자열을 추가한다
    $name = generateRandomString().$_FILES['upload']['name'];

    //파일에 새로운 이름을 붙여서, 원하는 업로드 경로로 옮긴다
    //move_uploaded_file: 서버로 전송된 파일을 저장할 때 사용하는 함수
    move_uploaded_file($_FILES["upload"]["tmp_name"], $uploadfullPath . $name);
    $url = $imageBaseUrl . $name ;
    $message = 'succeeded';


}else { $message = '업로드된 파일이 없습니다.'; }

echo "<script type='text/javascript'>; window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message')</script>";

?>