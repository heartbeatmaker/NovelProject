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
    accessLog();

    $isEditMode='';
    //편집모드인지 확인
    if(isset($_GET['mode'])&&$_GET['mode']=='edit'){
        $isEditMode=true;
    }
    

    $story_db_id_inEditMode='';
    $title_retrieved='';
    $description_retrieved='';    //이미지도 가져와야함
    $genre_retrieved='';
    $author_username_retrieved='';
    //편집모드: 이 story가 db에 어떤 id로 저장되어 있는지, get방식으로 값을 전달받는다
    if(isset($_GET['id'])){
        $story_db_id_inEditMode=$_GET['id'];

        $query_retrieveSavedContent = "SELECT*FROM novelProject_storyInfo WHERE id ='$story_db_id_inEditMode'";
        $result_retrieveSavedContent = mysqli_query($db, $query_retrieveSavedContent);
        $row_retrieveSavedContent = mysqli_fetch_array($result_retrieveSavedContent);

        $title_retrieved = $row_retrieveSavedContent['title'];
        $description_retrieved = $row_retrieveSavedContent['description'];
        $genre_retrieved = $row_retrieveSavedContent['genre'];
        $author_username_retrieved = $row_retrieveSavedContent['author_username'];
    }



    //db에 저장된 fiction 장르를 가져온다
    $board_name = 'fiction';
    $genre ='';
    $sql = "SELECT*FROM novelProject_boardInfo WHERE name='$board_name'";

    global $db;
    $result = mysqli_query($db, $sql);

    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result);
        $genre_string = $row['category'];
    }

//    var_dump($_SESSION);


    //글 작성을 마치고 확인 버튼을 눌렀을 때 - 저장
    if(isset($_POST['btn_submit'])){

        $title = $_POST['title'];
        $description = $_POST['description'];
        $genre = $_POST['genre'];
        $author_email = $_SESSION['email'];
        if(isset($_POST['anonymous'])){
            $author_username = $_POST['anonymous'];
        }else{
            $author_username = $_SESSION['user'];
        }
        $isCompleted = 'N';
        $date = date("Y/m/d");

        push_log('title='.$title);
//        push_log('description='.$description);
//        push_log('genre='.$genre);
//        push_log('author_email='.$author_email);
//        push_log('author_name='.$author_username);
//        push_log('isCompleted='.$isCompleted);
//        push_log('date='.$date);

        if($isEditMode==true){ //수정모드일 때 - db수정

            //story db 수정
            $sql_storyInfo = "UPDATE novelProject_storyInfo 
SET lastUpdate='$date', title='$title', description='$description', genre='$genre', author_username='$author_username' 
WHERE id='$story_db_id_inEditMode'";

            $result_storyDB_edit = mysqli_query($db, $sql_storyInfo) or die(mysqli_error($db));

            if($result_storyDB_edit){
                push_log('story edit) query succeeded');

                header("location: page_TableOfContents.php?id=$story_db_id_inEditMode"); //redirect

            }else{
                push_log('story edit) error: story');
            }


        }else{ //최초작성 시 - db에 저장

            $sql_storyInfo = "INSERT INTO novelProject_storyInfo(title, description, genre, author_email, author_username, 
isCompleted, startDate, lastUpdate, numberOfEpisode)VALUES('$title','$description','$genre','$author_email'
,'$author_username','$isCompleted','$date','$date', 0)";


            $result = mysqli_query($db, $sql_storyInfo);

            if($result){

                $inserted_id = mysqli_insert_id($db);
                push_log('query succeeded. db id='.$inserted_id);

                header("location: page_writeNewEpisode.php?id=$inserted_id"); //redirect
            }
        }

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

    <!--    stylesheets-->
    <link href="../css/write/form-validation.css" rel="stylesheet">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>-->
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
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | Create a New Story
            </div>
            <div>
                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='../index.php'">Cancel</button>
            </div>
        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

    <div class="row">

<!--        북커버 삽입-->
        <aside class="col-md-3 blog-sidebar">
            <div style="text-align: center">
                <img src="../images/bookCover_dummy/1.jpg" height="300" width="200" style="margin-bottom: 20px"/>
                <button class="btn btn-outline-secondary my-2 my-sm-0" id="insert_image">Add a Cover</button>
                <!--            <button class="btn btn-outline-secondary my-2 my-sm-0" id="change_image">Change</button>-->
                <!--            <button class="btn btn-outline-secondary my-2 my-sm-0" id="remove_image">Remove</button>-->
            </div>
        </aside><!-- /.blog-sidebar -->

