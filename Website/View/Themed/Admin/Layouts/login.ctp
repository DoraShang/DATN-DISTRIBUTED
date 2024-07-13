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
    </head>

    <body>
        <div class="row-fluid">
            <div class="span12">
                <div class="span3 offset4"style="min-width: 330px;">
                    <div id="login" class="well">
                        <?php echo $content_for_layout; ?>
                    </div>

                    <script type="text/javascript">
                        var $ = jQuery.noConflict();
                        var h = $(window).height();
                        var lh = $('#login').height();
                        var p = ((h - lh) / 2) - 80;
                        jQuery('#login').css({'margin-top': p + 'px'});
                    </script>               
                </div>
            </div>
        </div>
    </body>
</html>