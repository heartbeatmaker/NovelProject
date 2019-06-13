var testFolder = './data/';
var fs = require('fs');

fs.readdir(testFolder, function(error, filelist){
    console.log(filelist); //배열이 출력된다
});