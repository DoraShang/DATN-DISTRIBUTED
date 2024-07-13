<!DOCTYPE html>
<html>

<head>
    <title>Quản trị <?php echo $settings['title']; ?></title>
    <meta name="robots" content="noindex,nofollow">
    <meta name=viewport content="width=device-width,initial-scale=1,user-scalable=yes">
    <meta name=apple-mobile-web-app-capable content=yes>

    <link href="<?php echo DOMAIN; ?>theme/admin/css/bootstrap/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo DOMAIN; ?>theme/admin/css/bootstrap/plugins/icheck/all.css" rel="stylesheet" />
    <link href="<?php echo DOMAIN; ?>theme/admin/css/bootstrap/style.css" rel="stylesheet" />
    <link href="<?php echo DOMAIN; ?>theme/admin/css/bootstrap/themes.css" rel="stylesheet" />
    <link href="<?php echo DOMAIN; ?>theme/admin/css/qa.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo DOMAIN; ?>css/bootstrap-colorpicker.min.css" />
    <link href="<?php echo DOMAIN; ?>theme/admin/css/jquery.datetimepicker.min.css" rel="stylesheet" />
    <link href="<?php echo DOMAIN; ?>theme/admin/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script src="<?php echo DOMAIN; ?>theme/admin/js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <?php /*
        <script src="<?php echo DOMAIN; ?>theme/admin/js/bootstrap/plugins/nicescroll/jquery.nicescroll.min.js" type="text/javascript"></script>
        <script src="<?php echo DOMAIN; ?>theme/admin/js/bootstrap/plugins/validation/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo DOMAIN; ?>theme/admin/js/bootstrap/plugins/validation/additional-methods.min.js" type="text/javascript"></script>
        <script src="<?php echo DOMAIN; ?>theme/admin/js/bootstrap/plugins/icheck/jquery.icheck.min.js" type="text/javascript"></script>
        <script src="<?php echo DOMAIN; ?>theme/admin/js/bootstrap/eakroko.min.js" type="text/javascript"></script>   
        */ ?>

    <script src="<?php echo DOMAIN; ?>theme/admin/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo DOMAIN; ?>theme/admin/js/jquery.datetimepicker.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>theme/admin/js/sortable.js"></script>

    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/ckfinder/ckfinder.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/jquery.number.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/bootstrap-colorpicker.min.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/functions.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <?php /*
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/bootstrap.datepicker.js"></script>
        */ ?>

</head>

<body class="theme-satblue">
    <div id="navigation" class="navbar-fixed-top">
        <?php echo View::element('navbar'); ?>
    </div>

    <div class="nav-fixed container-fluid" id="content">
        <?php echo View::element('sidebar'); ?>


        <div class="modal fade modal-dropzone" id="modal-dropzone">
            <div class="modal-dialog">
                <div class="modal-close"></div>
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="dropzone dz-clickable" id="kt_dropzonejs_example_1">
                            <!--begin::Message-->
                            <div class="dz-message needsclick">
                                <!--begin::Icon-->
                                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                <!--end::Icon-->

                                <!--begin::Info-->
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                    <span class="fs-7 fw-bold text-primary opacity-75">Upload up to 100 files</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $content_for_layout; ?>
    </div>


    <script type="text/javascript">
        $('.image_preview').each(function() {
            var v = $(this).val();
            if (v != '') {
                var id = $(this).attr('id');
                $('.thumbnail-preview.' + id).html("<img src='<?php echo DOMAIN . 'thumb/150x120/'; ?>" + v + "' />");
            }
        });

        $('.tab-list a').click(function() {
            var tab = $(this).attr('href');

            $('.tab-body').addClass('hide');
            $(tab).removeClass('hide');

            $('.tab-list li a').removeClass('active');
            $(this).addClass('active');

            return false;
        });

        var is_collapse_sidebar = 0;

        $('.toogle-menu-mobile').click(function() {
            if (is_collapse_sidebar == 0) {
                $('#left').addClass('toogle-menu-hide');
                $('#main').addClass('toogle-menu-hide');

                is_collapse_sidebar = 1;
            } else {
                $('#left').removeClass('toogle-menu-hide');
                $('#main').removeClass('toogle-menu-hide');

                is_collapse_sidebar = 0;
            }
        });


        var myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
            url: "<?php echo DOMAIN; ?>default/ajax/upload_dropzone_imgs",
            paramName: "file",
            maxFiles: 100,
            maxFilesize: 10, // MB
            addRemoveLinks: true,

            complete: function(file) {
                var t = this.files;
                dropzone_total = t.length;

                if (file.xhr.responseText != '') {
                    dropzone_current_no_uplload = dropzone_current_no_uplload + 1;
                    console.log('dropzone_action');
                    console.log(dropzone_action);

                    if (dropzone_action == 'add-image-multi')
                        add_more_img(file.xhr.responseText, '/' + file.xhr.responseText);

                    if (dropzone_action == 'insert_ckeditor') {
                        insert_images('/' + file.xhr.responseText + ',');
                    }

                    if (dropzone_current_no_uplload >= dropzone_total) {
                        dropzone_current_no_uplload = 0;
                        dropzone_total = 0;
                        dropzone_action = '';
                        current_edior_id = '';

                        $('#modal-dropzone').modal('hide');
                        this.removeAllFiles(true);
                    }
                }
            },
            accept: function(file, done) {
                done();
            }
        });
    </script>


</body>

</html>