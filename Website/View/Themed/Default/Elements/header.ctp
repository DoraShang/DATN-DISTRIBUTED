<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-language" content="vi" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

    <title><?php echo 'Distributed'; ?></title>

    <meta name="robots" content="noindex,nofollow" />
    <?php	 /* 	 ?>
    <?php /* if (Configure::read('debug') == 2) : ?>
    <?php else : ?>
    <?php echo $robots_for_layout; ?>
    <?php endif; * / ?>

    <?php echo $keyword_for_layout; ?>
    <?php echo $description_for_layout; ?>
    <?php echo $og_for_layout; ?>*/ ?>

    <?php
    $css = array(
        // 'css/animate.css',
        // 'css/datetimepicker.min.css',
        'css/style.css',
        // 'css/home.css'
    );

    $scripts = array(
        // 'js/wow.min.js',
        // 'js/jquery.datetimepicker.full.min.js',
        // 'js/jquery_number.js',
        // 'js/typeaheap.js',
        // 'js/map.js'
        // 'js/test.js'
    );

    $theme_directory = isset($theme) ? strtolower($theme) : 'default';
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <?php foreach ($css as $v) : ?>
    <link rel="stylesheet"
        href="<?php echo DOMAIN; ?>theme/<?php echo $theme_directory; ?>/<?php echo $v; ?>?v=<?php echo time(); ?>" />
    <?php endforeach; ?>

    <?php foreach ($scripts as $v) : ?>
    <script type="text/javascript"
        src="<?php echo DOMAIN; ?>theme/<?php echo $theme_directory; ?>/<?php echo $v; ?>?v=<?php echo time(); ?>">
    </script>
    <?php endforeach; ?>

    <link rel="icon" type="image/png" href="<?php echo DOMAIN . 'theme/' .$theme_directory.'/img/logo.png'; ?>" />

    <?php
    $body = isset($is_mobile) && $is_mobile == 1 ? 'mb' : 'pc';

    if (isset($is_news)) $body .= ' news';
    if (isset($is_product)) $body .= ' product';
    if (isset($is_page)) $body .= ' page';
    if (isset($is_cart)) $body .= ' cart';
    if (isset($is_single)) $body .= ' single';

    if ($this->Session->check('admin')) $body .= ' admin';

    $adm_logged = false;

    if ($this->Session->check('admin') && $is_mobile == 0) {
        $adm_logged = true;
        $str = Configure::read('active_editor') == 1 ? 'Tắt chế độ sửa' : 'Bật chế độ sửa';
    }
    ?>

    <script type="text/javascript">
    const DOMAIN = "<?php echo DOMAIN; ?>";
    </script>
</head>

<body class="<?php echo $body; ?>">
    <div class="fix-fixed"></div>
    <!-- header -->
    <div class="vehicle-main">
        <div class="header">
            <div class="container-fluid">
                <div class="d-flex align-center justify-between">
                    <div class="logo">
                        <img src="<?php echo DOMAIN .'theme/'. $theme_directory; ?>/img/logo.png" alt="">
                    </div>
                    <div class="user-infor d-flex align-center">
                        <svg width="59" height="49" viewBox="0 0 59 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M29.2461 12.25C27.4385 12.25 25.6715 12.699 24.1685 13.5403C22.6655 14.3816 21.4941 15.5773 20.8024 16.9763C20.1106 18.3753 19.9297 19.9147 20.2823 21.3999C20.6349 22.8851 21.5054 24.2493 22.7836 25.32C24.0617 26.3908 25.6902 27.12 27.4631 27.4154C29.2359 27.7108 31.0736 27.5592 32.7436 26.9797C34.4136 26.4002 35.841 25.4189 36.8452 24.1598C37.8495 22.9008 38.3855 21.4205 38.3855 19.9062C38.3855 17.8757 37.4226 15.9283 35.7086 14.4925C33.9946 13.0566 31.67 12.25 29.2461 12.25ZM29.2461 24.5C28.1615 24.5 27.1013 24.2306 26.1995 23.7258C25.2978 23.221 24.5949 22.5036 24.1799 21.6642C23.7648 20.8248 23.6562 19.9012 23.8678 19.0101C24.0794 18.119 24.6017 17.3004 25.3686 16.658C26.1355 16.0155 27.1126 15.578 28.1763 15.4008C29.24 15.2235 30.3426 15.3145 31.3446 15.6622C32.3466 16.0099 33.203 16.5987 33.8056 17.3541C34.4081 18.1095 34.7297 18.9977 34.7297 19.9062C34.7281 21.1242 34.1498 22.2918 33.1218 23.153C32.0938 24.0142 30.6999 24.4986 29.2461 24.5Z"
                                fill="white" />
                            <path
                                d="M29.2458 3.0625C24.1845 3.0625 19.2369 4.31979 15.0286 6.67537C10.8203 9.03095 7.54034 12.379 5.60347 16.2962C3.66661 20.2134 3.15983 24.5238 4.14724 28.6822C5.13465 32.8407 7.57189 36.6605 11.1508 39.6586C14.7296 42.6567 19.2894 44.6984 24.2534 45.5256C29.2174 46.3528 34.3628 45.9282 39.0388 44.3057C43.7148 42.6831 47.7115 39.9354 50.5234 36.41C53.3353 32.8847 54.8361 28.7399 54.8361 24.5C54.8285 18.8164 52.1299 13.3674 47.3325 9.34847C42.535 5.32955 36.0305 3.0689 29.2458 3.0625ZM18.2786 40.389V38.2812C18.2802 37.0633 18.8584 35.8957 19.8864 35.0345C20.9145 34.1733 22.3083 33.6888 23.7622 33.6875H34.7295C36.1833 33.6888 37.5772 34.1733 38.6052 35.0345C39.6332 35.8957 40.2115 37.0633 40.2131 38.2812V40.389C36.8848 42.0171 33.0999 42.875 29.2458 42.875C25.3917 42.875 21.6068 42.0171 18.2786 40.389ZM43.8551 38.1676C43.8188 36.159 42.8419 34.2428 41.1349 32.8319C39.4278 31.421 37.1275 30.6284 34.7295 30.625H23.7622C21.3642 30.6284 19.0638 31.421 17.3568 32.8319C15.6498 34.2428 14.6728 36.159 14.6365 38.1676C11.3218 35.6882 8.98422 32.4237 7.93338 28.8066C6.88254 25.1895 7.16798 21.3903 8.75191 17.912C10.3358 14.4338 13.1435 11.4407 16.8032 9.32894C20.4629 7.21719 24.802 6.08645 29.2458 6.08645C33.6897 6.08645 38.0287 7.21719 41.6884 9.32894C45.3481 11.4407 48.1558 14.4338 49.7397 17.912C51.3237 21.3903 51.6091 25.1895 50.5583 28.8066C49.5074 32.4237 47.1699 35.6882 43.8551 38.1676Z"
                                fill="white" />
                        </svg>
                        <span>
                            <a href="<?php echo DOMAIN . 'user/dashboard'?>" style="color: white">
                                <?php echo isset($user) ? $user['fullname'] : ''?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>