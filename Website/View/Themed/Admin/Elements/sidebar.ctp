<?php
$controller = $this->params['controller'];
$action = $this->params['action'];

// $role = explode((','), $admin['role']);
$role = [];
$modules = array();
foreach ($role as $v) {
    $t = explode('/', $v);
    $ta = $t[0];
    $tb = explode('_', $ta);

    $modules[] = $tb[0];
}

$modules = array_unique($modules);

$controller_require_action_2_add_class_active = array('admin_setting');

// if(isset($_GET['dev']))
// {
//     pr($role);
//     pr($modules);
// }
?>
<div id="left" class="full ui-sortable ui-resizable">
    <?php foreach ($sidebar as $k => $v) : ?>
    <?php
        $module_group = '';

        foreach ($v as $dk => $dv) {
            if (is_array($dv)) {
                foreach ($dv as $kk => $vv) {
                    $t = explode('/', $vv);
                    $ta = $t[0];
                    $tb = explode('_', $ta);
                    $module_group = $tb[1];
                }
            } else {
                $t = explode('/', $dv);
                $ta = $t[0];
                $tb = explode('_', $ta);
                $module_group = $tb[1];
            }
        }
        ?>

    <?php if ($this->App->is_allowed($module_group, $modules, $admin)) { ?>
    <div class="subnav <?php echo $module_group; ?>">
        <div class="subnav-title">
            <a href="" class="toggle-subnav">
                <i class="icon-angle-down"></i>
                <span><?php echo $k;
                                unset($v['icon']); ?></span>
            </a>
        </div>
        <ul class="subnav-menu ">
            <?php foreach ($v as $key => $val) : ?>
            <?php if (is_array($val)) { ?>
            <?php
                            $temp = '';

                            foreach ($val as $kk => $vv) {
                                $t = explode('/', $vv);
                                $ta = $t[0];
                                $tb = explode('_', $ta);
                                $temp = $tb[1];
                            }
                            ?>
            <?php if ($this->App->is_allowed($temp, $modules, $admin)) { ?>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown"><?php echo $key; ?></a>
                <ul class="dropdown-menu">
                    <?php foreach ($val as $dkey => $dval) { ?>
                    <?php
                                            $arr = explode('/', $dval);
                                            $target_controller = isset($arr[0]) ? $arr[0] : '';
                                            $act = trim(end($arr), ' /');
                                            if ($this->App->is_allowed($act, $role, $admin)) {
                                            ?>
                    <?php if (in_array($target_controller, $controller_require_action_2_add_class_active)) { ?>
                    <li <?php if ($act == $action) echo 'class="active"'; ?>>
                        <?php } else { ?>
                    <li <?php if ($controller == $target_controller) echo 'class="active"'; ?>>
                        <?php } ?>
                        <a rel="<?php echo $act; ?>" href="<?php echo DOMAINAD . $dval; ?>">
                            <?php echo $dkey; ?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php } else { ?>
            <?php
                            $buff = explode('/', $val);
                            $target_controller = isset($buff[0]) ? $buff[0] : '';
                            $act = trim(end($buff), ' /');

                            $tb = explode('_', $target_controller);
                            $temp = $tb[1];

                            if ($this->App->is_allowed($act, $role, $admin)) {
                            ?>
            <?php if (in_array($target_controller, $controller_require_action_2_add_class_active)) { ?>
            <li <?php if ($act == $action) echo 'class="active"'; ?>>
                <?php } else { ?>
            <li <?php if ($controller == $target_controller) echo 'class="active"'; ?>>
                <?php } ?>
                <a rel="<?php echo $act; ?>" href="<?php echo DOMAINAD . $val; ?>">
                    <?php echo $key; ?>
                </a>
            </li>
            <?php } ?>
            <?php } ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php } ?>
    <?php endforeach; ?>
</div>