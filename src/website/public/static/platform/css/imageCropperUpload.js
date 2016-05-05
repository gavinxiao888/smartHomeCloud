(function ($) {  
    var defaultSettings = {  
        url: "",                                 //�ϴ���ַ    
        fileSuffixs: ["jpg", "png"],             //�����ϴ����ļ���׺���б�    
        errorText: "�����ϴ���׺Ϊ {0} ���ļ���", //������ʾ�ı�������{0}���ᱻ�ϴ��ļ��ĺ�׺���滻    
        onCheckUpload: function (text) { //�ϴ�ʱ����ļ���׺����������fileSuffixs������ʱ�����Ļص�������(textΪ������ʾ�ı�)    
        },  
        onComplete: function (msg) { //�ϴ���ɺ�Ļص�����[���ܳɹ���ʧ�ܣ�������������](msgΪ����˵ķ����ַ���)   
            alert(msg);  
        },  
  
        onChosen: function (file, obj, fileSize, errorTxt) { /*ѡ���ļ���Ļص�������(fileΪѡ���ļ��ı���·��;objΪ��ǰ���ϴ��ؼ�ʵ��, 
                                                                    fileSizeΪ�ϴ��ļ���С����λKB[ֻ����isFileSizeΪtrueʱ���˲�������ֵ], 
                                                                     errorTxtΪ��ȡ�ļ���Сʱ�Ĵ����ı���ʾ)  */  
            //alert(file);    
        },  
        cropperParam: {}, //ͼƬ��ȡ�������ã��˲�����ΪJcrop�������  
        isFileSize: false,//�Ƿ��ȡ�ļ���С  
        perviewImageElementId: "",//����Ԥ���ϴ�ͼƬ��Ԫ��id���봫��һ��divԪ�ص�id��    
  
        perviewImgStyle: null//��������ͼƬԤ��ʱ����ʽ���ɲ����ã��ڲ����õ�����¶��ļ��ϴ�ʱֻ����ʾһ��ͼƬ������{ width: '100px', height: '100px', border: '1px solid #ebebeb' }    
    };  
  
  
    $.fn.cropperUpload = function (settings) {  
  
        settings = $.extend({}, defaultSettings, settings || {});  
  
        return this.each(function () {  
            var self = $(this);  
  
            var upload = new UploadAssist(settings);  
  
            upload.createIframe(this);  
  
            //�󶨵�ǰ��ť����¼�    
            self.bind("click", function (e) {  
                upload.chooseFile();  
            });  
  
            //���ϴ��������ʵ������ŵ���ǰ�����У������ⲿ��ȡ    
            self.data("uploadFileData", upload);  
  
            //������iframe�е��Ǹ�iframe�������¼���Ҫ�ӳٰ�    
            window.setTimeout(function () {  
                $(upload.getIframeContentDocument().body).find("input[type='file']").change(function () {  
                    if(!this.value) return;  
                    var fileSuf = this.value.substring(this.value.lastIndexOf(".") + 1);  
  
  
                    //����Ƿ�Ϊ�����ϴ����ļ�    
                    if (!upload.checkFileIsUpload(fileSuf, upload.settings.fileSuffixs)) {  
                        upload.settings.onCheckUpload(upload.settings.errorText.replace("{0}", fileSuf));  
                        return;  
                    }  
  
                    if (upload.settings.isFileSize) {  
                        var size = perviewImage.getFileSize(this, upload.getIframeContentDocument());  
                        var fileSize, errorTxt;  
                        if (size == "error") {  
                            errorTxt = upload.errorText;  
                        } else {  
                            fileSize = size;  
                        }  
                        //ѡ�к�Ļص�    
                        upload.settings.onChosen(this.value, this, fileSize, errorTxt);  
                    } else {  
                        //ѡ�к�Ļص�    
                        upload.settings.onChosen(this.value, this);  
                    }  
  
  
                    //�Ƿ�����ͼƬԤ��    
                    if (upload.settings.perviewImageElementId) {  
                        var main = perviewImage.createPreviewElement("closeImg", this.value, upload.settings.perviewImgStyle);  
                        $("#" + upload.settings.perviewImageElementId).append(main);  
                        var div = $(main).children("div").get(0);  
  
                        perviewImage.beginPerview(this, div, upload.getIframeContentDocument(), upload);  
                    }  
                });  
  
                //Ϊ������iframe�ڲ���iframe��load�¼�    
                $(upload.getIframeContentDocument().body.lastChild).on("load", function () {  
                    var dcmt = upload.getInsideIframeContentDocument();  
                    upload.submitStatus = true;  
                    if (dcmt.body.innerHTML) {  
  
  
                        if (settings.onComplete) {  
                            settings.onComplete(dcmt.body.innerHTML);  
                        }  
  
  
                        dcmt.body.innerHTML = "";  
                    }  
                });  
            }, 100);  
        });  
    };  
})(jQuery);  
  
  
//�ϴ�������  
function UploadAssist(settings) {  
    //��������  
    this.settings = settings;  
    //������iframeΨһ����  
    this.iframeName = "upload" + this.getTimestamp();  
    //�ύ״̬  
    this.submitStatus = true;  
    //���IE�ϴ���ȡ�ļ���Сʱ�Ĵ�����ʾ�ı�  
    this.errorText = "�����������һЩ���������ϴ��ļ����������£�����һ�μ��ɣ���\n�����ε��������˵��е�\n'����->Internetѡ��->��ȫ->����վ��->�Զ��弶��'\n�ڵ������Զ��弶�𴰿����ҵ� 'ActiveX�ؼ��Ͳ��' ������������ȫ��ѡΪ '����' �󣬵��ȷ����\n��ʱ��Ҫ�رյ�ǰ���ڣ��ٵ�� 'վ��' ��ť���ڵ����Ĵ����н����渴ѡ��� '��' ȥ����Ȼ���� '���' ��ť���رյ�ǰ���ڡ�\n���һ· 'ȷ��' ��ɲ�ˢ�µ�ǰҳ�档";  
  
    this.jcropApi;  
    return this;  
}  
UploadAssist.prototype = {  
    //�����๹����  
    constructor: UploadAssist,  
  
    //����iframe  
    createIframe: function (/*�����ָ����dom����*/elem) {  
  
        var html = "<html>"  
        + "<head>"  
        + "<title>upload</title>"  
        + "<script>"  
        + "function getDCMT(){return window.frames['dynamic_creation_upload_iframe'].document;}"  
        + "</" + "script>"  
        + "</head>"  
        + "<body>"  
        + "<form method='post' target='dynamic_creation_upload_iframe' enctype='multipart/form-data' action='" + this.settings.url + "'>"  
        + "<input type='text' id='x1' name='x1' />"  
        + "<input type='text' id='y1' name='y1' />"  
        + "<input type='text' id='x2' name='x2' />"  
        + "<input type='text' id='y2' name='y2' />"  
        + "<input type='text' id='w'  name='w' />"  
        + "<input type='text' id='h'  name='h' />"  
        + "<input type='text' id='maxW' name='maxW' />"  
        + "<input type='text' id='maxH' name='maxH' />"  
        + "<input type='file' name='fileUpload' />"  
        + "</form>"  
        + "<iframe name='dynamic_creation_upload_iframe'></iframe>"  
        + "</body>"  
        + "</html>";  
  
  
        this.iframe = $("<iframe name='" + this.iframeName + "'></iframe>")[0];  
        this.iframe.style.width = "0px";  
        this.iframe.style.height = "0px";  
        this.iframe.style.border = "0px solid #fff";  
        this.iframe.style.margin = "0px";  
        elem.parentNode.insertBefore(this.iframe, elem);  
        var iframeDocument = this.getIframeContentDocument();  
        iframeDocument.write(html);  
    },  
  
    //��ȡʱ���  
    getTimestamp: function () {  
        return (new Date()).valueOf();  
    },  
    //����ͼƬ���ŵ����߿�  
    setMaxWidthAndHeight: function (/*����*/maxW,/*����*/maxH) {  
        this.getElement("maxW").value = maxW;  
        this.getElement("maxH").value = maxH;  
    },  
    //����ͼƬ��ȡ����  
    setImageCropperObj: function (/*ͼƬ��ȡ����*/obj) {  
        this.getElement("x1").value = obj.x;  
        this.getElement("y1").value = obj.y;  
        this.getElement("x2").value = obj.x2;  
        this.getElement("y2").value = obj.y2;  
        this.getElement("w").value = obj.w;  
        this.getElement("h").value = obj.h;  
    },  
    //��ȡ������iframe�е�Ԫ��  
    getElement: function (id) {  
        var dcmt = this.getIframeContentDocument();  
        return dcmt.getElementById(id);  
    },  
    //��ȡ������iframe�е�document����  
    getIframeContentDocument: function () {  
        return this.iframe.contentDocument || this.iframe.contentWindow.document;  
    },  
  
    //��ȡ������iframe���ڵ�window����  
    getIframeWindow: function () {  
        return this.iframe.contentWindow || this.iframe.contentDocument.parentWindow;  
    },  
  
    //��ȡ������iframe�ڲ�iframe��document����  
    getInsideIframeContentDocument: function () {  
        return this.getIframeWindow().getDCMT();  
    },  
  
    //��ȡ�ϴ�input�ؼ�  
    getUploadInput: function () {  
        var inputs = this.getIframeContentDocument().getElementsByTagName("input");  
        var uploadControl;  
        this.forEach(inputs, function () {  
            if (this.type === "file") {  
                uploadControl = this;  
                return false;  
            }  
        });  
        return uploadControl;  
    },  
  
    //forEach��������  
    forEach: function (/*����*/arr, /*������*/fn) {  
        var len = arr.length;  
        for (var i = 0; i < len; i++) {  
            var tmp = arr[i];  
            if (fn.call(tmp, i, tmp) == false) {  
                break;  
            }  
        }  
    },  
  
    //�ύ�ϴ�  
    submitUpload: function () {  
        if (!this.submitStatus) return;  
  
        this.submitStatus = false;  
  
        this.getIframeContentDocument().forms[0].submit();  
    },  
  
    //����ļ��Ƿ�����ϴ�  
    checkFileIsUpload: function (fileSuf, suffixs) {  
  
        var status = false;  
        this.forEach(suffixs, function (i, n) {  
            if (fileSuf.toLowerCase() === n.toLowerCase()) {  
                status = true;  
                return false;  
            }  
        });  
        return status;  
    },  
  
  
    //ѡ���ϴ��ļ�  
    chooseFile: function () {  
        if (this.settings.perviewImageElementId) {  
            $("#" + this.settings.perviewImageElementId).empty();  
        }  
  
        var uploadfile = this.getUploadInput();  
        $(uploadfile).val("").click();  
    }  
};  
  
