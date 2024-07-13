<div class="container-fluid">
    <ul class="main-nav">
        <li><a href="<?php echo DOMAINAD; ?>" class="brand">Dashboard</a></li>
        <li><a href="<?php echo DOMAIN; ?>" target="_blank">Xem trang</a></li>
    </ul>
    <div class="user">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- {name} -->
                <em>Xin chào, </em><?php echo $admin['fullname']; ?>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="<?php echo DOMAINAD; ?>admin_dashboard/dashboard_logout"><i class="icon icon-off"></i>
                        Thoát</a></li>
            </ul>
        </div>
    </div>
</div>