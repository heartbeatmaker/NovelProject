//$ : jquery 변수. jquery에서 사용하는 내장함수를 사용할 수 있다
//var : 자바스크립트 변수
//jquery란? 자바스크립트 라이브러리. js를 더 쉽게 사용할 수 있다


$(document).ready(function() {
    //DOM (Document Object Model; 페이지 구성요소?)이 모두 로딩되었을 때 아래의 코드를 실행한다는 의미


    //$("#abcd").hide()라고 id 값으로도 표현 가능.

    //<div class="child-comments" id="C-'.$par_code.'">'; -- 이렇게 선언되어 있다.
    // 여기서 id를 선택하지 않고 class를 선택한 이유?
    // class는 중복가능 -> child-comments라는 class를 가진 div를 모두 숨길 수 있다
    // id는 고유값이기 때문에, 특정 par_code를 가진 div만 숨긴다

    //"클래스가 "child-comments"인 요소를 숨긴다"는 의미
    // $(".child-comments").hide(); // .hide(): jquery 함수
    $(".child-comments").show(); // .hide(): jquery 함수

    //children id를 가진 요소를 클릭했을 때 일어나는 일
    //왜 a를 붙이지
    $("a#children").click(function(){

        //child-comments div의 id = C-par_code -> 이 div 전체를 토글한다
        //$(this) = 이벤트가 발생한 요소의 정보를 object로 표현한 것
        //.attr(name) : this 요소의 name 속성값을 가져와서 출력한다
        //name=par_code
        //"C-par_code 를 id로 가진 요소를 토글한다"는 의미
        var section = $(this).attr("name");
        $("#C-" + section).toggle();
        });

    //<참고>
    //js의 this와 jquery의 this는 다르다 [Javascript] this == [jQuery] $(this)[0] --어떻게 확인하지??
    //this = 이벤트가 발생한 태그 요소
    //$(this) = 이벤트가 발생한 요소의 정보를 object로 표현한 것

    //.attr(name, value) : name 속성을 추가한다

    //toggle()과 hide()의 차이점
    //toggle() = hide() + show()


    //댓글 내용을 입력하지 않은 채로 submit 버튼을 눌렀을 때 발생하는 이벤트
    //= 박스가 진동을 한다
    $(".form-submit").click(function () {
        var commentBox = $("#comment"); //comment 라는 id를 가진 textarea 이다
        var commentCheck = commentBox.val();
        //.val() : 양식(form)의 값을 가져오거나 값을 설정하는 메소드
        //여기서는 'commentBox 의 값 = 댓글 내용'을 가져온다
        //내용을 설정할 수도 있다. commentBox.val('abcd')

        if(commentCheck == '' || commentCheck == NULL){

            //addClass : class로 적용된 요소를 동적으로 처리할 수 있는 함수
            //commentBox에 "form-text-error"라는 이름을 가진 클래스 속성을 추가한다
            //박스를 진동하게 만든다
            commentBox.addClass("form-text-error");
            return false;
        }
    });

    //대댓글 내용을 입력하지 않은 채로 submit 버튼을 눌렀을 때 발생하는 이벤트
    //위와 동일
    $(".form-reply").click(function () {
        var replyBox = $("#new-reply");
        var replyCheck = replyBox.val();

        if(replyCheck == '' || replyCheck == NULL){
            replyBox.addClass("form-text-error"); // 이 코드는 작동하지 않는다. 왜?
            return false;
        }
    });

    //one()함수: 이벤트가 한번만 실행된다
    $("a#reply").one("click", function(){

        var comCode = $(this).attr("name"); // name = $par_code
        var postId = $(this).attr("post_id");
        var parent = $(this).parent(); //상위요소를 반환한다

        //append: parent 객체 내 마지막 요소 뒤에 아래 양식을 추가한다 = 대댓글 작성 창을 띄워준다
        //post에 값을 넘겨주기 위해서는 name이 지정되어 있어야 한다(id랑 혼동하지 말 것)
        parent.append("<br />" +
            "<form action='' method='post'>" +
            "<textarea class='form-text' name='new_reply_text' id='new-reply' required='required'></textarea>" +
            "<input type='hidden' name='code' value='"+comCode+"'/>" + //par_code와 post_id를 form으로 넘겨준다
            "<input type='hidden' name='post_id' value='"+postId+"'/>" +
            "<input type='submit' class='form-reply' name='new_reply_btn' value='Reply'/>" +
            "</form>")
    });

})