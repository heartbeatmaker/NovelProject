$(document).ready(function(){
    // alert('jquery works'); 작동하는지 확인

    //사용자가 POST 버튼을 클릭했을 때, ajax를 호출한다
    $(document).on('click', '#submit_btn', function(){

        var comment = $('#comment').val();
        var episode_db_id = $('#episode_db_id').val();
        

        //ajax: 서버에 요청을 하는 방식 중 하나
        //서버와 비동기적으로 통신한다. 비동기: 서버와 통신하는 동안 다른 작업을 할 수 있다는 의미
        //jquery에서 ajax() 함수를 사용하면 편리하게 서버와 통신할 수 있다
        $.ajax({
            url: 'comment_server.php', //서버측에서 가져올 페이지
            type: 'POST',//통신타입 설정. GET 혹은 POST. 아래의 데이터를 post 방식으로 넘겨준다.
            data: {//서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                'save': 1,
                'comment': comment,
                'episode_db_id': episode_db_id
            },

            //http 요청 성공 시 발생하는 이벤트
            success: function(response){
                //데이터 처리 후 -> 입력 상자를 초기화한다
                $('#comment').val('');

                //response=서버에서 받아온 데이터=$saved_comment=추가된 댓글
                //response를 display_area(=전체 댓글 div)의 마지막에 붙인다
                $('#display_area').append(response);

                //맨 아래로 스크롤 이동
                var bottom = window.innerHeight;
                $('body, html').animate({scrollTop:bottom}, 100);
            }
        });
    });

    //사용자가 delete 버튼을 클릭했을 때, ajax를 호출한다
    $(document).on('click', '.delete', function(){

        //이 댓글 쓴 사람의 email을 가져온다. 댓글 작성자와 현 사용자가 동일한지 확인용
        var writer_email = $(this).siblings('#writer_email').val();

            var id = $(this).data('id'); //episode_db_id
            // $(this).attr('data-id')와 동일
            var $clicked_btn = $(this);

            $.ajax({
                url: 'comment_server.php', //서버측에서 가져올 페이지
                type: 'POST', //통신타입 설정. GET 혹은 POST. 아래의 데이터를 get 방식으로 넘겨준다.
                data: { //서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                    'delete': 1,
                    'id': id,
                    'writer_email': writer_email
                },
                //http 요청 성공 시 발생하는 이벤트
                success: function(response){

                    if(response == 'success'){
                        console.log('successfully deleted the comment');
                        //remove the comment from screen
                        $clicked_btn.parent().remove();
                    }else if(response=='stranger'){

                        console.log('A stranger tried to delete the comment');
                        //댓 작성자만 지울 수 있다고 팝업띄워줌
                    }else if(response=='fail'){
                        console.log('unable to delete the comment');
                    }

                }
            });


    });


    //사용자가 edit 버튼을 클릭했을 때
    var edit_id;//episode_db_id
    var $edit_comment;

    var comment_div;
    var edit_span;
    var delete_span;
    $(document).on('click', '.edit', function(){


        edit_id = $(this).data('id');
        $edit_comment = $(this).parent(); // =해당 댓글 div

        //이 댓글 쓴 사람의 email을 가져온다. 댓글 작성자와 현 사용자가 동일한지 확인용
        var writer_email = $(this).siblings('#writer_email').val();

        // var $clicked_btn = $(this); //원래 댓글 div
        // name = $(this).siblings('.display_name').text();
        var comment = $(this).siblings('.comment_text').text();
        comment_div = $(this).siblings('.comment_text');
        edit_span = $(this);
        delete_span = $(this).siblings('.delete');

        $.ajax({
            url: 'comment_server.php', //서버측에서 가져올 페이지
            type: 'POST', //통신타입 설정. GET 혹은 POST
            data: { //서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                'edit': 1,
                'id': edit_id,
                'writer_email' : writer_email,
                'comment' : comment
            },
            //http 요청 성공 시 발생하는 이벤트
            success: function(response){

                if(response=='stranger'){

                    console.log('A stranger tried to edit the comment');

                }else if(response=='fail'){
                    console.log('unable to edit the comment');

                }else{
                    console.log('able to edit the comment');

                    $edit_comment.append(response); //기존 댓글 밑에 수정 박스를 넣어줌
                    comment_div.hide(); //나머지 불필요한 요소를 숨김
                    edit_span.hide();
                    delete_span.hide();
                }

            }
        });

    });


    //update 버튼을 누르면 -> 수정된 댓글 내용을 서버로 보낸다
    $(document).on('click','#edit_update_btn', function(){

        var comment = $('#edit_comment').val();

        $.ajax({
            url: 'comment_server.php',
            type: 'POST',
            data: {
                'update': 1,
                // 'name': name,
                'id': edit_id,
                'comment':comment
            },
            success: function(response){
                //edit_comment(기존 div)를 response(=새로 바뀐 comment div)로 바꾼다

                $edit_comment.replaceWith(response);
            }
        });


    });

    //수정 취소 버튼을 누르면 -> 원래대로 복귀
    $(document).on('click','#edit_cancel_btn', function(){

        //수정 창을 제거
        $("#edit_form_"+edit_id).remove();

        //위에서 숨긴 요소를 다시 보이게 만듦
        comment_div.show();
        edit_span.show();
        delete_span.show();

    });


});