

var obj_template = {
    'tem1': render_tem1,
    'tem2': render_tem2,
    'tem3': render_tem3,
    'tem4': render_tem4,
    'tem5': render_tem5,
    'tem6': render_tem6,

}

var price_range = document.getElementById('page-body');
new Sortable(price_range, {
    group: 'shared', // set both lists to same group
    animation: 150
});

// render tempalte 1
function render_tem1() {
    var count_element = $('#count_item').val();
    if (count_element == 0) {
        $('#page-body').html('');
    }
    var str = `<div class="teamplate-element element-${count_element} template-banner" id="element-${count_element}" data-template="tem1">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tpl-banner thumbnail-preview tpl-item-${count_element}">
                            </div>
                            <input type="hidden" name="page_element[${count_element}][field][image]" id="tpl-item-${count_element}">
                            <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-${count_element}');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                            .swf)
                        </div>
                    </div>
                    <input type="hidden" value="tem1" name='page_element[${count_element}][type_tem]'>
                    <input id='link-${count_element}' type="hidden" value="" name='page_element[${count_element}][field][link]'>
                    <div class="tem-funtion">
                        <div class="tem-funtion-wrap">
                            <img class="remove-element" src=" ${URL_IMAGE}delete.svg" alt="" data-item="element-${count_element}" title="delete"> <span></span>
                            <img class="add-link" data-link='link-${count_element}' data-toggle="modal" data-target="#addmodallink" src=" ${URL_IMAGE}icon_link.svg" alt="" data-item="element-${count_element}" title="add link"> <span></span>
                            <img class="add-element" src=" ${URL_IMAGE}add_element.svg" alt="" data-template="tem1" title="add">
                        </div>
                    </div>
                </div>`;
    $('#page-body').append(str);
    $('#count_item').val(Number(count_element) + 1);
    // delete
    $('.remove-element').off('click').on('click', (function (event) {
        var item = $(this).attr('data-item');
        $(`.${item}`).remove();
    }))
    // add
    $('.add-element').off('click').on('click', (function (event) {
        var type = $(this).attr('data-template');
        obj_template[type]();
    }))
    $('html, body').animate({
        scrollTop: parseInt($(`#element-${count_element}`).offset().top - 100)
    }, 100);

    $('.add-link').off('click').on('click', function () {
        var data_link = $(this).attr('data-link');
        $('#i-modal-link').attr('data-link', data_link);
        $('#i-modal-link').val($(`#${data_link}`).val());
    })

}

// render tempalte 2
function render_tem2() {
    var count_element = $('#count_item').val();
    if (count_element == 0) {
        $('#page-body').html('');
    }
    var str = `<div class="teamplate-element element-${count_element} template-banner" id="element-${count_element}" data-template="tem2">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tpl-banner thumbnail-preview tpl-item-${count_element}">
                            </div>
                            <input type="hidden" name="page_element[${count_element}][field][image]" id="tpl-item-${count_element}">
                            <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-${count_element}');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                            .swf)
                        </div>
                        <div class="col-sm-6">
                            <div class="tempalte-title">
                                <input type="text" name="page_element[${count_element}][field][title]" placeholder="Enter Title">
                            </div>
                            <div class="tempalte-content">
                                <textarea name="page_element[${count_element}][field][content]" id="" placeholder="Enter Content"></textarea>
                            </div>
                            <div class="page-button"><button type="button"><input placeholder="txt button" name='page_element[${count_element}][field][txt]'/></button></div>
                        </div>
                    </div>
                    <input type="hidden" value="tem2" name='page_element[${count_element}][type_tem]'>
                    <input id='link-${count_element}' type="hidden" value="" name='page_element[${count_element}][field][link]'>
                    <div class="tem-funtion">
                        <div class="tem-funtion-wrap">
                            <img class="remove-element" src=" ${URL_IMAGE}delete.svg" alt="" data-item="element-${count_element}" title="delete"> <span></span>
                            <img class="add-link" data-link='link-${count_element}' data-toggle="modal" data-target="#addmodallink" src=" ${URL_IMAGE}icon_link.svg" alt="" data-item="element-${count_element}" title="add link"> <span></span>
                            <img class="add-element" src=" ${URL_IMAGE}add_element.svg" alt="" data-template="tem2" title="add">
                        </div>
                    </div>
                </div>`;

    $('#page-body').append(str);
    build_ckeditor(`page_element[${count_element}][field][content]`)
    $('#count_item').val(Number(count_element) + 1);
    // delete
    $('.remove-element').off('click').on('click', (function (event) {
        var item = $(this).attr('data-item');
        $(`.${item}`).remove();
    }))
    // add
    $('.add-element').off('click').on('click', (function (event) {
        var type = $(this).attr('data-template');
        obj_template[type]();
    }))
    $('html, body').animate({
        scrollTop: parseInt($(`#element-${count_element}`).offset().top - 100)
    }, 100);

    $('.add-link').off('click').on('click', function () {
        var data_link = $(this).attr('data-link');
        $('#i-modal-link').attr('data-link', data_link);
        $('#i-modal-link').val($(`#${data_link}`).val());
    })

}

