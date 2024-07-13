Account = {};
Account.Delete = function (id) {
    var obj = {
        confirm: "Bạn muốn xoá user này?",
        url: "/admin/account/delete",
        data: { userid: id }
    };
    removeRecord(obj, function () {
        location.href = window.location.href;
    });
};

Support = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xoá hỗ trợ này?",
            url: "/admin/support/delete",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};

SiteLink = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xoá liên kết này?",
            url: "/admin/sitelink/delete",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};

Advertise = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xoá quảng cáo này?",
            url: "/admin/advertise/delete",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};

Module = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xoá module này?",
            url: "/admin/module/delete",
            data: { id: id }
        };
        removeRecord(obj, function () {
            $.growlUI("Xoá thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    }
};

Category = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xoá danh mục này?",
            url: "/admin/category/delete",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};

Block = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn khối này?",
            url: "/admin/block/delete",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};

BlockArticle = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa nội dung này?",
            url: "/admin/block/removeblockcontent",
            data: { blockid: blockid, articleid: id },
        };
        removeRecord(obj, function () {
            window.location.reload();
        });
    }
};

Article = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa bài viết này?",
            url: "/admin/article/DeleteArticle",
            data: { articleid: id },
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};
//QuickResponse = {
//    Delete: function (id) {
//        var obj = {
//            confirm: "Bạn muốn xóa nội dung này?",
//            url: "/admin/article/DeleteQuickResponse",
//            data: { id: id },
//        };
//        removeRecord(obj, function () {
//            location.href = window.location.href;
//        });
//    },
//    Edit: function (id) {
//        $.ajax({
//            type: "Get",
//            url: "/admin/article/AddResponse",
//            data: { id: id },
//            beforeSend: function () {
//                $("body").modalmanager('loading');
//            },
//            success: function (data) {
//                $("body").data("modalmanager").removeLoading();
//                $("#modal-quickresponse").html(data).modal("show");
//            },
//            error: function () {
//                $("body").data("modalmanager").removeLoading();
//                alert("Error");
//            }
//        });
//    },
//    Add: function (id) {
//        $.ajax({
//            type: "Get",
//            url: "/admin/article/AddResponse",
//            beforeSend: function () {
//                $("body").modalmanager('loading');
//            },
//            success: function (data) {
//                $("body").data("modalmanager").removeLoading();
//                $("#modal-quickresponse").html(data).modal("show");
//            },
//            error: function () {
//                $("body").data("modalmanager").removeLoading();
//                alert("Error");
//            }
//        });
//    },
//    Save: function () {
//        var frm = $("#frmQuickResponse");
//        var data = {};
//        data.QuickId = frm.find("*[name='QuickId']").val();
//        data.Title = frm.find("*[name='Title']").val();
//        data.body = CKEDITOR.instances["Body"].getData();

//        $.ajax({
//            type: "post",
//            url: "/admin/article/SaveQuickResponse",
//            data: data,
//            beforeSend: function () {
//                $("body").modalmanager('loading');
//            },
//            success: function () {
//                $("body").data("modalmanager").removeLoading();
//                $("#modal-quickresponse").modal("hide");
//                $.growlUI("Lưu thành công");
//                location.reload();
//            },
//            error: function () {
//                $("body").data("modalmanager").removeLoading();
//                alert("Error");
//            }
//        });
//    }
//};

