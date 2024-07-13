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

App::uses('CKEditor', 'Vendor');
App::uses('CKFinder', 'Vendor');
App::import('Vendor', 'Pagination', array('file' => 'Pagination.class.php'));

class NodeController extends DefaultAppController
{
    public function beforeRender()
    {
        parent::beforeRender();
        $category_root_id = 0;

        if (isset($this->custom_layout))
            $this->layout = $this->custom_layout;
    }

    public function upload_image()
    {
        $this->autoRender = false;


        $files = $_FILES;

        if (isset($_FILES['upload']['name'])) {
            $img = '';
            if (isset($_FILES['upload']['type']) && strpos($_FILES['upload']['type'], 'image') !== false) {
                $new_upload = array(
                    'name' => $_FILES['upload']['name'],
                    'type' => $_FILES['upload']['type'],
                    'tmp_name' => $_FILES['upload']['tmp_name'],
                    'error' => $_FILES['upload']['error'],
                    'size' => $_FILES['upload']['size'],
                );

                $img_name = time() . rand(0, 10) . rand(0, 10);

                $this->Upload->name = $img_name;
                $this->Upload->new = $new_upload;
                @$img = $this->Upload->Process();
            }

            $function_number = $_GET['CKEditorFuncNum'];
            $url = DOMAIN . 'app/webroot/uploads/images/' . $img;
            $message = '';
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
        }

        die;
    }

    public function user_change_pass()
    {
        $this->init_data();

        $this->User = ClassRegistry::init('User');

        if ($this->Session->check('user')) {
            $user = $this->Session->read('user');
            $this->set('user',$user);
            $res = array(
                'res' => 'err',
                'msg' => ''
            );
            $user = $this->Session->read('user');

            if ($this->data) {
                $this->autoRender = false;
                $data = $this->data;
                $pass = $this->removeXss($data['pass']);
                $repass = $this->removeXss($data['repass']);

                if ($pass == "" || $repass == "") {
                    $this->alert("Vui lòng nhập đủ thông tin", $this->referer());
                    die;
                }

                if ($pass != $repass) {
                    echo json_encode($res);
                    die;
                }

                $pass = md5($pass);

                $this->User->id = $user['id'];
                $this->User->saveField('password', $pass);

                $this->alert("Đã cập nhật thành công!", $this->referer());
                // $res['res'] = 'done';
                // echo json_encode($res);
                die;
            }

            $this->render('user_change_pass');
        } else {
            $this->redirect(DOMAIN);
            die;
        }
    }

    public function recover()
    {
        $msg = isset($_GET['msg']) && $_GET['msg'] == 3 ? $_GET['msg'] : '';
        $uid = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : '';

        if ($msg != '' && $uid != '') {
            $check = $this->Customer->findById($uid);
            $r = $check['Customer']['recover_password'];

            $this->Customer->id = $uid;
            $this->Customer->saveField('password', $r);
        }

        $this->render('user_recover');
    }

    public function user_dashboard()
    {
        $this->init_data();

        if ($this->Session->check('user')) {
            $user = $this->Session->read('user');
            $this->set('user',$user);

            $this->render('user_dashboard');
        } 
            

        if($this->data)
        {

            $this->User = ClassRegistry::init('User');
            if ($this->Session->check('user')) {
                $user = $this->Session->read('user');

                if ($this->data) {
                    $data = $this->data;
                    $fullname = $this->removeXss($data['fullname']);
                    $phone = $this->removeXss($data['phone']);
                    $email = $this->removeXss($data['email']);
                
                    // check email
                    $check = $this->User->find('all',[
                        'conditions' => [
                            'email' => $email,
                            'id NOT' => $user['id']
                        ]
                    ]);
                    if(is_array($check) && count($check) > 0)
                    {
                        $this->alert('Email đã được sử dụng.', $this->referer());
                        die();
                    }
                    // check so điện thoại
                    $check2 = $this->User->find('all',[
                        'conditions' => [
                            'phone' => $phone,
                            'id NOT' => $user['id']
                        ]
                    ]);
                    if(is_array($check2) && count($check2) > 0)
                    {
                        $this->alert('Số điện thoại đã được sử dụng.', $this->referer());
                        die();
                    }

                    $save = array(
                        'fullname' => $fullname,
                        'phone' => $phone,
                        'email' => $email,
                    );

                    $this->User->id = $user['id'];
                    $this->User->save($save);

                    $check = $this->User->findById($user['id']);

                    $this->Session->write('user', $check['User']);

                    if (isset($data['ajax']) && $data['ajax'] == 1) {
                        echo json_encode(1);
                        die;
                    }
                    $this->alert('Bạn thay đổi thông thành công.', $this->referer());
                    die();
                }
            } 
            
            $this->redirect(DOMAIN);
            die;
        }




    }

