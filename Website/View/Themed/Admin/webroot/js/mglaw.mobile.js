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
                            $("#btnAsknowQuest").click();
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