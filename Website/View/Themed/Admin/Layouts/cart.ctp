<!DOCTYPE html>
<html>
    <head>
        <title>Quản trị</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex,nofollow" />
        <link rel="stylesheet" href="<?php echo DOMAIN; ?>css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo DOMAIN; ?>css/libs.css" />
        <link rel="stylesheet" href="<?php echo DOMAIN; ?>theme/admin/css/style.css" />
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/bootstrap.datepicker.js"></script>
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/jquery.number.js"></script>
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/bootstrap-maxlength.js"></script>        
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/ckfinder/ckfinder.js"></script>        
        <script type="text/javascript" src="<?php echo DOMAIN; ?>js/functions.js"></script>        
    </head>
    <body>
        <div class="row-fluid">
            <div class="span12">
                <?php echo View::element('navbar'); ?>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="row-fluid main margin-bottom margin-top">
            <div class="span12">
                <?php echo $content_for_layout; ?>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="row-fluid">
            <div class="span12 well margin-bottom-none">
                <?php echo View::element('footer'); ?>
            </div>
        </div>
    </body>
</html>
