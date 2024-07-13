<form action="" method="post" class="form-horizontal form-main">
    <div id="main">
        <div class="container-fluid">
            <?php echo $this->Admin->admin_head('Sửa tài khoản'); ?>
            <?php echo $this->Admin->admin_breadcrumb('Tài khoản'); ?>

            <?php echo $this->Session->flash(); ?>

            <div class="box">
                <div class="box-content">

                    <div class="control-group">
                        <label class="control-label">Họ và tên</label>
                        <div class="controls">
                            <?php echo $this->Form->input('User.fullname', array('type'=>'text','label'=>false,'div'=>false)); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Username</label>
                        <div class="controls">
                            <?php echo $this->Form->input('User.username', array('type'=>'text','readonly', 'label'=>false,'div'=>false)); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Mật khẩu</label>
                        <div class="controls">
                            <?php echo $this->Form->input('User.password', array('type'=>'text','label'=>false,'div'=>false, 'value'=>'')); ?>
                            <small><i>Bỏ trống nếu không thay đổi</i></small>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <?php echo $this->Form->input('User.email', array('type'=>'text','label'=>false,'div'=>false)); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Điện thoại</label>
                        <div class="controls">
                            <?php echo $this->Form->input('User.phone', array('type'=>'text','label'=>false,'div'=>false)); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">&nbsp;</label>
                        <div class="controls">
                            <input type="submit" name="submit" value="Xác nhận" class="btn btn-primary" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>