Service = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa nội dung khỏi khối dịch vụ này?",
            url: "/admin/article/RemoveServiceContent",
            data: { categoryid: categogyid, articleid: id },
        };
        removeRecord(obj, function () {
            $.growlUI("Xóa thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    }
};

TopArticleCategory = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa nội dung khỏi danh sách này?",
            url: "/admin/category/DeleteTopArticleCategory",
            data: { topid: id },
        };
        removeRecord(obj, function () {
            $.growlUI("Xóa thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    }
};

Order = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa bản ghi này?",
            url: "/admin/Ecommerce/delete",
            data: { id: id },
        };
        removeRecord(obj, function () {
            $.growlUI("Xóa thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    },
    Show: function (id) {
        $.ajax({
            type: "GET",
            url: "/admin/ecommerce/load",
            data: { id: id },
            dataType: "json",
            success: function (data) {
                var modal = $("#modal-2");
                var decoded = $("<div />").html(data.model.Content).text();
                modal.find(".modal-header > h3").text("(Mã " + data.model.OrderID + ") Khách hàng " + data.model.CustomerName);
                modal.find(".modal-body").html(
                   "Email: " + " - " + data.model.Email + "<br />" +
                   "SDT: " + " - " + data.model.Phone + "<br />" +
                   "Nội dung: <br /><strong>" +
                    decoded + "</strong><br />" +
                   "Thanh toán qua: " + data.model.Bank + " <br />" +
                   "Trạng thái giao dịch: " + (data.model.Status == 2 ? "Đã thanh toán" : "Chưa thanh toán") + " <br />"
                );
                //var modal_id = $(this).attr('data-target');
                //$.get(url, function (data) {
                modal.modal();
                //});
            },
            error: function () {
                $.growlUI("Lỗi", "Vui lòng báo lại quản trị");
            }
        });
    }
};

ListFixAd = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa quảng cáo này?",
            url: "/admin/Advertise/ListFixAd",
            data: { id: id, act: "del" },
        };
        removeRecord(obj, function () {
            $.growlUI("Xóa thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    }
};

RelateArticle = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa nội dung liên quan này?",
            url: "/admin/article/RemoveRelate",
            data: { articlerelateid: id, articleid: articleid },
        };
        removeRecord(obj, function () {
            $.growlUI("Xóa thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    }
};

AskModerate = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xóa câu hỏi này?",
            url: "/admin/ask/removequest",
            data: { questid: id },
        };
        removeRecord(obj, function () {
            $.growlUI("Xóa thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    },
    DeclineAnswer: function (id) {
        var obj = {
            confirm: "Bạn muốn hủy câu trả lời này?",
            url: "/admin/ask/removeresponse",
            data: { questid: id },
        };
        removeRecord(obj, function () {
            $.growlUI("Hủy thành công");

            setTimeout(function () {
                $.unblockUI({
                    onUnblock: function () {
                        location.reload();
                    }
                });
            }, 1000);
        });
    }
};



function removeRecord(obj, successCallback) {
    bootbox.confirm(obj.confirm, function (res) {
        if (res == true) {
            $.ajax(
            {
                url: obj.url,
                data: obj.data,
                type: "POST",
                success: function (success) {
                    if (success) {
                        if (success.error) {
                            alert(success.error);
                        }
                        successCallback();
                    }
                }
            });
        }
    });
}

$(document).ready(function () {
    $(".dataTable th > span").on("click", function () {
        var str = "/admin/article/index/";


        if (parseInt($("#hdCategory").val()) > 0) {
            str += $("#hdCategory").val() + "/";
        }

        var page = $(".pagination .active span").text().replace(/^.*\D+/g, '');
        if (page != "") {
            str += "?page=" + page;
        } else {
            str += "?page=1";
        }

        if ($("#hdSearch").val() != "") {
            str += "&search=" + $("#hdSearch").val();
        }

        str += "&col=" + $(this).data("name");

        if ($(this).data("name") === $("#hdColumnSort").val()) {
            ($("#hdSort").val() === "desc") ? $("#hdSort").val("asc") : $("#hdSort").val("desc");
        }

        if ($("#dlUsers").val() !== "") {
            str += "&userid=" + $("#dlUsers").val();
        }

        str += "&dir=" + $("#hdSort").val();

        if ($("#byuser").is(":checked")) {
            str += "&byuser=true";
        }
        location.href = str;

    });

    $("#byuser").on("change", function () {
        var str = "/admin/article/index/";
        if ($(this).is(":checked")) {
            str += "?byuser=true";
            location.href = str;
            return;
        }
        location.href = str;
    });

    $(".controls .referLength").each(function (index, val) {
        if ($(this).parent().find(".notify").length == 0) {
            $(this).parent().append("<strong style=\"color: red\" class=\"notify\"></strong>");
        }
        // $(this).parent().find(".notify").html("Số ký tự: " + $(this).data("length") + "/" + $(this).val().length);

        $(this).on("focus keyup copy paste", function (e) {


            var fixlength = $(this).data("length");
            var t = $(this).val().length;

            //if ($(this).val().length >= fixlength) {
            //    $(this).val($(this).val().substring(0, fixlength));

            //    return false;
            //}
            $(this).parent().find(".notify").html("Số ký tự: " + t + "/" + fixlength);
        });
    });

    //CACHING
    if ($("#cacheRemover").length > 0) {
        $("#cacheRemover a").on("click", function () {
            var location = $(this).data("file");
            $.ajax({
                type: "POST",
                url: "/admin/system/cacheremove",
                beforeSend: function () {
                    $.blockUI({ message: '<p>Đang refresh....</p>', css: { height: '20px' } });
                },
                data: { type: location },
                success: function (data) {
                    $.unblockUI();
                    if (data && data.success == true) {
                        $.growlUI('', 'Refresh thành công');
                    } else {
                        $.growlUI('', 'Lỗi: ' + data.msg);
                    }
                },
                error: function () {
                    $.unblockUI();
                    $.growlUI('Lỗi!', res.responseText);
                }
            });
        });
    }
});


//BLOCKS
// requires some local variables
function blockSort(elm, url, blockid) {
    var me = $(elm);
    if (!isSorting) {
        $(elm).find("i").removeClass("icon-sort").addClass("icon-save");
        $(".sort-text").hide();
        $(".sort-input").show();

        isSorting = true;
    } else {
        var requestJson = {};
        var items = [];
        $(".sort-input > input[type=number]").each(function () {
            items.push(
            {
                Sort: parseInt($(this).val()),
                ArticleID: $(this).data("id"),
                BlockID: blockid,
                CategoryID: categogyid
            });
        });
        requestJson.items = JSON.stringify(items);

        $.ajax({
            type: "POST",
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lưu...</p>', css: { height: '20px' } });
            },
            url: url,
            data: requestJson,
            success: function () {
                me.find("i").removeClass("icon-save").addClass("icon-sort");

                $(".sort-input > input[type=number]").each(function (index, value) {
                    $(this).parent().parent().find(".sort-text").text($(this).val());
                });

                $(".sort-text").show();
                $(".sort-input").hide();

                $.unblockUI();

                $.growlUI('Lưu vị trí thành công!');

                setTimeout(function () {
                    $.unblockUI({
                        onUnblock: function () {
                            location.reload();
                        }
                    });
                }, 1000);

                isSorting = false;
            },
            error: function () {
                $.unblockUI();
                $.growlUI('Lỗi!', "Vui lòng thông báo quản trị.");
            }
        });
    }
}


($(".addblockcontent").length > 0 && $(".addblockcontent").each(function () {
    $(this).on("click", function () {
        if (articleid == 0) {
            $.growlUI("Bạn nên chọn bài viết trước");
        } else {
            $.ajax({
                type: "POST",
                url: "/admin/block/addblockcontent",
                data: { id: articleid, blockid: blockid },
                success: function (json) {
                    if (json.success) {
                        $.growlUI("Thêm nội dung thành công");

                        setTimeout(function () {
                            $.unblockUI({
                                onUnblock: function () {
                                    location.reload();
                                }
                            });
                        }, 1000);
                    } else {
                        $.growlUI(json.message);
                    }
                },
                error: function (xhr, error) {
                    alert(xhr);
                }
            });
        }
    });
}));

//SERVICES
($(".addservicecontent").length > 0 && $(".addservicecontent").each(function () {
    $(this).on("click", function () {
        if (articleid == 0) {
            $.growlUI("Bạn nên chọn bài viết trước");
        } else {
            $.ajax({
                type: "POST",
                url: "/admin/article/AddServiceContent",
                data: { articleid: articleid, categoryid: categogyid },
                success: function (json) {
                    if (json.success) {
                        $.growlUI("", "Thêm nội dung thành công");

                        setTimeout(function () {
                            $.unblockUI({
                                onUnblock: function () {
                                    location.reload();
                                }
                            });
                        }, 1000);
                    } else {
                        $.growlUI(json.message);
                    }
                },
                error: function (xhr, error) {
                    $.growlUI("Lỗi", xhr);
                }
            });
        }
    });
}));


//RELATES
($(".addrelatecontent").length > 0 && $(".addrelatecontent").each(function () {
    $(this).on("click", function () {
        if (articlerelateid == 0) {
            alert("Bạn nên chọn bài viết trước");
        } else {
            $.ajax({
                type: "POST",
                url: "/admin/article/AddRelate",
                data: { articleid: articleid, articlerelateid: articlerelateid },
                success: function (json) {
                    if (json.success) {
                        $.growlUI("Thêm thành công");

                        setTimeout(function () {
                            $.unblockUI({
                                onUnblock: function () {
                                    location.reload();
                                }
                            });
                        }, 1000);
                    } else {
                        $.growlUI("error", json.message);
                    }
                },
                error: function (xhr, error) {
                    $.growlUI("error", xhr);
                }
            });
        }
    });

}));


//TOP ARTICLES CATEGORY PAGE
($(".addtopnewCategory").length > 0 && $(".addtopnewCategory").each(function () {
    $(this).on("click", function () {
        if (articleid == 0) {
            alert("Bạn nên chọn bài viết trước");
        } else {
            $.ajax({
                type: "POST",
                url: "/admin/category/SaveTopArticleCategory",
                data: { articleid: articleid, categoryid: categoryid },
                success: function (json) {
                    if (json.success) {
                        $.growlUI("Thêm thành công");

                        setTimeout(function () {
                            $.unblockUI({
                                onUnblock: function () {
                                    location.reload();
                                }
                            });
                        }, 1000);
                    } else {
                        $.growlUI("Lỗi", "Liên kết này hiện đã có trong danh sách");
                    }
                },
                error: function (xhr, error) {
                    $.growlUI("error", xhr);
                }
            });
        }
    });
}));

if ($("#btnPreview").length > 0) {


    //on `key` event
    // editor.on('key', function () {

    //var data = editor.getData(); //reference to ckeditor data
    // $('#preview').html(data); //update `div` html

    // });
    $("#btnPreview").on("click", function () {
        //CKEDITOR.replace('textarea'); //new ckeditor instance
        var editor = CKEDITOR.instances.Content; //reference to instance
        var dataEditor = editor.getData();

        $("#Content").text(dataEditor);

        var data = $("#bb").serialize();

        $.ajax({
            url: "/article/previewarticlemode",
            data: data,
            type: "POST",
            success: function (dat) {
                var generator = window.open('', '_blank', 'location=no,width=1000,height=768,scrollbars=yes');
                generator.document.write(dat);
                generator.document.close();
            },
            error: function () {
                alert("Error");
            }
        });
    });
}

$("#bb").validate({
    ignore: "",
    invalidHandler: function (e, validator) {
        if (validator.errorList.length) {
            alert("Có " + validator.errorList.length + " trường cần bạn nhập. Vui lòng nhập đủ sau đó nhấn gửi");
        }
    }
});

$(".byuserid").length > 0 && $(".byuserid").on("change", function () {
    var id = $(this).val();
    location.href = "/admin/Article/Index?userid=" + id;
});

function ckCreate(name) {
    if (CKEDITOR.instances[name]) {

        var instance = CKEDITOR.instances[name];
        if (instance.element.$ != null) {
          //  try {
                instance.destroy(true);
            //}catch (e){}
        }

        $('#' + name).attr('contenteditable', true);
        CKEDITOR.inline(name);
    }
}

AskModerate = {};
AskModerate.sendNotifyToUser = function (userid) {
    var reason = CKEDITOR.instances['ckThongBao'].getData();
    var subject = $("#thongbaoName").val();

    $.ajax({
        type: "POST",
        url: "/admin/SaveNotification",
        data: { title: subject, content: reason, userid: userid },
        beforeSend: function () {
            $("body").modalmanager('loading');
        },
        success: function () {
            $("body").data("modalmanager").removeLoading();

            CKEDITOR.instances['ckThongBao'].setData('');
            $("#thongbaoName").val('');
            $("#modal-notification").modal('hide');

            alert("Đã gửi");
        },
        error: function () {
            $("body").data("modalmanager").removeLoading();
            alert("Đã có lỗi xảy ra. Vui lòng liên hệ Tuấn Kỹ thuật  để được giúp đỡ.");
        }
    });
}

$('#modal-notification').on('shown.bs.modal', function () {
    ckCreate('ckThongBao');

    //load ajax users
    $("#guiCho").select2({
        minimumInputLength: 3,
        placeholder: "Tìm người cần gán...",
        initSelection: function (element, callback) {
            callback({ id: 0, text: 'Tất cả' });
        },
        ajax: {
            url: "/admin/searchusers",
            delay: 1000,
            data: function (params) {
                return {
                    name: params
                };
            },
            results: function (data, page) {
                var newData = [];
                $.each(data, function (item, value) {
                    newData.push({
                        id: value.UserID,
                        text: value.FullName //string to be displayed
                    });
                });
                return { results: newData };
            }
        }
    }).select2('val', []);
});

var sendNotification = function (element) {
    var modal = $(element).closest(".modal");
    var form = modal.find("form");
    var title = form.find("[name='title']").val();
    var content = CKEDITOR.instances['ckThongBao'].getData();
    var userid = form.find("*[name='userid']").val();

    if (title.length === 0 || content.length === 0) {
        alert("Bạn cần nhập đủ tiêu đề, nội dung...");
        return;
    }

    $.ajax({
        type: "POST",
        url: "/admin/SaveNotification",
        data: { title: title, content: content, userid: userid },
        beforeSend: function () {
            $("body").modalmanager('loading');
        },
        success: function () {
            form.get(0).reset();
            CKEDITOR.instances['ckThongBao'].setData('');
            $("body").data("modalmanager").removeLoading();
            modal.modal("hide");
            alert("Đã lưu");
            location.reload();
        },
        error: function () {
            $("body").data("modalmanager").removeLoading();
            alert("Có lỗi xảy ra. Vui lòng liên hệ với bộ phận kỹ thuật để xử lý.");
        }
    });
}

$('.swfupload-control').each(function () {
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
$('.swfupload-control').bind('fileQueued', function (event, file) {
    $(this).swfupload('startUpload');
}).bind("uploadError", function (event, file, errorCode, message) {
    console.log(message);
}).bind("uploadSuccess", function (event, file, serverData, responseReceived) {
    var json = JSON.parse(serverData);

    var ul = $(this).find("ul").html("");
    $.each(json.files, function (index, value) {
        ul.append("<li>" + value + " <i class='icon-remove'></i></li>");
    });

    ul.find("i").bind("click", function () {
        var elm = $(this).parent();

        $.ajax({
            type: "POST",
            url: "/article/removeAttachment",
            data: { filename: elm.text() },
            success: function (d) {
                if (d == 1) {
                    elm.fadeOut("slow", function () { elm.remove(); });
                }
            },
            error: function () {
                alert("Xảy ra lỗi, vui lòng thử lại sau.");
            }
        });
    });
}).bind("fileQueueError", function (file, errorCode, message) {
    if (errorCode.filestatus == -3) {
        alert("Không chấp nhận file " + errorCode.name + " do không đúng định dạng (doc, excel, rar, zip, pdf)");
    } else if (errorCode.filestatus == -6) {
        alert("Không chấp nhận file " + errorCode.name + " do kích thước quá lớn (dưới 10Mb)");
    } else {
        alert("Lỗi " + message);
    }
});

var deleteNotification = function (id, element) {
    bootbox.confirm('Chắc chắn xóa?', function (success) {
        if (success) {
            $.ajax({
                type: "POST",
                url: "/admin/DeleteNotify",
                data: { notifyId: id },
                beforeSend: function () {
                    $("body").modalmanager('loading');
                },
                success: function () {
                    $("body").data("modalmanager").removeLoading();
                    location.reload();
                },
                error: function () {
                    $("body").data("modalmanager").removeLoading();
                    alert("Có lỗi xảy ra. Vui lòng liên hệ với bộ phận kỹ thuật để xử lý.");
                }
            });
        }
    });
}

var showDetailNotification = function (id) {
    $.ajax({
        type: "GET",
        url: "/admin/readnotify?id=" + id,
        success: function (data) {
            $("#modal-adminview-detailnotifty").find(".modal-body").html(data);
            $("#modal-adminview-detailnotifty").modal("show");
        },
        error: function () {
            alert("Lỗi. Vui lòng liên hệ bộ phận kỹ thuật...");
        }
    });
};


var MailRepo = {
    getMailingList: function (element, page) {
        $.ajax({
            url: "/admin/qa/GetMailList",
            type: "get",
            data: { page: parseInt(page) },
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lấy dữ liệu từ mail....</p>', css: { height: '20px' } });
            },
            success: function (data) {
                $(element).html(data);
                $(".hdPage").val(page);
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
                $(element).html("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    init: function () {
        $(document).on("click", "#mailingList .pager1 a", function (evt) {
            evt.preventDefault();
            var k = $(this).attr("href").match(/\d+/);
            MailRepo.getMailingList("#mailingList", k);
        });
    },
    Get: function (id) {
        $.ajax({
            url: "/admin/qa/FetchEmail",
            type: "get",
            data: { id: id },
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lấy mail....</p>', css: { height: '20px' } });
            },
            success: function (data) {
                $("#modal-mail").html(data);
                $.unblockUI();
                $("#modal-mail").modal("show");
            },
            error: function () {
                $.unblockUI();
                $(element).html("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    Delete: function (id, elment) {
        var obj = {
            confirm: "Bạn muốn xóa email này?",
            url: "/admin/qa/deleteEmail",
            data: { id: id }
        };
        removeRecord(obj, function () {
            var page = $(".hdPage").val();
            $("#modal-mail").modal("hide");
            MailRepo.getMailingList("#mailingList", page);
        });
    },
    sendCrm: function (id) {
        $.ajax({
            url: "/admin/qa/SendCrm",
            type: "get",
            data: { id: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $("#modal-crm").html(data);
                $("body").data("modalmanager").removeLoading();
                $("#modal-crm").modal("show");

            },
            error: function () {
                $.unblockUI();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    closeCrmModal: function () {
        $("#modal-crm").modal("close");
        //$("#modal-mail").modal("show");
    },
    submitCrm: function () {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        };

        var frm = $(".frmSendCrm").serialize();

        $.ajax({
            url: "/admin/qa/SaveCrm",
            type: "post",
            data: frm,
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lưu thông tin....</p>', css: { height: '20px' } });
            },
            success: function (data) {
                $.unblockUI();

                $("#modal-crm").modal("hide");
                $("#modal-mail").modal("hide");

                $.growlUI("Lưu khách hàng vào CRM thành công", '', 800, function () {
                    location.reload();
                });
            },
            error: function () {
                $.unblockUI();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    forwardEmail: function (id) {
        $("#modal-forward").data("mailid", id);
        $("#modal-forward").modal("show");
        $("#forwardTo").focus();
    },
    forwardEmailSubmit: function () {
        var maiid = $("#modal-forward").data("mailid");
        var to = $("#modal-forward #forwardTo").val();
        var notes = $("#modal-forward").find("textarea").val();

        //var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        //if (to.length === 0 || !re.test(to)) {
        //    alert("Bạn cần nhập địa chỉ người nhận");
        //    $("#modal-forward #forwardTo").focus().addClass("error");
        //    return;
        //}

        if ($(".frmForwardEmail").valid()) {
            $.ajax({
                type: "POST",
                url: "/admin/qa/forwardemail",
                beforeSend: function () {
                    $("body").modalmanager('loading');
                },
                data: { mailid: maiid, to: to, notes: notes },
                success: function (data) {
                    $("body").data("modalmanager").removeLoading();
                    $("#modal-forward").modal("hide");
                    $("#modal-forward .frmSendCrm").get(0).reset();
                    alert("Đã gửi email.");
                },
                error: function () {
                    $("body").data("modalmanager").removeLoading();
                    alert("Lỗi: ");
                }
            });
        }
    },
    pushToRepository: function (id, title, mailid) {
        $.ajax({
            url: "/admin/qa/repository",
            type: "get",
            data: { id: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $("#modal-pushtoRepo").html(data).modal("show");
                $("body").data("modalmanager").removeLoading();
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    }
};


var MailRepo = {
    getMailingList: function (element, page) {
        $.ajax({
            url: "/admin/qa/GetMailList",
            type: "get",
            data: { page: parseInt(page) },
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lấy dữ liệu từ mail....</p>', css: { height: '20px' } });
            },
            success: function (data) {
                $(element).html(data);
                $(".hdPage").val(page);
                $.unblockUI();
            },
            error: function () {
                $.unblockUI();
                $(element).html("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    init: function () {
        $(document).on("click", "#mailingList .pager1 a", function (evt) {
            evt.preventDefault();
            var k = $(this).attr("href").match(/\d+/);
            MailRepo.getMailingList("#mailingList", k);
        });
    },
    Get: function (id) {
        $.ajax({
            url: "/admin/qa/FetchEmail",
            type: "get",
            data: { id: id },
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lấy mail....</p>', css: { height: '20px' } });
            },
            success: function (data) {
                $("#modal-mail").html(data);
                $.unblockUI();
                $("#modal-mail").modal("show");
            },
            error: function () {
                $.unblockUI();
                $(element).html("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    Delete: function (id, elment) {
        var obj = {
            confirm: "Bạn muốn xóa email này?",
            url: "/admin/qa/deleteEmail",
            data: { id: id }
        };
        removeRecord(obj, function () {
            var page = $(".hdPage").val();
            $("#modal-mail").modal("hide");
            MailRepo.getMailingList("#mailingList", page);
        });
    },
    sendCrm: function (id) {
        $.ajax({
            url: "/admin/qa/SendCrm",
            type: "get",
            data: { id: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $("#modal-crm").html(data);
                $("body").data("modalmanager").removeLoading();
                $("#modal-crm").modal("show");

            },
            error: function () {
                $.unblockUI();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    closeCrmModal: function () {
        $("#modal-crm").modal("close");
        //$("#modal-mail").modal("show");
    },
    submitCrm: function () {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        };

        var frm = $(".frmSendCrm").serialize();

        $.ajax({
            url: "/admin/qa/SaveCrm",
            type: "post",
            data: frm,
            beforeSend: function () {
                $.blockUI({ message: '<p>Đang lưu thông tin....</p>', css: { height: '20px' } });
            },
            success: function (data) {
                $.unblockUI();

                $("#modal-crm").modal("hide");
                $("#modal-mail").modal("hide");

                $.growlUI("Lưu khách hàng vào CRM thành công", '', 800, function () {
                    location.reload();
                });
            },
            error: function () {
                $.unblockUI();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    forwardEmail: function (id) {
        $("#modal-forward").data("mailid", id);
        $("#modal-forward").modal("show");
        $("#forwardTo").focus();
    },
    forwardEmailSubmit: function () {
        var maiid = $("#modal-forward").data("mailid");
        var to = $("#modal-forward #forwardTo").val();
        var notes = $("#modal-forward").find("textarea").val();

        //var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        //if (to.length === 0 || !re.test(to)) {
        //    alert("Bạn cần nhập địa chỉ người nhận");
        //    $("#modal-forward #forwardTo").focus().addClass("error");
        //    return;
        //}

        if ($(".frmForwardEmail").valid()) {
            $.ajax({
                type: "POST",
                url: "/admin/qa/forwardemail",
                beforeSend: function () {
                    $("body").modalmanager('loading');
                },
                data: { mailid: maiid, to: to, notes: notes },
                success: function (data) {
                    $("body").data("modalmanager").removeLoading();
                    $("#modal-forward").modal("hide");
                    $("#modal-forward .frmSendCrm").get(0).reset();
                    alert("Đã gửi email.");
                },
                error: function () {
                    $("body").data("modalmanager").removeLoading();
                    alert("Lỗi: ");
                }
            });
        }
    },
    pushToRepository: function (id, title, mailid) {
        $.ajax({
            url: "/admin/qa/repository",
            type: "get",
            data: { id: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $("#modal-pushtoRepo").html(data).modal("show");
                $("body").data("modalmanager").removeLoading();
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    }
};

var QaRepo = {
    showModerate: function (id) {
        $.ajax({
            url: "/admin/qa/QuestModerateViewDetail",
            type: "get",
            data: { qid: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $(".moderate-modal").html(data).modal("show");
                $("body").data("modalmanager").removeLoading();
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    moderateNow: function (id, element) {
        $.ajax({
            url: "/admin/qa/ModerateQuestById",
            type: "get",
            data: { qid: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $("body").data("modalmanager").removeLoading();

                if (element != null) {
                    $(element).closest(".box").fadeOut().remove();
                    $.growlUI("Thông báo", "Đã duyệt câu hỏi");
                }
                else {

                    $(".moderate-modal").data("modal").hide();
                    $(".tblModerate").find("tr.data-" + id).fadeOut('slow');

                    $.growlUI("Thông báo", "Đã duyệt câu hỏi");

                    location.reload();
                }
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    cancel: function (id) {
        bootbox.confirm('Chắc chắn hủy?', function (confirm) {
            if (confirm) {
                $.ajax({
                    url: "/admin/qa/DeleteAQuest",
                    type: "post",
                    data: { qid: id },
                    beforeSend: function () {
                        $("body").modalmanager('loading');
                    },
                    success: function (success) {

                        $("body").data("modalmanager").removeLoading();
                        if ($(".moderate-modal").length > 0) {
                            $(".moderate-modal").modal("hide");
                            $(".tblModerate").find("tr.data-" + id).fadeOut('slow');
                        }
                        location.reload();
                    },
                    error: function () {
                        $("body").data("modalmanager").removeLoading();
                        alert("Lỗi, xin vui lòng thử lại");
                    }
                });
            }
        });
    },
    sendEmail: function (id) {
        $(".moderate-forward").modal("show");
        $(".moderate-forward #qid").val(id);
    },
    forwardEmail: function () {
        var maiid = $(".moderate-forward #qid").val();
        var to = $(".moderate-forward #forwardTo").val();
        var notes = $(".moderate-forward").find("textarea").val();

        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if (to.length === 0 || !re.test(to)) {
            alert("Bạn cần nhập địa chỉ người nhận");
            $(".moderate-forward #forwardTo").focus().addClass("error");
            return;
        }

        if ($(".frmForwardEmail").valid()) {
            $.ajax({
                type: "POST",
                url: "/admin/qa/ForwardQuestionEmail",
                beforeSend: function () {
                    $.blockUI({ message: '<p>Đang gửi mail....</p>', css: { height: '20px' } });
                },
                data: { qid: maiid, to: to, notes: notes },
                success: function (data) {
                    $.unblockUI();
                    $(".moderate-forward").modal("hide");
                    $(".moderate-forward .frmSendCrm").get(0).reset();
                    alert("Đã gửi email.");

                    location.reload();
                },
                error: function () {
                    $.unblockUI();
                    alert("Lỗi: ");
                }
            });
        }
    },
    quickReply: function (qid, type) {
        $.ajax({
            type: "GET",
            url: "/admin/qa/quickresponse",
            data: { type: type, id: qid },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                if (data && data.success)Ơ
                $("#quickModal").html(data).modal({
                    keyboard: false,
                    backdrop: "static"
                });
                $("body").data("modalmanager").removeLoading();
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Có lỗi. Vui lòng liên hệ bộ phận kỹ thuật.");
            }
        });
    },
    sendQuickReply: function () {
        var mtitle = $("*[name='mtitle']").val();
        if (mtitle.length === 0) {
            alert("Bạn cần nhập tiêu đề của nội dung này");
            $("*[name='mtitle']").focus().css('border-color', 'red');
            return false;
        }

        var ckContent = CKEDITOR.instances['ckQuickReply'].getData();
        var qrid = $("*[name='qrid']").val();
        var mtype = $("*[name='mtype']").val();

        $.ajax({
            type: "POST",
            url: "/admin/qa/savequickreply",
            data: { ckContent: ckContent, qrid: qrid, mtype: mtype, mtitle: mtitle },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $("body").data("modalmanager").removeLoading();

                if (data.success) {
                    $("#quickModal").html(data).modal("hide");
                    $(".moderate-modal").html(data).modal("hide");
                    $.growlUI("Đã xử lý", "", 800, function () {
                    location.reload();
                    });
                } else {
                    alert("Có lỗi: " + data.message);
                }

            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Có lỗi. Vui lòng liên hệ bộ phận kỹ thuật.");
            }
        });
    },
    removeQuickReply: function (id) {
        var obj = {
            confirm: "Chắc chắn hủy nội dung này?",
            url: "/admin/qa/RemoveQuickReply",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.reload();
        });
    },
    enableQuickResponse: function (element, id, enable) {
        enable = !$(element).data("enable");

        $.ajax({
            type: "POST",
            url: "/admin/qa/SetQuickResponse",
            data: { bd: id, enable: enable },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function () {
                if ($(".boxModerateQuest").length > 0) {
                    var box = $(element).closest(".box");

                    box.find(".changequickreplystatus span").text(" " + (enable ? "Unset" : "Set") + " trả lời nhanh");

                    if (box.find(".box-title h3 .itemshow sup").length > 0) {
                        if (!enable) {
                            box.find(".box-title h3 .itemshow sup").hide().remove();
                        }
                    } else {
                        if (enable) {
                            box.find(".box-title h3 .itemshow").append("<sup style=\"color:red\"> (Trả lời nhanh)</sup>");
                        }
                    }
                    $(element).data("enable", enable);
                } else {

                    $("body").data("modalmanager").removeLoading();
                    $.growlUI("Set thành công", "", 200, function () {
                        $(".moderate-modal").modal("hide");
                        //location.reload();
                    });
                }

                $("body").data("modalmanager").removeLoading();
                location.reload();
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Error");
            }
        });
    },
    changeToPublicQuickResponse: function (id) {
        $.ajax({
            type: "POST",
            url: "/admin/qa/ChangeQuickResponseEmailStatus",
            data: { qrid: id },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function () {
                $("body").data("modalmanager").removeLoading();
                $(".modal").each(function () {
                    if ($(this).is(':visible')) {
                        $(this).modal("hide");
                    }
                });
                $.growlUI("Set thành công", "", 200, function () {

                    location.reload();
                });
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Error");
            }
        });
    },
    removeAsSpam: function (qid) {
        $.ajax({
            type: "POST",
            url: "/admin/qa/RemoveQuestSpam",
            data: { qid: qid },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function () {
                $("body").data("modalmanager").removeLoading();
                $(".modal").each(function () {
                    if ($(this).is(':visible')) {
                        $(this).modal("hide");
                    }
                });
                $.growlUI("Đã xóa", "", 200, function () {
                    location.reload();
                });
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Lỗi xóa yêu cầu tư vấn");
            }
        });
    },
    quickReplyTaskShow: function (id) {
        $.ajax({
            url: "/admin/qa/QuestModerateViewDetail",
            type: "get",
            data: { qid: id, type: "quickview" },
            beforeSend: function () {
                $("body").modalmanager('loading');
            },
            success: function (data) {
                $(".moderate-modal").html(data).modal("show");
                $("body").data("modalmanager").removeLoading();
            },
            error: function () {
                $("body").data("modalmanager").removeLoading();
                alert("Lỗi, xin vui lòng thử lại");
            }
        });
    },
    editInModerateView: function (id, element) {
        // openWin("/admin/qa/showedittask?qid=" + id, "Cập nhật nội dung tư vấn", 800, 500);
        var box = $(element).closest(".box");
        box.find(".itemhide").show();
        box.find(".itemshow").hide();
        if (CKEDITOR.instances["ckm-" + id] == null) {
            CKEDITOR.inline("ckm-" + id);
        }

        var form = box.find(".customerForm");
        $.ajax({
            type: "GeT",
            url: "/admin/customer/loadbyquest",
            data: { qid: id },
            success: function (data) {
                form.find(".email").val(data.Cemail);
                form.find(".name").val(data.Cname);
                form.find(".address").val(data.Address);
                form.find(".phone").val(data.Cphone);
                form.find(".id").val(data.Cid);
            }
        });

    },
    cancelEditAQuest: function (id, element) {
        var box = $(element).closest(".box");
        box.find(".itemhide").hide();
        box.find(".itemshow").show();
    },
    saveEditAQuest: function (id, element) {
        var box = $(element).closest(".box");
        var title = box.find(".q-subject").val();
        var content = CKEDITOR.instances["ckm-" + id].getData();

        $.ajax({
            url: "/admin/qa/editable",
            data: { name: "content", title: title, content: content, qid: id },
            type: "POST",
            success: function () {
                box.find(".box-title h3 .itemshow").text(title);
                box.find(".box-content .itemshow").html(content);

                box.find(".itemhide").hide();
                box.find(".itemshow").show();

                $.growlUI("Đã lưu " + title);

                var form = box.find(".customerForm");
                $.ajax({
                    url: "/admin/customer/SaveEditTask",
                    data: form.serialize(),
                    type: "POST",
                    success: function () {
                    },
                    error: function () {
                        alert("Có lỗi xảy ra khi lưu thông tin khách hàng...");
                    }
                });

            },
            error: function () {
                alert("Có lỗi xảy ra...");
                box.find(".itemhide").hide();
                box.find(".itemshow").show();
            }
        });




    },
    editCustomerView: function (id, element) {
        openWin("/admin/customer/showedittask?qid=" + id, 'Compose', 800, 400, 'compose');
    }
};

var Customer = {
    Show: function(id) {
        openWin("/admin/customer/show/" + id, "Compose", 1000, 590, "compose");
    },
    DeleteQuestion: function(id, elm) {
        var obj = {
            confirm: "Bạn muốn xoá câu hỏi này?",
            url: "/admin/qa/DeleteAQuest",
            data: { qid: id }
        };
        removeRecord(obj, function() {
            $(elm).closest("tr").fadeOut();
        });
    },
    ShowModal: function(id) {
        var $modal = $('body');


        $modal.modalmanager('loading');

        //setTimeout(function() {
        //    $modal.load("/admin/customer/show/"+id, '', function() {
        //        $modal.modal();
        //    });
        //}, 1000);

        //$('.ajax .demo').on('click', function () {
        //    // create the backdrop and wait for next modal to be triggered
        //    $('body').modalmanager('loading');

        //    setTimeout(function () {
        //        $modal.load('modal_ajax_test.html', '', function () {
        //            $modal.modal();
        //        });
        //    }, 1000);
        //});

        //$modal.on('click', '.update', function () {
        //    $modal.modal('loading');
        //    setTimeout(function () {
        //        $modal
        //          .modal('loading')
        //          .find('.modal-body')
        //            .prepend('<div class="alert alert-info fade in">' +
        //              'Updated!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
        //            '</div>');
        //    }, 1000);
        //});


        $("#modal-customer").load("/admin/customer/show/" + id, '', function() {
            $("#modal-customer").modal('show');
            $modal.data("modalmanager").removeLoading();
        });
    },
    ShowQuestDetail: function(id) {
        var $modalLoading = $('body');
        $.ajax({
            url: "/admin/qa/showfullquest",
            type: "get",
            beforeSend: function() {
                $modalLoading.modalmanager('loading');
            },
            data: { id: id },
            success: function(data) {
                $(".modal-quest").html(data).modal('show');
                $modalLoading.data("modalmanager").removeLoading();
            },
            error: function() {
                alert("Lỗi khi tải chi tiết câu hỏi");
                $modalLoading.data("modalmanager").removeLoading();
            }
        });
    }
};

OldUrl = {
    Delete: function (id) {
        var obj = {
            confirm: "Bạn muốn xoá link này?",
            url: "/admin/system/DeleteOldLink",
            data: { id: id }
        };
        removeRecord(obj, function () {
            location.href = window.location.href;
        });
    }
};

QuickResponse = function() {
    this.init = function() {
        var me = this;
        $(".btnShowDetail").on("click", function() {
            var id = $(this).data("id");
            me.showDetail(id, function(data) {
                $("#modal-quickresponse").html(data).modal("show");
            });
        });
        $(".btnHide").on("click", function() {
            var id = $(this).data("id");
            me.markAsPublic(id, function(d) {
                $.growlUI("Lưu thành công");
                location.reload();
            });
        });
        $(".btnExport").on("click", function() {
            var checkRows = $(".advertiseTable .text-center input[type='checkbox']:checked");
            if (checkRows.length == 0) {
                alert("Bạn chưa chọn bài nào?");
            }

            var str = "";
            checkRows.each(function(index,value) {
                str+= $(this).data("id")+",";
            });
            str = str.substr(0, str.length - 1);
            console.log(str);

            location.href = "/admin/article/new?batch="+str;
        });
    };

    this.showDetail = function(id, callback) {
        var $modalLoading = $('body');
        $.ajax({
            url: "/admin/qa/showfullquest",
            type: "get",
            beforeSend: function() {
                $modalLoading.modalmanager('loading');
            },
            data: { id: id },
            success: function(data) {
                callback(data);
                $modalLoading.data("modalmanager").removeLoading();
            },
            error: function() {
                alert("Lỗi khi tải chi tiết câu hỏi");
                $modalLoading.data("modalmanager").removeLoading();
            }
        });
    }

    this.markAsPublic = function (qrid, callback) {
        $.ajax({
            type: "POST",
            url: "/admin/qa/MarkQuickReplyAsPublic",
            data: { id: qrid },
            success: callback,
            error: function () {
                alert("Lỗi khi xử lý ẩn nội dung trả lời nhanh");
            }
        });
    }
};


function openWin(a, b, c, d, e) {
    a = window.open(a, b + (e == "compose" ? top.composeWindowCount++ : ""), "directories=no,location=no,menubar=no,status=no,scrollbars=" + (e == "yes" || e == "compose" ? "yes" : "no") + ",resizable=" + (e == "yes" || e == "compose" ? "yes" : "no") + ",titlebars=no,toolbar=no,width=" + c + ",height=" + d);
    a.focus();
    if (!a.opener)
        a.opener = window;
    return a;
}

submitForm = function (element, success, error) {
    var data = $(element).serialize();
    var url = $(element).prop("action");
    var type = $(element).prop("method");

    $.ajax({
        type: type,
        data: data,
        url: url,
        success: success,
        error: error
    });
}

$("#btnReloadQuest").on("click", function () {
    var btn = $(this);
    $.ajax({
        url: "/admin/qa/getsomequestforme",
        type: "post",
        beforeSend: function () {
            btn.find("i").addClass("icon-spin");
            btn.prop('disabled', true);
        },
        success: function (data) {
            btn.find("i").removeClass("icon-spin");
            btn.prop('disabled', false);
            if (data && data.success) {
                alert("Vui lòng xem tab đang trả lời nếu bạn không tìm thấy câu hỏi");
                $.growlUI("Đã nhận thêm nội dung mới. Vui lòng kiểm tra tab mới nhận và tab đang  trả lời", '', 800, function () {
                    location.reload();
                });
            } else {
                alert(data.error);
            }
        },
        error: function () {
            btn.prop('disabled', false);
            btn.find("i").removeClass("icon-spin");
            alert("Đã xảy ra lỗi. Vui lòng kiểm tra phiên đăng nhập hoặc liên hệ bộ phận IT.");
        }
    });
});


var viewquest = function (cid) {
    $.ajax({
        url: "/admin/qa/viewquest?qid=" + cid,
        type: "get",
        success: function (data) {
            $(".modal-quest").html(data).modal("show");
        }
    });
};
$(document).on("click", ".btn-pushmailbox", function () {
    $.ajax({
        type: "POST",
        url: "/admin/qa/BatchEmailImport",
        data: {},
        timeout: 600000,
        beforeSend: function () {
            $("body").modalmanager('loading');
        },
        success: function (data) {
            if (data.success) {
                bootbox.alert("Done");
            } else {
                bootbox.modal(data.exception + " <br /> At:" + data.curItem, "Thông báo lỗi");
            }
            $("body").data("modalmanager").removeLoading();
        },
        error: function () {
            $("body").data("modalmanager").removeLoading();
            alert("Error");
        }
    });
});
//ASK REPLY
function autoSaveResponse() {
    if (CKEDITOR.instances.ckeditor1.getData().length > 0) {
        var form = $("#bb").clone();
        form.find("#ckeditor1").text(CKEDITOR.instances.ckeditor1.getData());

        $.ajax({
            url: "/admin/qa/saveresponse",
            type: "POST",
            data: form.serialize(),
            beforeSend: function () {

            },
            success: function () {
                $.growlUI("Thông báo", "Lưu tự động");
            },
            error: function () {
                $.growlUI("Thông báo", "Lưu tự động lỗi. Nhớ sao lưu câu trả lời và nhấn F5 lại");
            }
        });
    }
}
$("#btnAssignQuest").on("click", function () {
    var arr = [];
    $(".input-assignval:checked").each(function (index, value) {
        arr.push($(this).val());
    });

    if (arr.length === 0) {
        alert("Bạn chưa lựa chọn câu hỏi");
        return;
    }

    var $modalLoading = $('body');
    $.ajax({
        type: "get",
        traditional: true,
        url: "/admin/qa/GetQuestionAssigntoUser",
        data: { aids: arr },
        beforeSend: function () {
            $modalLoading.modalmanager('loading');
        },
        success: function (data) {
            var ids = arr.join();
            $modalLoading.data("modalmanager").removeLoading();
            $(".modal-assignTo").html(data).modal("show");
            $(".modal-assignTo").find("*[name=ids]").val(ids);
        },
        error: function () {
            $modalLoading.data("modalmanager").removeLoading();
            alert("Loi khi load");
        }
    });

    $(".modal-assignTo").modal("show");
});

$(document).on('shown.bs.modal', '.modal-assignTo', function () {
    var me = $(this);
    me.css('margin-top', '0');
    //load ajax users
    $("#guiCho").select2({
        minimumInputLength: 3,
        placeholder: "Tìm người cần gán...",
        initSelection: function (element, callback) {
            callback({ id: 0, text: 'Tất cả' });
        },
        ajax: {
            url: "/admin/searchusers",
            delay: 2000,
            data: function (params) {
                return {
                    name: params
                };
            },
            results: function (data, page) {
                var newData = [];
                $.each(data, function (item, value) {
                    newData.push({
                        id: value.UserID,
                        text: value.FullName //string to be displayed
                    });
                });
                return { results: newData };
            }
        }
    });
});

$(document).on("click", ".btn-editCustomer", function () {
    var form = $(this).closest("form");
    form.find(".hidden-control").show().prev().hide();
    $(".btn-saveEditCustomer").show();
    $(this).hide();
});

$(document).on("click", ".btn-saveEditCustomer", function () {
    var me = $(this);
    var spanNotifier = $(this).parent().find("span");
    var form = $(this).closest("form");
    if (form.valid()) {
        $.ajax({
            url: "/admin/customer/savecustomer",
            type: "post",
            beforeSend: function () {
                spanNotifier.text("Đang lưu...").show();
            },
            data: form.serialize(),
            success: function () {
                form.find(".hidden-control").each(function () {
                    var val = $(this).find("input").val();
                    $(this).hide().prev().text(val).show();
                });

                $(".btn-editCustomer").show();
                me.hide();
                spanNotifier.text("Đã lưu");
            },
            error: function () {
                spanNotifier.text("Lỗi");
            }
        });
    }
});

$(document).on("click", ".btn-assigned-to", function () {
    var form = $(this).closest(".modal").find("form");
    var modal = $(this).closest(".modal");
    var $modalLoading = $('body');
    $.ajax({
        type: "post",
        url: "/admin/qa/SaveAssignQuestToUser",
        data: form.serialize(),
        beforeSend: function () {
            $modalLoading.modalmanager('loading');
        },
        success: function () {
            $modalLoading.data("modalmanager").removeLoading();
            modal.modal("hide");
            $.growlUI('', 'Gán thành công');
            setTimeout(function () {
                $(".input-assignval:checked").each(function (index, value) {
                    $(this).closest("tr").fadeOut('slow').remove();
                });
            }, 500);
        },
        error: function () {
            $modalLoading.data("modalmanager").removeLoading();
            alert("Loi khi load");
        }
    });
});

$(document).on('shown.bs.modal','#modal-notification', function () {
    ckCreate('ckThongBao');

    //load ajax users
    $("#guiCho").select2({
        minimumInputLength: 3,
        placeholder: "Tìm người cần gán...",
        initSelection: function (element, callback) {
            callback({ id: 0, text: 'Tất cả' });
        },
        ajax: {
            url: "/admin/account/searchusers",
            delay: 2000,
            data: function (params) {
                return {
                    q: params
                };
            },
            results: function (data, page) {
                var newData = [];
                $.each(data, function (item, value) {
                    newData.push({
                        id: value.UserID,
                        text: value.FullName //string to be displayed
                    });
                });
                return { results: newData };
            }
        }
    }).select2('val', []);
});
$(document).on('shown.bs.modal', '.modal', function (event) {
    if (event.target && $(event.target).hasClass("modal")) {
        $(this).find(".ckeditor").each(function () {
            var ck = CKEDITOR.replace($(this).prop("id"),
              {
                  //   toolbar: 'Basic', /* this does the magic */
                  height: '300px'
              });
            CKFinder.setupCKEditor(ck, '/content/ckfinder/');
        });
    }
});


$("#slModeratorType,#slUserList").on("change", function () {
    $("#btnModerateFilter").trigger("click");
});


$("#btnUserFilter").on("click", function () {
    var url = "/admin/account/index";
    var temp = "";

    if ($("#btnChangeUserType").val() !== "0") {
        temp += "?type=" + $("#btnChangeUserType").val();
    }

    if ($("#btnModerateBy").val() !== "0") {
        if (temp.length > 0) {
            temp += "&mtype=" + $("#btnModerateBy").val();
        } else {
            temp += "?mtype=" + $("#btnModerateBy").val();
        }
    }
    location.href = url + temp;
});

$("#btnModerateFilter").on("click", function () {
    var q = "";
    if ($("#slModeratorType").val() !== "0") {
        q = "?f=" + $("#slModeratorType").val();
    }

    if (q.length > 0 && $("#slUserList").val() !== "") {

        q += "&u=" + $("#slUserList").val();
    }

    location.href = "/admin/qa/moderate" + q;
});

    $("#slEmailChanger").on("change", function() {
        var val = $(this).val();
        $.ajax({
            type: "POST",
            url: "/admin/qa/SetEmailAccount",
            data: { email: val },
            success: function() {
                location.reload();
            }
        });
    });