<!--        공간띄우기용-->
        <aside class="col-md-1 blog-sidebar"></aside>

<!--        세부사항-->
        <div class="col-md-8 blog-main" id="hot_post_list">

            <h4 style="margin-bottom: 30px">Story Details</h4>
            <form action="" method="post" class="needs-validation" novalidate>

                <div>
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Untitled Story"
                           value="<?php
                           if($isEditMode==true){
                               echo $title_retrieved;
                           } ?>" required>
                    <div class="invalid-feedback">
                        Title is required.
                    </div>
                </div>


                <div class="mb-3" style="margin-top: 30px;">
                    <label for="description">Description</label>
                    <textarea type="text" class="form-control" id="description" name="description" rows="5" required>
                        <?php if($isEditMode==true){
                            echo $description_retrieved;
                        }
                        ?>
                    </textarea>
                    <div class="invalid-feedback">
                        Please enter description of the story.
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="genre">Genre</label>
                        <select class="custom-select d-block w-100" id="genre" name="genre" required>
                            <option value=""><?php
                                if($isEditMode==true){
                                    echo $genre_retrieved;
                                }else{
                                    echo 'Choose..';
                                }
                                ?></option>

                            <?php
                            //string으로 이어서 가져온 장르를 개별로 분할하여 화면에 출력한다
                            $genre_split_array = explode(';', $genre_string);

                            for($i=0; $i<count($genre_split_array); $i++){
                                echo '<option>'.$genre_split_array[$i].'</option>';
                            }
                            ?>

                        </select>
                        <div class="invalid-feedback">
                            Please select a valid country.
                        </div>
                    </div>

                </div>

                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <?php
                    if($isEditMode==true && $author_username_retrieved=='Anonymous'){
                        echo '<input type="checkbox" class="custom-control-input" name="anonymous" id="anonymous" value="Anonymous" checked>';
                    }else{
                        echo '<input type="checkbox" class="custom-control-input" name="anonymous" id="anonymous" value="Anonymous">';
                    }
                    ?>
                    <label class="custom-control-label" for="anonymous">Anonymous</label>
                </div>

                <hr class="mb-4">
                <button class="btn btn-info btn-lg btn-block" type="submit" name="btn_submit" value="true">Done</button>
            </form>

        </div><!-- /.blog-main -->

    </div><!-- /.row -->

</main><!-- /.container -->

<!--    <footer class="blog-footer">-->
<!--        <p>2019 by <a href="https://twitter.com/mdo">@mdo</a>.</p>-->
<!--        <p>-->
<!--            <a href="#">Back to top</a>-->
<!--        </p>-->
<!--    </footer>-->

</body>

</html>


<div id="imageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add a Cover</h4>
        </div>
        <div class="modal-body">
            <form id="image_form" method="post" enctype="multipart/form-data">
                <p><label>Select Image</label>
                    <input type="file" name="image" id="image"/>
                </p><br />
                <input type="hidden" name="action" id="action" value="insert"/>
                <input type="hidden" name="image_id" id="image_id"/>
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
        // fetch_data();
        //
        // function fetch_data(){
        //     var action = "fetch";
        //     $.ajax({
        //         url:"action.php",
        //         method:"post",
        //         data:{action:action},
        //         success:function(data){ //data= 완성된 표
        //             $('#image_data').html(data);
        //             //html(data): image_data 요소 안에 내용(=data)을 넣는다
        //         }
        //     })
        // }

        //추가 버튼을 누르면 - 모달 창을 띄워준다
        //모달 - 팝업 차이: 모달은 페이지 안에 존재하는 하나의 레이어. 사용자가 block할 수 없음
        //팝업은 별도의 창을 띄우는 것. 현재 브라우저 창에 상관없이 제어가 가능
        //브라우저 옵션을 통해 열지 않도록 강제할 수 있음
        $('#insert_image').click(function(){
            $('#imageModal').modal('show');
            $('#image_form')[0].reset(); //reset(): form 양식 안의 모든 요소를 초기화하는 js 메소드
            $('.modal-title').text("Add an Image");

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
                        url:"action.php",
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
                    url:"action.php",
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