// render tempalte 5
function render_tem5() {
    var count_element = $('#count_item').val();
    if (count_element == 0) {
        $('#page-body').html('');
    }
    var str = `<div class="teamplate-element element-${count_element} template-banner" id="element-${count_element}" data-template="tem5">
                    <div class="row">
                        <div class="col-sm-6 uncol">
                            <div class="tpl-banner thumbnail-preview tpl-item-${count_element}">
                            </div>
                            <input type="hidden" name="page_element[${count_element}][field][image]" id="tpl-item-${count_element}">
                            <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-${count_element}');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                            .swf)
                        </div>
                        <div class="col-sm-6">
                            <div class="tempalte-title">
                                <input type="text" name="page_element[${count_element}][field][title]" placeholder="Enter Title">
                            </div>
                            <div class="tempalte-content">
                                <textarea name="page_element[${count_element}][field][content]" id="" placeholder="Enter Content"></textarea>
                            </div>
                            <div class="page-button"><button type="button"><input placeholder="txt button" name='page_element[${count_element}][field][txt]'/></button></div>
                        </div>
                    </div>
                    <input type="hidden" value="tem5" name='page_element[${count_element}][type_tem]'>
                    <input id='link-${count_element}' type="hidden" value="" name='page_element[${count_element}][field][link]'>
                    <div class="tem-funtion">
                        <div class="tem-funtion-wrap">
                            <img class="remove-element" src=" ${URL_IMAGE}delete.svg" alt="" data-item="element-${count_element}" title="delete"> <span></span>
                            <img class="add-link" data-link='link-${count_element}' data-toggle="modal" data-target="#addmodallink" src=" ${URL_IMAGE}icon_link.svg" alt="" data-item="element-${count_element}" title="add link"> <span></span>
                            <img class="add-element" src=" ${URL_IMAGE}add_element.svg" alt="" data-template="tem5" title="add">
                        </div>
                    </div>
                </div>`;

    $('#page-body').append(str);
    build_ckeditor(`page_element[${count_element}][field][content]`)
    $('#count_item').val(Number(count_element) + 1);

    // delete
    $('.remove-element').off('click').on('click', (function (event) {
        var item = $(this).attr('data-item');
        $(`.${item}`).remove();
    }))

    // add
    $('.add-element').off('click').on('click', (function (event) {
        var type = $(this).attr('data-template');
        obj_template[type]();
    }))

    $('html, body').animate({
        scrollTop: parseInt($(`#element-${count_element}`).offset().top - 100)
    }, 100);

    $('.add-link').off('click').on('click', function () {
        var data_link = $(this).attr('data-link');
        $('#i-modal-link').attr('data-link', data_link);
        $('#i-modal-link').val($(`#${data_link}`).val());
    })

}

