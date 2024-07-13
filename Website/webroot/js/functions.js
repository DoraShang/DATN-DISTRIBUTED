var current_edior_id = '';
var dropzone_action = '';
var dropzone_total = 0;
var dropzone_current_no_uplload = 0;



function file_manager(render_id) {
    var finder = new CKFinder();
    finder.basePath = 'ckfinder/';  // The path for the installation of CKFinder (default = "/ckfinder/").
    finder.selectActionFunction = function (fileUrl) {
        $('#' + render_id).val(fileUrl);
        $('.thumbnail-preview.' + render_id).html("<img src='" + fileUrl + "' />");
    }
    finder.popup();
}

$(document).ready(function () {
    $("#check_all").click(function () {
        $(".check_id").attr('checked', $(this).is(':checked'));
    });

    $('.confirm-delete').click(function () {
        var link = $(this).attr('goto');
        if (confirm('Bạn chắc chắn muốn xóa dữ liệu?') == true) {
            document.location.href = link;
        }
    });

    $('.number').number(true, 0);

});


function insert_images(lst_images)
{
    console.log('insert_images to current_edior_id: ' + current_edior_id);
    console.log('insert_images to current_edior_id images: ' + lst_images);
    if(current_edior_id == '') {}
    else
    {
        var img_arr = lst_images.split(',');
        if(img_arr.length > 1)
        {
            for(var i = 0; i<img_arr.length - 1; i++)
            {
                var img = '<p><img src="' + img_arr[i] + '" /></p>';
                CKEDITOR.instances[current_edior_id].insertHtml(img);
            }
        }
    }
}
