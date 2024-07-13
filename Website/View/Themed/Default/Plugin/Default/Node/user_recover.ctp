<div class="archive" id="breadcrumb">
    <div class="wrap">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="block-breadcrumb-mb">
                    <ol itemscope itemtype="http://schema.org/BreadcrumbList">
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="<?php echo DOMAIN; ?>">
                                <span itemprop="name">Trang chủ</span>
                            </a>
                            <i class="fa fa-angle-right"></i>
                            <meta itemprop="position" content="1">
                        </li>

                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="<?php echo DOMAIN; ?>login">
                                <span itemprop="name">Lấy lại mật khẩu</span>
                            </a>
                            <i class="fa fa-angle-right"></i>
                            <meta itemprop="position" content="2">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main single">
    <div class="<?php echo isset($_GET['msg']) && ($_GET['msg'] == 2 || $_GET['msg'] == 3) ? 'wrap' : 'wrap-form';  ?> ">
        <div class="row">
            <div class="col-sm-12">
                    <div id="modal-login">
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 2) { ?>
                    <div class="res-msg res-msg-done">
                        <?php echo $this->App->t_a('header_label_3'); ?>
                    </div>
                    <?php } elseif(isset($_GET['msg']) && $_GET['msg'] == 3) { ?>
                    <div class="res-msg res-msg-done">
                        <?php echo $this->App->t_a('header_label_5'); ?>
                    </div>
                    <?php } else { ?>
                    <div class="logo2">
                        <img src="<?php echo DOMAIN; ?>theme/default/img/logo2.svg" alt="" />
                    </div>
                    <form action="<?php echo DOMAIN; ?>default/contact/recover" method="post" autocomplete="off"
                        class="frmlogin frmrecover">
                        <div class="input-text input-text-user">
                            <span></span>
                            <input type="text" name="email" required="required" placeholder="Email của bạn" />
                        </div>


                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 1) { ?>
                        <div class="res-msg">
                            <?php echo $this->App->t_a('header_label_4'); ?>
                        </div>
                        <?php }  ?>

                        <input type="submit" name="submit" value="Gửi mật khẩu"/>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>