<!DOCTYPE html>
<html lang="vi">

<head>
    <?php echo $this->Html->charset(); ?>
    <title>Teamplate</title>
    <?php
    $css = array(
        'teamplate/css/bootstrap.min.css',
        'teamplate/css/style.css',
        'teamplate/css/custom.css',
    );

    $scripts = array(
        'teamplate/js/jquery.min.js',
        'teamplate/js/bootstrap.min.js',
        'teamplate/js/sortable.js',
        'teamplate/js/functions.js',

    );

    ?>
    <?php foreach ($css as $v) : ?>
        <link rel="stylesheet" href="<?php echo DOMAIN; ?>theme/admin/<?php echo $v; ?>?v=<?php echo STYLE_VERSION; ?>" />
    <?php endforeach; ?>


    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>js/ckfinder/ckfinder.js"></script>
    <?php foreach ($scripts as $v) : ?>
        <script type="text/javascript" src="<?php echo DOMAIN; ?>theme/admin/<?php echo $v; ?>?v=<?php echo STYLE_VERSION; ?>"></script>
    <?php endforeach; ?>

    <script>
        const DOMAIN = '<?php echo DOMAIN ?>';
        const URL_IMAGE = '<?php echo DOMAIN . 'theme/admin/teamplate/image/' ?>';
    </script>

</head>

<body>

    <?php echo $this->fetch('content'); ?>

</body>

</html>