<div class="login-wrap">
    <div class="container">
        <div class="login-form">
            <form action="<?php echo DOMAIN; ?>default/node/login" id="login-form" method="post">
                <div class="login-img">
                    <img src="<?php echo $theme_directory; ?>/img/logo.png" alt="">
                </div>
                <div class="form-group">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16.6666 17.5V15.8333C16.6666 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66665C5.78259 12.5 4.93474 12.8512 4.30962 13.4763C3.6845 14.1014 3.33331 14.9493 3.33331 15.8333V17.5"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M10 9.16667C11.841 9.16667 13.3334 7.67428 13.3334 5.83333C13.3334 3.99238 11.841 2.5 10 2.5C8.15907 2.5 6.66669 3.99238 6.66669 5.83333C6.66669 7.67428 8.15907 9.16667 10 9.16667Z"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="text" class="form-control username" name="username" placeholder="Tên đăng nhập"
                        required>
                </div>
                <div class="form-group">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.8333 9.16663H4.16667C3.24619 9.16663 2.5 9.91282 2.5 10.8333V16.6666C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6666V10.8333C17.5 9.91282 16.7538 9.16663 15.8333 9.16663Z"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M5.83331 9.16663V5.83329C5.83331 4.72822 6.2723 3.66842 7.0537 2.88701C7.8351 2.10561 8.89491 1.66663 9.99998 1.66663C11.105 1.66663 12.1649 2.10561 12.9463 2.88701C13.7277 3.66842 14.1666 4.72822 14.1666 5.83329V9.16663"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="password" class="form-control password" name="password" placeholder="Mật khẩu"
                        required>
                </div>
                <button type="submit" class="btn-login">
                    Đăng nhập
                </button>
                <div class="btn-register">
                    <a href="<?php echo DOMAIN; ?>register" class="tablinks">Đăng ký tài khoản</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php	 /* 	 ?>
<div class="main single">
    <div class="wrap">
        <div class="container-fluid">
            <div class="sign-in">
                <div class="tab">
                    <a href="<?php echo DOMAIN; ?>register" class="tablinks " onclick="openCity(event, 'London')">Đăng
                        ký</a>
                    <a class="bg-primary tablinks" onclick="openCity(event, 'Paris')"
                        id="defaultOpen"><?php echo $this->App->t_a('home_label_45'); ?></a>
                </div>

                <div id="Paris" class="tabcontent tabcontent1">
                    <form action="<?php echo DOMAIN; ?>default/node/login" method="post" autocomplete="off"
                        id="frmlogin">

                        <div class="user-img">
                            <img src="<?php echo DOMAIN; ?>theme/default/img/logo2.svg" alt="" />
                        </div>

                        <div class="input-wrap">
                            <div class="icon-input">
                                <img src="theme/Default/img/Group 83.svg" alt="">
                            </div>
                            <input type="text" class="signup_input username" name="username" required="required"
                                placeholder="<?php echo $this->App->t('contact_label_28'); ?>" readonly
                                onfocus="this.removeAttribute('readonly');" />
                            <?php echo $this->App->adm_link('lang','contact_label_28','text'); ?>
                        </div>

                        <div class="input-wrap">
                            <div class="icon-input">
                                <img src="theme/Default/img/Frame.svg">
                            </div>
                            <input type="password" name="password" required="required"
                                placeholder="<?php echo $this->App->t('contact_label_29'); ?>" id="password_in"
                                class="signup_input pass" onfocus="black2()" onblur="white2()" />
                            <?php echo $this->App->adm_link('lang','contact_label_29','text'); ?>
                            <div class="showhide-icon">
                                <img src="theme/Default/img/eye.svg" onclick="passwordShow2()" id="showEye2" alt="">
                                <img src="theme/Default/img/eye-svgrepo-com.svg" onclick="passwordHide2()" id="hideEye2"
                                    alt="">
                            </div>
                        </div>

                        <div class="col-sm-12 text-center">
                            <input type="submit" class="login_submit" name="submit"
                                value="<?php echo $this->App->t('home_label_45'); ?>" />
                            <?php echo $this->App->adm_link('lang', 'home_label_45'); ?>
                        </div>

                        <div class="re-take">
                            <span>Bạn quên mật khẩu? <a href="#">Lấy lại mật khẩu</a></span>
                        </div>

                        <div class="sign-with">
                            <div class="social">
                                <div class="icon-social">
                                    <img src="theme/Default/img/Union.svg" alt="">
                                </div>
                                <div class="text-social">
                                    Google
                                </div>
                            </div>
                            <div class="social">
                                <div class="icon-social">
                                    <img src="theme/Default/img/fb.svg" alt="">
                                </div>
                                <div class="text-social">
                                    Facebook
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
*/ ?>

<?php	 
$scripts = array(
    // 'js/wow.min.js',
    // 'js/jquery.datetimepicker.full.min.js',
    // 'js/jquery_number.js',
    // 'js/typeaheap.js',
    'js/constant.js',
    'js/user.js',
    // 'js/app.js',
    // 'js/test.js'
);
?>
<?php foreach ($scripts as $v) : ?>
<script type="text/javascript" src="<?php echo $theme_directory; ?><?php echo $v; ?>?v=<?php echo time(); ?>">
</script>
<?php endforeach; ?>