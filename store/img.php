<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<div>
    <input type="text" id="image_name">
</div>
<div>
    <input type="file" name="file_name" id="file_name" multiple="multiple" />
    <img src="" id="preview"  >
</div>
<div>
    <button type="button" id="upload" >Download</button>

    <img src="" id="output">
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="/assets/js/download.js"></script>
<script>

    var resizeImage = function (settings) {
        var file = settings.file;
        var maxSize = settings.maxSize;
        var reader = new FileReader();
        var image = new Image();
        var canvas = document.createElement('canvas');
        var dataURItoBlob = function (dataURI) {
            var bytes = dataURI.split(',')[0].indexOf('base64') >= 0 ?
                atob(dataURI.split(',')[1]) :
                unescape(dataURI.split(',')[1]);
            var mime = dataURI.split(',')[0].split(':')[1].split(';')[0];
            var max = bytes.length;
            var ia = new Uint8Array(max);
            for (var i = 0; i < max; i++)
                ia[i] = bytes.charCodeAt(i);
            return new Blob([ia], { type: 'image/jpeg'});
        };
        var resize = function () {
            var width = image.width;
            var height = image.height;

            height *= maxSize / width;
            width = maxSize;

            canvas.width = width;
            canvas.height = height;
            canvas.getContext('2d').drawImage(image, 0, 0, width, height);
            var dataUrl = canvas.toDataURL('image/jpeg');
            return dataURItoBlob(dataUrl);
        };
        return new Promise(function (ok, no) {
            if (!file.type.match(/image.*/)) {
                no(new Error("Not an image"));
                return;
            }
            reader.onload = function (readerEvent) {
                image.onload = function () { return ok(resize()); };
                image.src = readerEvent.target.result;
            };
            reader.readAsDataURL(file);
        });
    };

    let fileMap = new Map();

    $(document).ready(function() {
        $("#file_name").on("change", select);
        $("#upload").off("click").on("click", upload);
    });

    var sizeDic = {};
    sizeDic['naverMain'] = 1000;
    sizeDic['naverSub'] = 860;
    sizeDic['kakao'] = 750;

    var nameArr = ['naverMain','naverSub','kakao'];

    function select(){
        var imgName = '\/'+$('#image_name').val();
        console.log(imgName);
        for (var i in sizeDic){
            var rr = 0;
        $.each(this.files, function(index, file){

                var reader = new FileReader();
                reader.onload = function(e){
                    // document.getElementById('preview').src = e.target.result;
                };
                reader.readAsDataURL(file);

                // resizing 이전 파일
                fileMap.set("1_"+file.name,file);


                resizeImage({
                    file: file,
                    maxSize: sizeDic[i]
                }).then(function (resizedImage) {
                    reader.onload = function(e){
                        // document.getElementById('output').src = URL.createObjectURL(resizedImage);
                    };
                    reader.readAsDataURL(file);

                    // resizing 이후 파일
                    fileMap.set(nameArr[rr]+imgName,resizedImage);
                    rr++;
                    // var path = i + imgName
                    // download(resizedImage,path,"image/jpg");
                });

        });
        }

    }

    function logMapElements(value, key, map) {
        download(value,key,"image/jpg");
    }

    function upload(){
        fileMap.forEach(logMapElements);
    }
</script>
</body>
</html>