// render tempalte 3
function render_tem3() {
    var count_element = $('#count_item').val();
    if (count_element == 0) {
        $('#page-body').html('');
    }
    var str = `<div class="teamplate-element element-${count_element} template-banner" id="element-${count_element}" data-template="tem3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tempalte-title">
                                <input type="text" name="page_element[${count_element}][field][title]" placeholder="Enter Title">
                            </div>
                            <div class="tempalte-content">
                                <textarea name="page_element[${count_element}][field][content]" id="" placeholder="Enter Content"></textarea>
                            </div>
                            <div class="page-button"><button type="button"><input placeholder="txt button" name='page_element[${count_element}][field][txt]'/></button></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tpl-banner thumbnail-preview tpl-item-${count_element}">
                            </div>
                            <input type="hidden" name="page_element[${count_element}][field][image]" id="tpl-item-${count_element}">
                            <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-${count_element}');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                            .swf)
                        </div>
                    </div>
                    <input type="hidden" value="tem3" name='page_element[${count_element}][type_tem]'>
                    <input id='link-${count_element}' type="hidden" value="" name='page_element[${count_element}][field][link]'>
                    <div class="tem-funtion">
                        <div class="tem-funtion-wrap">
                            <img class="remove-element" src=" ${URL_IMAGE}delete.svg" alt="" data-item="element-${count_element}" title="delete"> <span></span>
                            <img class="add-link" data-link='link-${count_element}' data-toggle="modal" data-target="#addmodallink" src=" ${URL_IMAGE}icon_link.svg" alt="" data-item="element-${count_element}" title="add link"><span></span>
                            <img class="add-element" src=" ${URL_IMAGE}add_element.svg" alt="" data-template="tem3" title="add">
                        </div>
                    </div>
                </div>`;

    $('#page-body').append(str);

    build_ckeditor(`page_element[${count_element}][field][content]`)

    $('#count_item').val(Number(count_element) + 1);

    // delete
    $('.remove-element').off('click').on('click', (function (event) {
        var item = $(this).attr('data-item');
        $(`.${item}`).remove();
    }))

    // add
    $('.add-element').off('click').on('click', (function (event) {
        var type = $(this).attr('data-template');
        obj_template[type]();
    }))

    $('html, body').animate({
        scrollTop: parseInt($(`#element-${count_element}`).offset().top - 100)
    }, 100);

    $('.add-link').off('click').on('click', function () {
        var data_link = $(this).attr('data-link');
        $('#i-modal-link').attr('data-link', data_link);
        $('#i-modal-link').val($(`#${data_link}`).val());
    })
}

// render tempalte 6
function render_tem6() {
    var count_element = $('#count_item').val();
    if (count_element == 0) {
        $('#page-body').html('');
    }
    var str = `<div class="teamplate-element element-${count_element} template-banner" id="element-${count_element}" data-template="tem6">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tempalte-title">
                                <input type="text" name="page_element[${count_element}][field][title]" placeholder="Enter Title">
                            </div>
                            <div class="tempalte-content">
                                <textarea name="page_element[${count_element}][field][content]" id="" placeholder="Enter Content"></textarea>
                            </div>
                            <div class="page-button"><button type="button"><input placeholder="txt button" name='page_element[${count_element}][field][txt]'/></button></div>
                        </div>
                        <div class="col-sm-6 uncol">
                            <div class="tpl-banner thumbnail-preview tpl-item-${count_element}">
                            </div>
                            <input type="hidden" name="page_element[${count_element}][field][image]" id="tpl-item-${count_element}">
                            <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-${count_element}');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                            .swf)
                        </div>
                    </div>
                    <input type="hidden" value="tem6" name='page_element[${count_element}][type_tem]'>
                    <input id='link-${count_element}' type="hidden" value="" name='page_element[${count_element}][field][link]'>
                    <div class="tem-funtion">
                        <div class="tem-funtion-wrap">
                            <img class="remove-element" src=" ${URL_IMAGE}delete.svg" alt="" data-item="element-${count_element}" title="delete"> <span></span>
                            <img class="add-link" data-link='link-${count_element}' data-toggle="modal" data-target="#addmodallink" src=" ${URL_IMAGE}icon_link.svg" alt="" data-item="element-${count_element}" title="add link"><span></span>
                            <img class="add-element" src=" ${URL_IMAGE}add_element.svg" alt="" data-template="tem6" title="add">
                        </div>
                    </div>
                </div>`;

    $('#page-body').append(str);

    build_ckeditor(`page_element[${count_element}][field][content]`)

    $('#count_item').val(Number(count_element) + 1);

    // delete
    $('.remove-element').off('click').on('click', (function (event) {
        var item = $(this).attr('data-item');
        $(`.${item}`).remove();
    }))

    // add
    $('.add-element').off('click').on('click', (function (event) {
        var type = $(this).attr('data-template');
        obj_template[type]();
    }))

    $('html, body').animate({
        scrollTop: parseInt($(`#element-${count_element}`).offset().top - 100)
    }, 100);

    $('.add-link').off('click').on('click', function () {
        var data_link = $(this).attr('data-link');
        $('#i-modal-link').attr('data-link', data_link);
        $('#i-modal-link').val($(`#${data_link}`).val());
    })
}