    public function logout()
    {
        if ($this->Session->check('user'))
            $this->Session->delete('user');

        $this->redirect(DOMAIN);
    }

    public function login()
    {
        $this->User = ClassRegistry::init('User');
    
        if ($this->Session->check('user'))
            $this->redirect(DOMAIN );

        if ($this->data) {
            $this->autoRender = false;
           
            $res = array(
                'res' => 'err',
                'msg' => '',
            );
            $data = $this->data;
            $username = $data['username'];
            $pass = $data['password'];
         
            if ($username == '' || $pass == '') {
                $this->alert('Vui lòng nhập đủ thông tin', $this->referer());
                die;
            }

            $username = $this->removeXss($username);
            $pass = md5($pass);

            $check = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $username,
                    'User.password' => $pass
                )
            ));

            if (is_array($check) && count($check) > 0) {
                $this->autoRender = false;
                $this->Session->write('user', $check['User']);
               
                // $redirect = DOMAIN . 'user/dashboard';
                // if(isset($_GET['r']))
                //     $redirect = $_GET['r'];

                // $this->redirect($redirect);

                // $this->redirect($this->referer());
                $res['res'] = 'done';
                $res['msg'] = $username;

                $this->alert('Bạn đã đăng nhập thành công.', $this->referer());

                echo json_encode($res);
                die;
            }
            // echo json_encode($res);
            // die;
            $this->alert('Email / Password không đúng', $this->referer());

        }
        $this->render('user_login');
    }

    public function register()
    {
        // $this->Customer = ClassRegistry::init('Customer');
        $parent_id = isset($_GET['r']) && is_numeric($_GET['r']) ? $_GET['r'] : "";

        if ($this->data) {
            $data = $this->data;

            $email = $this->removeXss($data['email']);
            $phone = $this->removeXss($data['phone']);
            $fullname = $this->removeXss($data['fullname']);
            // $address = $this->removeXss($data['address']);
            $username = $this->removeXss($data['username']);
            // $role = $this->removeXss($data['role']);
            // $fullnamebtk = $this->removeXss($data['fullnamebtk']);
            // $emailbtk = $this->removeXss($data['emailbtk']);
            // $phonebtk = $this->removeXss($data['phonebtk']);
            // $content = $this->removeXss($data['content']);
            $type = isset($obj['type']) ? $obj['type'] : 1;

            $pass = $data['password'];

            // if($fullname == '' || $email == '' || $pass == '' || $username == '')
            // {
            //     $this->alert('Vui lòng nhập đủ thông tin', $this->referer());
            //     die;
            // }

            if ($pass == '' || $username == '' || $email == '' || $phone == '' || $fullname == '') {
                $this->alert('Vui lòng nhập đủ thông tin', $this->referer());
                die;
            }

            $check = $this->User->find('first', array(
                'conditions' => array(
                    'OR' => array(
                        'User.username' => $username,
                        // 'User.email'=>$email
                    )
                )
            ));

            if (is_array($check) && count($check) > 0) {
                $this->alert('Thông tin đã được đăng ký. Vui lòng lựa chọn Tên đăng nhập và Email khác!', $this->referer());
                die;
            }

            $password = md5($pass);

            $save = array(
                'fullname'=>$fullname,
                'password' => $password,
                'phone'=>$phone,
                'email'=>$email,
                // 'address'=>$address,
                'username' => $username,
                'parent_id' => $parent_id,
                // 'role'=>$role,
                // 'fullnamebtk'=>$fullnamebtk,
                // 'emailbtk'=>$emailbtk,
                // 'phonebtk'=>$phonebtk,
                // 'content'=>$content,
                // 'status'=>0,
                'type' => $type
            );

            $this->User->create();
            $this->User->save($save);
            
            $this->alert('Đã đăng ký tài khoản thành công!', $this->redirect(DOMAIN . 'login'));
            die;

        }

        $this->render('user_register');
    }

    public function page404()
    {
        $this->render('page404');
    }
 
}