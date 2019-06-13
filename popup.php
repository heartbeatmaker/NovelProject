<!DOCTYPE html>
<html lang="en">


<head>
    <title>Notification</title>

    <script type="text/javascript">

        //쿠키를 저장하는 함수
        //쿠키의 이름, 값, 유효기간
        function setCookie(name, value, availableDays) {
            var today = new Date();
            today.setDate(today.getDate() + availableDays);

            //document 객체: html 문서의 정보를 제공하거나 조작하는 객체
            //document 객체의 .cookie 메소드를 이용하여 쿠키를 저장한다
            //클라이언트 측에서 정보를 저장하는 방법을 제공하고, 웹페이지의 요청에 따라 이 정보를 서버에 보낸다

            //쿠키의 정보를 지정한다. 각 정보 사이에는 ; 기호가 사용된다
            //path: 쿠키의 경로를 지정. 지정하지 않으면, 쿠키를 설정한 이 문서의 위치가 사용된다
            //domain: 지정하지 않으면 쿠키를 설정한 문서 위치의 도메인이 사용된다
            //expires: 쿠키가 종료되는 날짜를 지정. 그리니치 표준시로 저장된다. 날짜가 생략되면, 해당 쿠키는 세션동안에만 유효하다
            document.cookie = name + '=' + escape(value) + '; path=/; expires=' + today.toGMTString() + ';'
            //escape(String) = URI로 데이터를 전달하기 위해서 문자열을 인코딩하는 것. 인코딩된 문자열을 반환한다
            //만약 value 안에 &라는 글자를 넣으면 -> 시스템은 &를 문자열이 아니라 데이터 표시 기호로 인식한다
            //escape()를 사용하면, &을 %26으로 치환해준다 -> 문자열로 인식
        }

        //사용자가 체크박스를 누르면 -> 쿠키를 저장하고 팝업창을 닫는다
        function closePop() {

            //document 객체: html 문서의 정보를 제공하거나 조작하는 객체
            //forms[]: form 객체들로 이루어진 배열. document 객체의 하위 객체이다
            //form에서 사용되는 양식을 제어할 수 있다
            //html에서 기술한 순서대로 0번부터 참조된다. ex) html 페이지에 form 이 3개 있으면, 위에서부터 0번, 1번, 2번..
            //form 내 각 양식들의 이름(name)을 정의해주면 각 요소에 접근하기가 쉽다
            //name을 지정하지 않았을 경우, elements[]배열을 사용한다. ex) document.forms[0].elements[0].. document.forms[0].elements[1]

            //popup_checkBox라는 이름을 가진 form 요소(checkBox)에 접근, check 되었는지 확인한다
            if(document.forms[0].popup_checkBox.checked)

                //체크박스가 눌리면 쿠키를 저장한다
                setCookie('noPopup', 'true', 1);
            //자기 자신(창)을 닫는다. window.close()도 가능
            self.close();
        }

    </script>

    <meta charset="UTF-8">
</head>
<body>
    오늘 하루동안 보지 않기
    <form>
<!--        마우스 클릭이벤트: onclick 뒤에 원하는 속성과 함수를 넣으면 된다-->
        <input type="checkbox" name="popup_checkBox" onClick="closePop()"/>
    </form>


</body>
</html>