var http = require('http');
var fs = require('fs');
var url = require('url'); //url이라는 모듈을 사용한다
var qs = require('querystring');


function templateHtml(title, list, body){
    var data = `
            <!doctype html>
                <html>
                <head>
                  <title>WEB1 - ${title}</title>
                  <meta charset="utf-8">
                </head>
                <body>
                  <h1><a href="/">WEB</a></h1>
                 ${list}
                 <a href="/create">create</a>
                 ${body}
                </body>
                </html>`;
    return data;
}

function templateList(filelist){
    var list = '<ul>';
    var i=0;
    while(i<filelist.length){
        list = list +`<li><a href="/?id=${filelist[i]}">${filelist[i]}</a></li>`;
        i+=1;
    }
    list = list + '</ul>'

    return list;
}


var app = http.createServer(function(request, response){
    var _url = request.url;
    var queryData = url.parse(_url, true).query;
    var pathname = url.parse(_url, true).pathname; //url에 들어있는 정보 중 pathname을 가져온다

    if(pathname === '/'){ //루트로 접근했을 경우 아래와같이 정상적인 정보를 띄워준다

        //홈화면으로 들어갔을 경우 (id값 = undefined)
        //없는 값일 경우, undefined가 뜬다
        if(queryData.id === undefined){

            fs.readdir('./data', function(error, filelist){
                console.log(filelist);//파일 이름이 담긴 배열을 출력한다

                var list = templateList(filelist);
                var title = 'Welcome';
                var description = 'Hello Node.js';

                var template = templateHtml(title, list, `<h2>${title}</h2>${description}`);

                response.writeHead(200);
                response.end(template); //클라이언트에게 넘겨줄 데이터(response 방식)
            });

        }else{ //홈화면이 아니라 세부페이지로 접근했을 경우(id값 있음)

            fs.readFile(`data/${queryData.id}.html`, 'utf8', function(err, data){

                fs.readdir('./data', function(error, filelist){

                    var list = templateList(filelist);
                    var title = queryData.id;
                    var description = data;

                    var template = templateHtml(title, list, `<h2>${title}</h2>${description}`);

                    response.writeHead(200);
                    response.end(template); //클라이언트에게 넘겨줄 데이터(response 방식)
                });
            });
        }
    }else if(pathname === '/create'){

        fs.readdir('./data', function(error, filelist){

            var list = templateList(filelist);
            var title = 'Hi';
            var description = 'Create pages';

            var template = templateHtml(title, list, `<form action="http://192.168.133.131:3000/process_create" method="post">
<p><input type="text" name="title"></p>
<p><textarea name="description"></textarea></p>
<p><input type="submit" name="submit_btn"></p>
</form>`);

            response.writeHead(200);
            response.end(template); //클라이언트에게 넘겨줄 데이터(response 방식)
        });

    }else if(pathname === '/process_create'){

        fs.readdir('./data', function(error, filelist){

            var body='';
            request.on('data', function(data){
                body += data;
            });
            request.on('end', function(){
                var post = qs.parse(body);
                var title = post.title;
                var description = post.description;

                fs.writeFile(`data/${title}.html`, description, 'utf8', function(err){
                    response.writeHead(302, {Location: `/?id=${title}.html`});
                    response.end('success'); //클라이언트에게 넘겨줄 데이터(response 방식)
                })
            })
        });

    }else{ //비정상적인 경로 -> not found 띄워줌
        response.writeHead(404);
        response.end('Not Found');
    }

});
app.listen(3000);