
<div class="alert alert-success"><strong>Lấy lại mật khẩu</strong></div>

<form  action="" method="post">
    <div class="row-fluid">
        <div class="span12">
            <input type="text" name="email" placeholder="Email của bạn" class="span12" />
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <input type="submit" class="btn btn-primary" value="Xác nhận" /> &nbsp;
            <a href="<?php echo DOMAINAD; ?>"><em>Quay lại trang đăng nhập</em></a>
        </div>
    </div>
</form>

<h4 class="text-error"><?php echo $this->Session->flash(); ?></h4>
