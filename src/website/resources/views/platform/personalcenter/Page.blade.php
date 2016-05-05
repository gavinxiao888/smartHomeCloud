
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/static/platform/upload/imgareaselect-default.css" />
<link rel="stylesheet" type="text/css" href="/static/platform/upload/layout.css" />
<link href="/static/platform/upload/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/static/platform/upload/jquery.imgareaselect.pack.js"></script>
<!--<script type="text/javascript" src="/static/platform/upload/uploadify/swfobject.js"></script>-->
<script type="text/javascript" src="/static/platform/upload/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript"> 
　　$(document).ready(function(){
		//uploadify设置
		$('#pic_upload').uploadify({ 
		'uploader' : '/static/platform/upload/uploadify/uploadify.swf', 
		'script' : '/static/platform/upload/process.php', 
		'cancelImg' : '/static/platform/upload/uploadify/cancel.png', 
		}); 
　　}

	function previewImage(file)
        {
          var MAXWIDTH  = 150; 
          var MAXHEIGHT = 200;
          var div = document.getElementById('oriImage');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=generated>';
              var img = document.getElementById('generated');
              img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
              }
              var reader = new FileReader();
              reader.onload = function(evt){img.src = evt.target.result;}
              reader.readAsDataURL(file.files[0]);
          }
          else //兼容IE
          {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=generated>';
            var img = document.getElementById('generated');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
			//img.attr('src',src);
          }
        }
        function clacImgZoomParam( maxWidth, maxHeight, width, height ){
            var param = {top:0, left:0, width:width, height:height};
            if( width>maxWidth || height>maxHeight )
            {
                rateWidth = width / maxWidth;
                rateHeight = height / maxHeight;
                 
                if( rateWidth > rateHeight )
                {
                    param.width =  maxWidth;
                    param.height = Math.round(height / rateWidth);
                }else
                {
                    param.width = Math.round(width / rateHeight);
                    param.height = maxHeight;
                }
            }
             
            param.left = Math.round((maxWidth - param.width) / 2);
            param.top = Math.round((maxHeight - param.height) / 2);
            return param;
        }
</script> 
<title>图片剪切上传</title>
</head>

<body class="home">
<form>
<fieldset>
<legend>图片上传</legend>
<div id="queue"></div>
<input type="file" name="file" id="pic_upload"/><br />
<a id='uploadLink' href="javascript:$('#pic_upload').uploadifyUpload();">上传图片 </a>

<input type="file" onchange="previewImage(this)" />
<div id="uploadInfo"></div>
</fieldset> 
</form>
<div id="twoColLayout">
<div id="primaryContent">
<div >
<h3>图片预览</h3>
<div id='oriImage' class="img_v"> 
</div> 
</div>
<div >
裁剪结果：
<div id="cropResult" class="cbb" > 
</div> 
</div>
</div>
<div id="sideContent"> 
裁剪预览：
<div id="preview"> 
</div>
</div>
</div> 
</body>
</html>