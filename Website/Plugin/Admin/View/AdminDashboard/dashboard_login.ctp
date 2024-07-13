
<div class="alert alert-success"><strong>Đăng nhập quản trị</strong></div>

<form  action="" method="post">
    <div class="row-fluid">
        <div class="span12">
            <input type="text" name="username" placeholder="Tên đăng nhập" class="span12" />
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <input type="password" name="password" placeholder="Mật khẩu đăng nhập" class="span12" />
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <input type="submit" class="btn btn-primary" value="Đăng nhập" /> &nbsp;
            <a href="<?php echo DOMAINAD; ?>admin_dashboard/dashboard_recover"><em>Quên mật khẩu ?</em></a>
        </div>
    </div>
</form>

<h4 class="text-error"><?php echo $this->Session->flash(); ?></h4>
