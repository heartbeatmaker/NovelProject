const express = require('express');
const multer = require('multer'); //express에 multer 모듈 적용
const ejs = require('ejs'); //= embedded javascript Template = 템플릿 엔진
//동적인 연산 결과(변수)를 문서에 나타낼 수 있다
const path = require('path');
//multer란? 사용자가 전송한 파일을 처리하는 모듈
//express는 사용자가 업로드한 파일을 받아서 저장하는 기능을 제공하지 않는다 -> multer 모듈을 설치한다

// Set The Storage Engine
const storage = multer.diskStorage({ //diskStorage: 파일의 저장경로, 파일명을 처리하기 위해 사용하는 엔진
    destination: './public/uploads/', //파일이 저장되는 곳
    filename: function(req, file, cb){
        cb(null,file.fieldname + '-' + Date.now() + path.extname(file.originalname));
        //callback(에러, 파일이름)
        //path.extname(file.originalname) : 해당 파일의 확장자를 반환한다
    }
});

// Init Upload
const upload = multer({
    storage: storage, //위에서 정의한 storage
    limits:{fileSize: 1000000}, //1000000byte 이상의 파일을 올릴 경우, 에러 메시지를 발생시킨다
    fileFilter: function(req, file, cb){
        checkFileType(file, cb); //이미지 파일이 맞는지 확인한다
    }
}).single('myImage'); //다중이미지 업로드 할 거면 single x

// Check File Type
function checkFileType(file, cb){
    // 허용되는 확장자를 정의한다
    const filetypes = /jpeg|jpg|png|gif/;
    // 파일의 확장자를 검사한다 - boolean을 반환한다
    const extname = filetypes.test(path.extname(file.originalname).toLowerCase());
    // mimetype이 image/jpeg(or jpg or png or gif) 인지 확인한다 - 이건 왜 검사하지?
    const mimetype = filetypes.test(file.mimetype);

    if(mimetype && extname){ //확장자와 mimetype이 true라면
        return cb(null,true);//callback(에러, 리턴값) : null인 에러메시지를 콜백한다
    } else {
        cb('Error: Images Only!'); //에러메시지를 지정하여 콜백한다
    }
}

// Init app
const app = express();

// EJS 연동
// view engine으로 ejs를 사용하겠다는 의미
app.set('view engine', 'ejs');

// Public Folder
// 정적인 파일이 위치할 디렉토리 이름을 선언
app.use(express.static('./public'));

app.get('/', (req, res) => res.render('index')); //3000포트로 접속하면 index 화면을 보여주도록 라우터 연결

//브라우저의 form에서 post방식으로 보낸 데이터를 처리하는 부분
app.post('/upload', (req, res) => {

    //위에서 지정한 upload 메소드(저장경로, 파일이름, 허용 용량 설정)를 여기에서 호출
    upload(req, res, (err) => {
        if(err){//에러 발생 시, 템플릿을 재 렌더링하여 'err'라는 메시지를 화면에 띄운다
            res.render('index', { // index.ejs에 msg라는 키워드로 err메시지를 전달한다
                msg: err
            });
        } else {
            // console.log(req.file); //파일 정보 object를 리턴한다
            // res.send('test');
            //이부분에서 파일을 db에 저장하면 된다

            if(req.file == undefined){ //파일을 선택하지 않고 submit을 누르면 -> 에러 출력
                res.render('index', {
                    msg: 'Error: No File Selected!'
                });
            } else { //이미지 파일을 선택했을 때
                res.render('index', {
                    msg: 'File Uploaded!',
                    file: `uploads/${req.file.filename}` //uploads 폴더에 있는 해당 파일의 주소를 넘긴다
                });
            }
        }
    });
});

const port = 3000;

app.listen(port, () => console.log(`Server started on port ${port}`));