function render_tem4() {
    var count_element = $('#count_item').val();
    if (count_element == 0) {
        $('#page-body').html('');
    }

    var str = `
            <div class="teamplate-element element-${count_element} template-banner" id="element-${count_element}" data-template="tem4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tem4-wrap">
                            <div class="template-image centerbox">
                                <div class="tpl-banner thumbnail-preview tpl-item-${count_element}">
                                </div>
                                <input type="hidden" name="page_element[${count_element}][field][image]" id="tpl-item-${count_element}" value="">
                                <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-${count_element}');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                                .swf)
                            </div>
                            <div class="tempalte-content">
                                <textarea name="page_element[${count_element}][field][content]" id="" placeholder="Enter Content"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="tem4" name='page_element[${count_element}][type_tem]'>
                <input id='link-${count_element}' type="hidden" value="" name='page_element[${count_element}][field][link]'>
                <div class="tem-funtion">
                    <div class="tem-funtion-wrap">
                        <img class="remove-element" src=" ${URL_IMAGE}delete.svg" alt="" data-item="element-${count_element}" title="delete"> <span></span>
                        <img class="add-link" data-link='link-${count_element}' data-toggle="modal" data-target="#addmodallink" src=" ${URL_IMAGE}icon_link.svg" alt="" data-item="element-${count_element}" title="add link"><span></span>
                        <img class="add-element" src=" ${URL_IMAGE}add_element.svg" alt="" data-template="tem4" title="add">
                    </div>
                </div>
            </div>`;

    $('#page-body').append(str);

    build_ckeditor(`page_element[${count_element}][field][content]`)

    $('#count_item').val(Number(count_element) + 1);

    // delete
    $('.remove-element').off('click').on('click', (function (event) {
        var item = $(this).attr('data-item');
        $(`.${item}`).remove();
    }))

    // add
    $('.add-element').off('click').on('click', (function (event) {
        var type = $(this).attr('data-template');
        obj_template[type]();
    }))

    $('html, body').animate({
        scrollTop: parseInt($(`#element-${count_element}`).offset().top - 100)
    }, 100);

    $('.add-link').off('click').on('click', function () {
        var data_link = $(this).attr('data-link');
        $('#i-modal-link').attr('data-link', data_link);
        $('#i-modal-link').val($(`#${data_link}`).val());
    })
}



$('.add-element').click(function () {
    var type = $(this).attr('data-template');
    obj_template[type]();
    console.log('xxx')
})

$('.remove-element').click(function () {
    var item = $(this).attr('data-item');
    $(`.${item}`).remove()

})
$('.add-link').on('click', function () {
    var data_link = $(this).attr('data-link');
    $('#i-modal-link').attr('data-link', data_link);
    $('#i-modal-link').val($(`#${data_link}`).val());
})
$("#addmodallink").on('hidden.bs.modal', function () {
    var value = $('#i-modal-link').val();
    var data_link = $('#i-modal-link').attr('data-link');
    console.log(data_link);
    $(`#${data_link}`).val(value);
    console.log($(`#${data_link}`).val());
});

// build ckeditor
function build_ckeditor(name) {
    CKEDITOR.replace(`${name}`, {
        "width": "100%",
        "height": "200",
        // "filebrowserBrowseUrl": `https:\/\/${DOMAIN}js\/ckfinder\/finder.php?check=c626ec112c1a9032ff0a525bf4f68cf6`,
        // "filebrowserImageBrowseUrl": `https:\/\/${DOMAIN}js\/ckfinder\/finder.php?check=c626ec112c1a9032ff0a525bf4f68cf6`,
        // "filebrowserFlashBrowseUrl": `https:\/\/${DOMAIN}js\/ckfinder\/finder.php?check=c626ec112c1a9032ff0a525bf4f68cf6`,
        // "filebrowserUploadUrl": `https:\/\/${DOMAIN}js\/ckfinder\/core\/connector\/php\/connector.php?command=QuickUpload`,
        // "filebrowserImageUploadUrl": `https:\/\/${DOMAIN}js\/ckfinder\/core\/connector\/php\/connector.php?command=QuickUpload`,
        // "filebrowserFlashUploadUrl": `https:\/\/${DOMAIN}js\/ckfinder\/core\/connector\/php\/connector.php?command=QuickUpload`,
        "toolbar": [
            ["Source"],
            ["PasteFromWord", "-"],
            ["Undo", "Redo", "-", "Find", "Replace", "-", "RemoveFormat"],
            ["Bold", "Italic", "Underline", "Strike", "-", "Subscript", "Superscript"],
            ["NumberedList", "BulletedList", "-", "Outdent", "Indent"],
            ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
            ["Link", "Anchor"],
            // ["Image", "Flash", "Youtube", "Table", "HorizontalRule", "SpecialChar", "PageBreak", "lineheight"],
            ["Styles", "Format", "Font", "FontSize"],
            ["TextColor", "BGColor"],
            ["ShowBlocks", "Maximize"]
        ],
    });
}

