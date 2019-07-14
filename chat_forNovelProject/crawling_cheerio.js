//cheerio로 뉴욕타임즈 크롤링하는 코드


//express: http 모듈에 여러 기능을 추가해서 쉽게 사용할 수 있게 만든 모듈
//node.js의 핵심 모듈인 http와 connect 컴포넌트를 기반으로 하는 웹 프레임워크(=미들웨어??)
//node.js 기본 모듈(http)만 이용하면 불편한 점이 있음. 이것을 개선해줌

const express = require('express');
const app = express();//애플리케이션 객체 생성
const http = require('http').createServer(app);// 웹서버 생성 후 express과 연결


const cheerio = require('cheerio'); //html 문서를 파싱하여, 필요한 정보만을 가져올 수 있도록 해준다
//jquery와 비슷함. 특정 html 요소에 접근하기 위해 jquery 셀렉터를 이용할 수 있다

const request = require('request'); //http 요청을 쉽게 보내는 라이브러리
//크롤링에서는, 웹 페이지의 html 문서를 그대로 가져오기 위해 쓴다


const Iconv = require('iconv').Iconv; //문자열의 인코딩을 변환시켜준다. 한글 깨짐 방지
const iconv = new Iconv('CP949', 'utf-8//translit//ignore'); //CP949를 UTF-8로 변환한다


//필요한 외부 모듈
const mysql = require('mysql');
const session = require('express-session');//세션을 쉽게 생성할 수 있게 해줌
const schedule = require('node-schedule'); //특정 작업을 스케줄링

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




//매주 월요일 14시 8분에  아래의 작업을 실행한다
// var work = schedule.scheduleJob({hour:14, minute:8, dayOfWeek:1}, function(){

app.get('/crawling', (req, res) => {// http://192.168.133.131:3000/crawling 로 클라이언트가 접속했을 때

    let resultArr_src = [];
    let resultArr_title = [];
    let resultArr_author = [];
    let resultArr_desc = [];


    let url = "https://www.nytimes.com/books/best-sellers/";

    //url로 요청을 보낸다. 콜백함수: 이 페이지의 html 코드를 다 가져온다(=body)
    request({url, encoding: null}, function(error, response, body){

        // let htmlDoc = iconv.convert(body).toString(); //문자열 인코딩(한글 깨짐 방지) - 한글 데이터를 수집하지 않기 때문에 사용x

        const $ = cheerio.load(body); //jquery 셀렉터를 사용하기 위해서, body값을 cheerio 메소드에 넣는다

        // 이미지 주소
        let imgSrcArr = $('.css-9fprjv'); //셀렉터로 원하는 요소를 찾는다'
        console.log('result size:'+imgSrcArr.length);
        for(let i = 0; i < imgSrcArr.length; i++){
            resultArr_src.push($(imgSrcArr[i].children[0]).attr('src')); //원하는 요소를 찾으면, 배열에 넣는다
        }

        //책 제목
        let titleArr = $('.css-i1z3c1'); //셀렉터로 원하는 요소를 찾는다'
        console.log('result size:'+titleArr.length);
        for(let i = 0; i < titleArr.length; i++){
            resultArr_title.push($(titleArr[i]).text()); //원하는 요소를 찾으면, 배열에 넣는다
        }

        // 작가 이름
        let authorArr = $('.css-a0gqxo'); //셀렉터로 원하는 요소를 찾는다'
        console.log('result size:'+authorArr.length);
        for(let i = 0; i < authorArr.length; i++){
            resultArr_author.push($(authorArr[i]).text()); //원하는 요소를 찾으면, 배열에 넣는다
        }

        //책 설명
        let descArr = $('.css-5yxv3r'); //셀렉터로 원하는 요소를 찾는다'
        console.log('result size:'+descArr.length);
        for(let i = 0; i < descArr.length; i++){
            resultArr_desc.push($(descArr[i]).text()); //원하는 요소를 찾으면, 배열에 넣는다
        }


        var date = new Date();

        for(var i=0; i<55; i++){

            var genre ='';
            if(i<5){
                genre='Combined Print & E-Book Fiction';
            }else if(i>=5 && i<10){
                genre='Combined Print & E-Book Nonfiction';
            }else if(i>=10 && i<15){
                genre='Hardcover Fiction';
            }else if(i>=15 && i<20){
                genre='Hardcover Nonfiction';
            }else if(i>=20 && i<25){
                genre='Paperback Trade Fiction';
            }else if(i>=25 && i<30){
                genre='Paperback Nonfiction';
            }else if(i>=30 && i<35){
                genre='Advice, How-To & Miscellaneous';
            }else if(i>=35 && i<40){
                genre='Children’s Middle Grade Hardcover';
            }else if(i>=40 && i<45){
                genre='Children’s Picture Books';
            }else if(i>=45 && i<50){
                genre='Children’s Series';
            }else if(i>=50 && i<55){
                genre='Young Adult Hardcover';
            }

            var info={title: resultArr_title[i], author: resultArr_author[i], description: resultArr_desc[i], img_src:resultArr_src[i] ,genre: genre, listed_date: date};

            connection.query('INSERT INTO novelProject_bestseller SET ?', info, function(err, result) {
                if(err){
                    console.log('mysql err: '+err);
                }
            });


        }



    });

});



//서버 실행
http.listen(3000, () => {// app.listen이 아닌 http.listen임에 유의한다. 무슨뜻?
    console.log('Connected at 3000');
});
//참고: 서버종료: close()


