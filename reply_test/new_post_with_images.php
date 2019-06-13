<?php

require_once '../connect.php';
require_once 'functions.php';

$id = mysqli_insert_id($db)+1; //현재 글이 저장될 id를 구한다

echo $id;

//글쓰기 완료 버튼을 누르면
if(isset($_POST['submit_btn'])) {

    $title = ($_POST['title']);
    $content = ($_POST['content']);
    $code = generateRandomString(); //글의 고유코드
    $post_date = date('Y-m-d H:i:s'); //작성시각

    //글 내용 저장
    $sql_info = "INSERT INTO blog_post(title, content, code, date)VALUES('$title','$content','$code','$post_date')";
    mysqli_query($db, $sql_info);

    //코드로 검색, 이 글의 id를 찾음 -- 최선입니까?
    $query = "SELECT*FROM blog_post WHERE code ='$code'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);
    $id = $row['id'];

    //글 읽는 창으로 이동
    header("location: read_post.php?id=".$id);
}

//취소 버튼을 누르면
if(isset($_POST['cancel_btn'])) {

    //사진 db 초기화

    header("location: blog.php");
}
?>


<!DOCTYPE html>
<html>
    <head>

        <title>Porfolio Site</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/sidebar.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>

    <div id="wrapper">
        <!--        Sidebar-->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <!--                ul = unordered list-->
                <!--                li = list item-->
                <li><a href="../index.html">Home</a></li>
                <li><a href="#">About Me</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Study</a></li>
                <li><a href="../blog/blog.php">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>

        <!--        Page content-->
        <div id="page-content-wrapper">
            <div class="container">
                <h1>New Post</h1>
                <form action="new_post.php" method="post">
                    <!--                required: 공백 허용x -->
                    <input type="text" name="title" placeholder="Title">
                    <textarea name="content" placeholder="Content"></textarea>
                    <button type="submit" name="submit_btn">Submit</button>
                    <button type="submit" name="cancel_btn">Cancel</button>
                </form>
            </div>


        </div>

    </div>


        <br /><br /><br /><br /><br /><br /><br /><br />
        <div class="container" style="width:500px;">
            <h3 align="center">Upload Images</h3>
            <br />
            <div align="right">
                <button type="button" name="add" id="add" class="btn btn-success" data-postId="<?php echo $id ?>">Add</button>
            </div>
            <br />
            <div id="image_data">

            </div>
        </div>


    </body>

</html>
<div id="imageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Images</h4>
        </div>
        <div class="modal-body">

<!--            post: 전송 방식. 파일 업로드의 경우 꼭 post를 사용해야 한다-->
<!--            enctype: 전송되는 데이터의 인코딩 방식을 설정한다-->
<!--            파일이나 이미지를 서버로 전송할 경우 반드시 이 형식을 사용-->
            <form id="image_form" method="post" enctype="multipart/form-data">
                <p><label>Select Image</label>
                <input type="file" name="image" id="image"/>
                </p><br />
                <input type="hidden" name="action" id="action" value="insert"/>
                <input type="hidden" name="image_id" id="image_id"/>
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $id ?>"/>
                <input type="submit" name="insert" id="insert" value="insert" class="btn btn-info"/>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

</div>

<script>
    $(document).ready(function(){

        //db에 저장되어 있는 모든 이미지를 표에 넣어서 화면에 출력한다
        fetch_data();

        function fetch_data(){
            var action = "fetch";
            $.ajax({
                url:"image_action.php",
                method:"post",
                data:{action:action},
                success:function(data){ //data= 완성된 표
                    $('#image_data').html(data);
                    //html(data): image_data 요소 안에 내용(=data)을 넣는다
                }
            })
        }

        //추가 버튼을 누르면 - 모달 창을 띄워준다
        //모달 - 팝업 차이: 모달은 페이지 안에 존재하는 하나의 레이어. 사용자가 block할 수 없음
        //팝업은 별도의 창을 띄우는 것. 현재 브라우저 창에 상관없이 제어가 가능
        //브라우저 옵션을 통해 열지 않도록 강제할 수 있음
        $('#add').click(function(){
            $('#imageModal').modal('show');
            $('#image_form')[0].reset(); //reset(): form 양식 안의 모든 요소를 초기화하는 js 메소드
            $('.modal-title').text("Add Image");

            //각 양식에 값을 넣는다
            $('#image_id').val('');
            $('#action').val('insert');
            $('#insert').val('insert');
        });


        $('#image_form').submit(function(event){ //js에서는 id를 사용하여 form 객체를 가져오는 것이 가능하다

           //클릭이벤트 외에 별도의 브라우저 행동을 막기 위해 사용 ex) 스크롤이 위로 올라가는 것을 막음
           event.preventDefault();

           //파일의 이름을 가져온다
           var image_name = $('#image').val();

           //이미지 선택 했는지 확인
           if(image_name == ''){
               alert("Please Select Image");
               return false;
           }
           //이미지 이름이 있다면, 서버로 요청을 보낸다
           else{

               //파일의 확장자를 확인한다
               //파일 이름을 . 단위로 분리한 후, 소문자 형태의 확장자만 남긴다
               //pop(): 배열의 마지막 요소를 제거한 후, 그 요소를 반환한다
               var extension = $('#image').val().split('.').pop().toLowerCase();

               //jquery inArray = js indexOf
               //배열 안에 특정한 값이 있는지 검사한다
               //gif, png, jpg, jpeg 에 해당하는 확장자가 없다면
               if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1){
                   alert("Invalid Image File");
                   $('#image').val('');
                   return false;
               }
               //유효한 확장자라면, 서버에 요청한다
               else{
                   $.ajax({
                       url:"image_action.php",
                       method: "POST",
                       //formData: 파일을 전송할 때, 직접 폼 형태(key/value)로 보낼 수 있게 해주는 객체
                       data: new FormData(this), //formData 객체에 image_form 의 값을 넣어준다
                       //formData로 파일을 전송할 때, contentType과 processData는 아래와 같이 설정해준다
                       contentType:false,
                       processData:false,
                       success:function(data){
                           alert(data); //이건 왜하지?
                           fetch_data(); //이미지를 포함한 새로운 행을 화면에 표시한다
                           $('#image_form')[0].reset();
                           $('#imageModal').modal('hide');
                       }
                   });
               }
           }
        });

        //이미지 변경 버튼을 눌렀을 때
        $(document).on('click', '.update', function(){

            //각종 속성 변경
            //image_id key에 해당 이미지의 id를 값으로 넣어준다. db에서 찾아야 하므로. 원래는 값이 비어있음
            $('#image_id').val($(this).attr("id"));
            $('#action').val("update");
            $('.modal-title').text("Update Image");
            $('#insert').val("Update");
            $('#imageModal').modal("show");

            //db에서 이미지 이름만 바꿨는데 왜 화면이 업데이트되지???
            //fetch_data() 해야되는거 아님?
        });

        //이미지 삭제 버튼을 눌렀을 때
        $(document).on('click', '.delete', function() {

            //각종 속성 변경
            //image_id key에 해당 이미지의 id를 값으로 넣어준다. db에서 찾아야 하므로
            var image_id = $(this).attr("id");
            var action = "delete";
            if(confirm("Want to remove this image?")){
                $.ajax({
                    url:"image_action.php",
                    method:"POST",
                    data:{image_id:image_id, action:action},
                    success:function(data){
                        alert(data);
                        fetch_data(); //화면의 표를 새로고침한다
                    }
                })
            }
            else{
                return false;
            }
        })
    });
</script>