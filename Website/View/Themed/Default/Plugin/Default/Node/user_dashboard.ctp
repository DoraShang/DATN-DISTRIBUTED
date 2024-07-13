<div class="dashboard space-list">
    <div class="wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="sibar-dashboard">
                        <div class="user">
                            <?php $avata = $user['fullname'][0]; 	 ?>
                            <div class="avatar">
                                <div class="avt-name"><?php echo $avata; ?></div>
                            </div>
                            <div class="info">
                                <p class="name"><?php echo $user['fullname']?></p>
                                <p class="point">Thành viên</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <ul>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.8327 17.5V15.8333C15.8327 14.9493 15.4815 14.1014 14.8564 13.4763C14.2312 12.8512 13.3834 12.5 12.4993 12.5H7.49935C6.61529 12.5 5.76745 12.8512 5.14233 13.4763C4.5172 14.1014 4.16602 14.9493 4.16602 15.8333V17.5"
                                        stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M9.99935 9.16667C11.8403 9.16667 13.3327 7.67428 13.3327 5.83333C13.3327 3.99238 11.8403 2.5 9.99935 2.5C8.1584 2.5 6.66602 3.99238 6.66602 5.83333C6.66602 7.67428 8.1584 9.16667 9.99935 9.16667Z"
                                        stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <a href="<?php echo DOMAIN ?>user/dashboard" title="Thông tin tài khoản">Thông
                                    tin tài khoản</a>
                            </li>
                            <li>
                                <svg width="17" height="22" viewBox="0 0 17 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.5013 14.5846C9.09961 14.5846 9.58464 14.0996 9.58464 13.5013C9.58464 12.903 9.09961 12.418 8.5013 12.418C7.90299 12.418 7.41797 12.903 7.41797 13.5013C7.41797 14.0996 7.90299 14.5846 8.5013 14.5846Z"
                                        fill="#333" stroke="#333" stroke-width="0.5" />
                                    <path
                                        d="M7.83203 16.4186C7.83203 16.7871 8.13021 17.0853 8.4987 17.0853C8.86719 17.0853 9.16536 16.7871 9.16536 16.4186V13.9186C9.16536 13.5501 8.86719 13.252 8.4987 13.252C8.13021 13.252 7.83203 13.5501 7.83203 13.9186V16.4186Z"
                                        fill="#333" stroke="#333" stroke-width="0.5" />
                                    <path
                                        d="M16 21.25H16.25V21V8.5V8.25H16H1H0.75V8.5V21V21.25H1H16ZM14.9167 19.9167H2.08333V9.58333H14.9167V19.9167Z"
                                        fill="#333" stroke="#333" stroke-width="0.5" />
                                    <path
                                        d="M13.498 9.16667H13.748V8.91667V6C13.748 3.10485 11.3932 0.75 8.49805 0.75C5.60289 0.75 3.24805 3.10485 3.24805 6V8.91667V9.16667H3.49805H4.33138H4.58138V8.91667V6C4.58138 3.84057 6.33862 2.08333 8.49805 2.08333C10.6575 2.08333 12.4147 3.84057 12.4147 6V8.91667V9.16667H12.6647H13.498Z"
                                        fill="#333" stroke="#333" stroke-width="0.5" />
                                </svg>
                                <a href="<?php echo DOMAIN ?>user/pass">Đổi mật khẩu</a>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 8C11.1 8 12 7.1 12 6V2C12 0.9 11.1 0 10 0C8.9 0 8 0.9 8 2V6C8 7.1 8.9 8 10 8Z"
                                        fill="#333" />
                                    <path
                                        d="M17.1 2.9C16.8 2.6 16.5 2.5 16 2.5C15.2 2.5 14.5 3.2 14.5 4C14.5 4.4 14.7 4.8 14.9 5.1C16.2 6.4 16.9 8.1 16.9 10C16.9 13.9 13.8 17 9.9 17C6 17 2.9 13.9 2.9 10C2.9 8.1 3.7 6.3 5 5.1C5.3 4.8 5.5 4.4 5.5 4C5.5 3.2 4.8 2.5 4 2.5C3.6 2.5 3.2 2.7 2.9 2.9C1.1 4.7 0 7.2 0 10C0 15.5 4.5 20 10 20C15.5 20 20 15.5 20 10C20 7.2 18.9 4.7 17.1 2.9Z"
                                        fill="#333" />
                                </svg>
                                <a href="<?php echo DOMAIN ?>logout">Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="tab-info">
                        <div class="info-inner">
                            <h2 class="title">
                                Thông tin tài khoản
                            </h2>
                        </div>
                        <form id="user-info-form" method="POST">
                            <div style="margin-bottom: 20px">
                                <label>Họ tên <span style="color: #ff6108">*</span></label>
                                <input type="text" class="form-control" name="fullname"
                                    value="<?php echo $user['fullname']; ?>" required="">
                            </div>
                            <div style="margin-bottom: 20px">
                                <label>Tên đăng nhập</label>
                                <input disabled="" type="text" class="form-control"
                                    value="<?php echo $user['username']; ?>">
                            </div>
                            <div style="margin-bottom: 20px">
                                <label>Email <span style="color: #ff6108">*</span></label>
                                <input type="text" class="form-control" name="email"
                                    value="<?php echo $user['email']; ?>" required="">
                            </div>
                            <div style="margin-bottom: 20px">
                                <label>Số điện thoại <span style="color: #ff6108">*</span></label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?php echo $user['phone']; ?>" required="">
                            </div>
                            <button type="submit" class="btn-submit dashboard-button">Lưu thay
                                đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>