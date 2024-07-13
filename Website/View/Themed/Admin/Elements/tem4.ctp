<div class="teamplate-element element-<?php echo $index ?> template-banner" id="element-<?php echo $index ?>" data-template="tem4">
    <div class="row">
        <div class="col-sm-12">
            <div class="tem4-wrap">
                <div class="template-image centerbox">
                    <div class="tpl-banner thumbnail-preview tpl-item-<?php echo $index ?>">
                        <?php if ($data['image'] != '') { ?>
                            <img src="<?php echo DOMAIN . $data['image'];  ?>" alt="">
                        <?php }     ?>
                    </div>
                    <input type="hidden" name="page_element[<?php echo $index ?>][field][image]" id="tpl-item-<?php echo $index ?>" value="<?php echo $data['image'] ?>">
                    <input type="button" class="btn btn-success m10" onclick="file_manager('tpl-item-<?php echo $index ?>');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png,
                    .swf)
                </div>
                <div class="tempalte-content">
                    <!-- <textarea name="page_element[<?php echo $index ?>][field][content]" id="" placeholder="Enter Content"></textarea> -->
                    <?php
                    $CKEditor = new CKEditor();
                    $CKEditor->config['width'] = '100%';
                    $CKEditor->config['height'] = '200';
                    $CKEditor->config['toolbar'] = [
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
                    ];
                    CKFinder::SetupCKEditor($CKEditor);

                    $initialValue = $data['content'];
                    echo $CKEditor->editor("page_element[$index][field][content]", $initialValue, "extra");
                    ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="tem4" name='page_element[<?php echo $index ?>][type_tem]'>
    <input id='link-<?php echo $index ?>' type="hidden" value="<?php echo $data['link'] ?>" name='page_element[<?php echo $index ?>][field][link]'>
    <div class="tem-funtion">
        <div class="tem-funtion-wrap">
            <img class="remove-element" src="<?php echo $url_image ?>delete.svg" alt="" data-item="element-<?php echo $index ?>" title="delete"> <span></span>
            <img class="add-link" data-link='link-<?php echo $index ?>' data-toggle="modal" data-target="#addmodallink" src=" <?php echo $url_image ?>icon_link.svg" alt="" data-item="element-<?php echo $index ?>" title="add link"> <span></span>
            <img class="add-element" src="<?php echo $url_image ?>add_element.svg" alt="" data-template="tem4" title="add">
        </div>
    </div>
</div>