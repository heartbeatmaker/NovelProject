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

    <script src="../ckeditor/ckeditor.js"></script>

    <title>ReadMe | Fiction</title>
</head>
<body>


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-8" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | Lord of the Rings
            </div>
            <div>
                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='mainPage.php'">Cancel</button>
            </div>
        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

        <!--        세부사항-->
        <div class="col-md-10 blog-main" style="margin:0px auto">

            <form class="needs-validation" novalidate>

                <div>
                    <input type="text" class="form-control" id="title" placeholder="Title" value="" required>
                    <div class="invalid-feedback">
                        Title is required.
                    </div>
                </div>

                <div class="mb-3" style="margin-top: 30px;">
                    <textarea type="text" class="form-control" id="content" required></textarea>
                    <div class="invalid-feedback">
                        Please enter description of the story.
                    </div>
                </div>

                <button class="btn btn-info btn-lg btn-block" type="submit" style="margin-top: 50px">Publish</button>
            </form>





        </div><!-- /.blog-main -->



</main><!-- /.container -->


</body>

<script>
    CKEDITOR.replace('content');
</script>
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