//ͼƬԤ������  
var perviewImage = {  
    timers: [],  
    //��ȡԤ��Ԫ��  
    getElementObject: function (elem) {  
        if (elem.nodeType && elem.nodeType === 1) {  
            return elem;  
        } else {  
            return document.getElementById(elem);  
        }  
    },  
    //��ʼͼƬԤ��  
    beginPerview: function (/*�ļ��ϴ��ؼ�ʵ��*/file, /*��Ҫ��ʾ��Ԫ��id��Ԫ��ʵ��*/perviewElemId, /*�ϴ�ҳ�����ڵ�document����*/ dcmt, /*�ϴ�������ʵ��*/upload) {  
        this.imageOperation(file, perviewElemId, dcmt, upload);  
    },  
    //ͼƬԤ������  
    imageOperation: function (/*�ļ��ϴ��ؼ�ʵ��*/file, /*��Ҫ��ʾ��Ԫ��id��Ԫ��ʵ��*/perviewElemId, /*�ϴ�ҳ�����ڵ�document����*/ dcmt, /*�ϴ�������ʵ��*/upload) {  
        for (var t = 0; t < this.timers.length; t++) {  
            window.clearInterval(this.timers[t]);  
        }  
        this.timers.length = 0;  
  
        var tmpParams = {  
            onChange: function (c) {  
                upload.setImageCropperObj(c);  
            },  
            onSelect: function (c) {  
                upload.setImageCropperObj(c);  
            }  
        };  
        var sWidth = 50, sHeight = 50;  
        if (upload.settings.cropperParam.minSize) {  
            var size = upload.settings.cropperParam.minSize;  
            sWidth = size[0] > sWidth ? size[0] : sWidth;  
            sHeight = size[1] > sHeight ? size[1] : sHeight;  
        }  
        var params = $.extend({}, tmpParams, upload.settings.cropperParam || {});  
  
        var preview_div = this.getElementObject(perviewElemId);  
  
        var MAXWIDTH = preview_div.clientWidth;  
        var MAXHEIGHT = preview_div.clientHeight;  
  
        upload.setMaxWidthAndHeight(MAXWIDTH, MAXHEIGHT);  
  
        if (file.files && file.files[0]) { //�˴�ΪFirefox��Chrome�Լ�IE10�Ĳ���  
            preview_div.innerHTML = "";  
            var img = document.createElement("img");  
            preview_div.appendChild(img);  
            img.style.visibility = "hidden";  
            img.onload = function () {  
                var rect = perviewImage.clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);  
                img.style.width = rect.width + 'px';  
                img.style.height = rect.height + 'px';  
                img.style.visibility = "visible";  
                var offsetWidth = (rect.width / 2) - (sWidth / 2);  
                var offsetHeight = (rect.height / 2) - (sHeight / 2);  
                var obj = {  
                    x: offsetWidth,  
                    y: offsetHeight,  
                    x2: offsetWidth + sWidth,  
                    y2: offsetHeight + sHeight,  
                    w: sWidth,  
                    h: sHeight  
                };  
  
                $(img).Jcrop(params, function () {  
                    upload.jcropApi = this;  
  
                    this.animateTo([obj.x, obj.y, obj.x2, obj.y2]);  
                    upload.setImageCropperObj(obj);  
                });  
            };  
  
            var reader = new FileReader();  
            reader.onload = function (evt) {  
                img.src = evt.target.result;  
            };  
            reader.readAsDataURL(file.files[0]);  
        } else { //�˴�ΪIE6��7��8��9�Ĳ���  
            file.select();  
            var src = dcmt.selection.createRange().text;  
  
            var div_sFilter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale',src='" + src + "')";  
            var img_sFilter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='image',src='" + src + "')";  
  
            preview_div.innerHTML = "";  
            var img = document.createElement("div");  
            preview_div.appendChild(img);  
            img.style.filter = img_sFilter;  
            img.style.visibility = "hidden";  
            img.style.width = "100%";  
            img.style.height = "100%";  
  
            function setImageDisplay() {  
                var rect = perviewImage.clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);  
                preview_div.innerHTML = "";  
                var div = document.createElement("div");  
                div.style.width = rect.width + 'px';  
                div.style.height = rect.height + 'px';  
                div.style.filter = div_sFilter;  
                var offsetWidth = (rect.width / 2) - (sWidth / 2);  
                var offsetHeight = (rect.height / 2) - (sHeight / 2);  
                var obj = {  
                    x: offsetWidth,  
                    y: offsetHeight,  
                    x2: offsetWidth + sWidth,  
                    y2: offsetHeight + sHeight,  
                    w: sWidth,  
                    h: sHeight  
                };  
                preview_div.appendChild(div);  
                $(div).Jcrop(params, function () {  
                    upload.jcropApi = this;  
                    this.animateTo([obj.x, obj.y, obj.x2, obj.y2]);  
                    upload.setImageCropperObj(obj);  
                });  
            }  
  
            //ͼƬ���ؼ���  
            var tally = 0;  
  
            var timer = window.setInterval(function () {  
                if (img.offsetHeight != MAXHEIGHT) {  
                    window.clearInterval(timer);  
                    setImageDisplay();  
                } else {  
                    tally++;  
                }  
                //�������������ͼƬ�����ܼ��أ���ֹͣ��ǰ����ѯ  
                if (tally > 20) {  
                    window.clearInterval(timer);  
                    setImageDisplay();  
                }  
            }, 100);  
  
            this.timers.push(timer);  
        }  
    },  
    //����������ͼƬ  
    clacImgZoomParam: function (maxWidth, maxHeight, width, height) {  
        var param = { width: width, height: height };  
        if (width > maxWidth || height > maxHeight) {  
            var rateWidth = width / maxWidth;  
            var rateHeight = height / maxHeight;  
  
            if (rateWidth > rateHeight) {  
                param.width = maxWidth;  
                param.height = Math.round(height / rateWidth);  
            } else {  
                param.width = Math.round(width / rateHeight);  
                param.height = maxHeight;  
            }  
        }  
  
        param.left = Math.round((maxWidth - param.width) / 2);  
        param.top = Math.round((maxHeight - param.height) / 2);  
        return param;  
    },  
    //����ͼƬԤ��Ԫ��  
    createPreviewElement: function (/*�ر�ͼƬ����*/name, /*�ϴ�ʱ���ļ���*/file, /*Ԥ��ʱ����ʽ*/style) {  
        var img = document.createElement("div");  
        img.title = file;  
        img.style.overflow = "hidden";  
        for (var s in style) {  
            img.style[s] = style[s];  
        }  
  
        var text = document.createElement("div");  
        text.style.width = style.width;  
        text.style.overflow = "hidden";  
        text.style.textOverflow = "ellipsis";  
        text.style.whiteSpace = "nowrap";  
        text.innerHTML = file;  
  
        var main = document.createElement("div");  
        main.appendChild(img);  
        main.appendChild(text);  
        return main;  
    },  
  
    //��ȡ�ϴ��ļ���С  
    getFileSize: function (/*�ϴ��ؼ�dom����*/file, /*�ϴ��ؼ����ڵ�document����*/dcmt) {  
        var fileSize;  
        if (file.files && file.files[0]) {  
            fileSize = file.files[0].size;  
        } else {  
            file.select();  
            var src = dcmt.selection.createRange().text;  
            try {  
                var fso = new ActiveXObject("Scripting.FileSystemObject");  
                var fileObj = fso.getFile(src);  
                fileSize = fileObj.size;  
            } catch (e) {  
                return "error";  
            }  
        }  
        fileSize = ((fileSize / 1024) + "").split(".")[0];  
        return fileSize;  
    }  
};  