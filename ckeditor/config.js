/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    config.extraPlugins = "youtube";
    config.allowedContent = true;
    config.removePlugins = 'easyimage, cloudservices';

    //ckeditor를 실행하는 파일 기준(같은 디렉토리에 upload.php라는 파일이 있어야 함)
    config.filebrowserUploadUrl = './upload.php';
    config.filebrowserUploadMethod = 'form'; //꼭 있어야 함

};
