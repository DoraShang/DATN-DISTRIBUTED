
function resizeAll() {
    if ($(".md-modal").length > 0 && $.browser.msie && $.browser.version < 9) {
        var rs = function() {
            var docW = $(document).width();
            $(".md-modal").each(function () {
                var thisW = $(this).width();
                var left = (docW - thisW) / 2;
                $(this).css("left", left);
            });
        };

        rs();

        $(window).on("resize", rs);
    }
}

$('.dated').datepicker({ format: 'yyyy-mm-dd' });


$(document).ready(function () {

    ////Resize
    resizeAll();
   
    (function loadAdv() {
        console.log('loadAdv');

        if (!(/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()))) {
            // $(".container").append("<div id='adFixLeft'></div>");
            // $(".container").append("<div id='adFixRight'></div>");
            $.ajax({
                url: "/common/adshowleft",
                type: "GET",
                data: { title: document.title },
                success: function (data) {
                    console.log(data);

                    // $("#adFixRight").append(data.file1);
                    // $("#adFixLeft").append(data.file2);

                    var link = window.location.href;
                    link = link.replace("https://luatminhgia.com.vn/", "");
                    link = link.split("/");
                    link = link[0];

                    console.log(link);
                    // console.log(data.file1);
                    // alert(link);
                    var tg = document.title;
                    /*
                    if (
                        link.indexOf("luat-su-dan-su") >= 0 
                        || link.indexOf("luat-su-hinh-su") >= 0 
                        || link.indexOf("luat-su-dat-dai") >= 0 
                        || link.indexOf("luat-su-hon-nhan") >= 0 
                        || link.indexOf("luat-su-lao-dong") >= 0 
                        || link.indexOf("luat-su-doanh-nghiep") >= 0 
                        || link.indexOf("nghi-quyet") >= 0 
                        || link.indexOf("quyet-dinh") >= 0 
                    ) 
                    {
                        // $("#adFixRight").append("<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script><!-- LMG-2BenWeb --><ins class=\"adsbygoogle\" style=\"display:inline-block;width:160px;height:600px\" data-ad-client=\"ca-pub-5654082264670297\" data-ad-slot=\"2916036768\"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>");
                        // $("#adFixLeft").append("<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script><!-- LMG-2BenWeb --><ins class=\"adsbygoogle\" style=\"display:inline-block;width:160px;height:600px\" data-ad-client=\"ca-pub-5654082264670297\" data-ad-slot=\"2916036768\"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>");
                     }
                    else 
                    {*/
                        $("#adFixRight").append(data.file1);
                        $("#adFixLeft").append(data.file2);
                    /*}*/
                }
            });
        }
    })();


    //Scroll to top
    $(window).bind("scroll", function() {
        if ($(this).scrollTop() > 520) {
            $(".btnScrollTop").fadeIn();
        } else {
            $(".btnScrollTop").stop().fadeOut();
        }
    });
    $(".btnScrollTop").on("click", function(evt) {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });

    ($("#frmSearch").length > 0 && $("#frmSearch").on("submit", function (e) {
        e.preventDefault();
        var searchVal = $(this).find(".text").val();
        if (searchVal.length > 0) {
            location.href = "/search?q=" + encodeURI(searchVal);
        }
    }));

    if($(".searchboxWrap").length > 0) {
        $(".searchboxWrap").on("submit", function(e) {
            e.preventDefault();
            var key = $(this).parent().find(".inputSearch").val();
            if (key.length > 3) {
                location.href = "/search?q=" + encodeURIComponent(key);
            }
        });
        $(".buttonSearch").on("click", function (e) {
            e.preventDefault();
            var key = $(this).parent().find(".inputSearch").val();
            if (key.length > 3) {
                location.href = "/search?q=" + encodeURIComponent(key);
            }
        });
    }

    ($("#formErrorSearch").length > 0 && $("#formErrorSearch").on("submit", function(e) {
        e.preventDefault();
        var key = $(this).find(".loi-timkiem-text").val();
        location.href = "/search?q=" + encodeURI(key);
    }));


    for (var f = document.forms, i = f.length; i--;) f[i].setAttribute("novalidate", "");

    $("#frmContact").validate({
        lang: '@CultureInfo.CurrentCulture.TwoLetterISOLanguageName',
        rules: {
            recaptcha_response_field: {
                required: true
            }
        },
        errorElement: "label",
        errorClass: "help-block error",
        errorPlacement: function(e, t) {
            t.parents(".input").append(e);
        }
    });


    $("#btnAsknow").on("click", function () {
        var button = $(this);
        var ctx = $("#formAsk").serialize();
        $("#formAsk").validate({
            errorElement: "span",
            errorPlacement: function(e, t) {
                t.parents("div.span9").append(e);
            },
            submitHandler: function(form) {
                $.ajax({
                    url: "/home/SaveContact",
                    type: "POST",
                    beforeSend: function() {
                        button.prepend($("<img src='/Content/bootstrap/plugins/dynatree/loading.gif' style='padding-right: 4px' />"));
                        button.attr("disabled", "disabled");
                    },
                    data: ctx,
                    success: function(data) {
                        if (data) {
                            button.removeAttr("disabled");
                            button.find("img").remove();
                            alert("Hệ thống đã lưu câu hỏi, chúng tôi sẽ trả lời sớm cho bạn qua hệ thống email. Xin cảm ơn!");
                            $(form).get(0).reset();
                            $(form).find(".swfupload-control > ul").html("");
                            $("#makeQuestion").modal("hide");
                        } else {
                            alert("Co loi xay ra, vui long thu lai sau: " + data.error);
                        }
                    },
                    error: function () {
                        button.find("img").remove();
                        alert("Co loi xay ra, vui long thu lai sau");
                    }
                });
            }
        });

        $("#formAsk").submit();
    });
    $('.swfupload-control').each(function() {
        $(this).swfupload({
            upload_url: "/article/AddAttachments",
            file_size_limit: "10240",
            file_types: "*.doc;*.docx;*.xls;*.xlsx;*.pdf;*.zip;*.rar;*.png;*.jpg;*.jpeg;*.txt",
            file_types_description: "All Files",
            file_upload_limit: "0",
            flash_url: "/Scripts/swfupload/swfupload.swf",
            button_image_url: '/Scripts/swfupload/XPButtonUploadText_61x22.png',
            button_width: 61,
            button_height: 22,
            button_placeholder: $('.button', this)[0],
            debug: false
        });
    });

    // bind the swfupload event handlers like an other jquery event
    $('.swfupload-control').bind('fileQueued', function(event, file) {
        $(this).swfupload('startUpload');
    }).bind("uploadError", function(event, file, errorCode, message) {
        console.log(message);
    }).bind("uploadSuccess", function(event, file, serverData, responseReceived) {
        var json = JSON.parse(serverData);

        var ul = $(this).find("ul").html("");
        $.each(json.files, function(index, value) {
            ul.append("<li>" + value + " <i class='icon-remove'></i></li>");
        });

        ul.find("i").bind("click", function() {
            var elm = $(this).parent();

            $.ajax({
                type: "POST",
                url: "/article/removeAttachment",
                data: { filename: elm.text() },
                success: function(d) {
                    if (d == 1) {
                        elm.fadeOut("slow", function() { elm.remove(); });
                    }
                },
                error: function() {
                    alert("Xảy ra lỗi, vui lòng thử lại sau.");
                }
            });
        });
    }).bind("fileQueueError", function(file, errorCode, message) {
        if (errorCode.filestatus == -3) {
            alert("Không chấp nhận file " + errorCode.name + " do không đúng định dạng (doc, excel, rar, zip, pdf)");
        } else if (errorCode.filestatus == -6) {
            alert("Không chấp nhận file " + errorCode.name + " do kích thước quá lớn (dưới 10Mb)");
        } else {
            alert("Lỗi " + message);
        }
    });

    if ($(".timeToExit").length > 0) {
        setInterval(function() {
            var num = $(".timeToExit").text();
            if (num == 0) {
                location.href = "/";
                return;
            }
            $(".timeToExit").text(num - 1);
        }, 1000);

    }

    $(".timeintext").length>0 && $(".timeintext").timeago();
});

$("body").on({
    "mouseenter": function () {
        $(this).closest("#adFixLeft, #adFixRight").addClass("AdBackground");
    },
    "mouseleave": function() {
        $(this).closest("#adFixLeft, #adFixRight").removeClass("AdBackground");
    }
}, ".adButton");



var showPop = function(element, title, message) {
    var str = "<div class='md-modal md-effect-1' id='" + element + "'>" +
        "<div class='md-content'>" +
            "<h3>" + title +"<i class='btnAskClose md-close'></i>" +"</h3>" +
            "<div>" + (message!=null ? message: title) +"</div>" +
        "</div>";
    $("body").append(str);

    ModalEffects();

    classie.add($("#"+element).get(0), 'md-show');
};

var closePop = function() {
    classie.remove($("#modal-2").get(0), 'md-show');
};

function popitup(url, windowName) {
    var newwindow = window.open(url, windowName, 'height=500,width=990');
    if (window.focus) {
        newwindow.focus();}
    return false;
}