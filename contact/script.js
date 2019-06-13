$(document).ready(function() {

    $('#submit_button').click(function () {
        var name = $('#form_name').val();
        var email = $('#form_email').val();
        var subject = $('#form_subject').val();
        var message = $('#form_message').val();

        $.ajax({
            url: 'send_mail.php', //서버측에서 가져올 페이지
            type: 'POST',//통신타입 설정. GET 혹은 POST. 아래의 데이터를 post 방식으로 넘겨준다.
            data: {//서버에 요청 시 전송할 파라미터. key/value 형식의 객체. data type을 설정할 수 있다(여기선 안함)
                'send': 1,
                'name': name,
                'email': email,
                'subject': subject,
                'message': message
            },

            //http 요청 성공 시 발생하는 이벤트
            success: function (response) {
                //데이터 처리 후 -> 입력 상자를 초기화한다
                $('#form_name').val('완료');
                $('#form_email').val('완료');
                $('#form_subject').val('완료');
                $('#form_message').val('완료');

                alert("hehe");
                //결과를 표시해준다
                $('#result').text(response);
            }
        });


    });
});

