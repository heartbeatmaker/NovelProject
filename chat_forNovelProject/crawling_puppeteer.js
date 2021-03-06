//puppeteer 로 데이터 크롤링하는 코드


//express: http 모듈에 여러 기능을 추가해서 쉽게 사용할 수 있게 만든 모듈
//node.js의 핵심 모듈인 http와 connect 컴포넌트를 기반으로 하는 웹 프레임워크(=미들웨어??)
//node.js 기본 모듈(http)만 이용하면 불편한 점이 있음. 이것을 개선해줌

const express = require('express');
const app = express();//애플리케이션 객체 생성
const http = require('http').createServer(app);// 웹서버 생성 후 express과 연결

var puppeteer = require('puppeteer');

const cheerio = require('cheerio'); //html 문서를 파싱하여, 필요한 정보만을 가져올 수 있도록 해준다
//jquery와 비슷함. 특정 html 요소에 접근하기 위해 jquery 셀렉터를 이용할 수 있다

const request = require('request'); //http 요청을 쉽게 보내는 라이브러리
//크롤링에서는, 웹 페이지의 html 문서를 그대로 가져오기 위해 쓴다

const Iconv = require('iconv').Iconv; //문자열의 인코딩을 변환시켜준다. 한글 깨짐 방지
const iconv = new Iconv('CP949', 'utf-8//translit//ignore'); //CP949를 UTF-8로 변환한다

//필요한 외부 모듈
const mysql = require('mysql');
const session = require('express-session');//세션을 쉽게 생성할 수 있게 해줌

//const와 var의 차이?

const connection = mysql.createConnection({
    host: '192.168.133.131',
    user: 'root',
    password: 'vhxmvhffldh@2019',
    database: 'webdata'
});

connection.connect();


// //정적인 파일을 서비스 하는 법 = 서버에 있는 파일(이미지, 텍스트)을 클라이언트에게 전달
// //public 이라는 폴더를 만들고, 그 안에 파일을 넣는다
// //ex) http://localhost:3000/bento 로 접속했을때 bento.png 이미지가 출력된다
// app.use(express.static('public'));


//----- 클라이언트가 루트 경로로 접근할 때마다 네이버 영화 사이트에서 영화 제목을 크롤링한다 -------
app.get('/crawling', (req, res) => {



        (async () => {

            const browser = await puppeteer.launch();
            const page = await browser.newPage();

    //quora의 book 페이지로 가라
            await page.goto('https://www.quora.com/topic/Books');


    //화면이 전환될 때까지 0.5초 기다리기
            await page.waitFor(500);


            const individual_story = await page.$('.AnswerStoryBundle');
            const text = await page.evaluate(individual_story => individual_story.textContent, individual_story);

            //브라우저 꺼라
            await browser.close();


            res.json(text);

        })();


});





//서버 실행
http.listen(3000, () => {// app.listen이 아닌 http.listen임에 유의한다. 무슨뜻?
    console.log('Connected at 3000');
});
//참고: 서버종료: close()


