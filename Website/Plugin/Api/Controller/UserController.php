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

use Illuminate\Support\Arr;

App::uses('AppController', 'Controller');

class UserController extends ApiAppController
{
    //Danh sách components
    // public $components = array('Cookie', 'Session', 'Upload', 'Paginator');

    private $res = array(
        'res' =>'err',
        'msg' =>'',
        'data' =>array(),
    );

    public function test_api()
    {
        echo 'xxxx ahihi';
    }
    
    public function register()
    {
        $json = file_get_contents('php://input');

        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            // $obj['appID'] = $_GET['appID'];
            // $obj['from'] = $_GET['from'];
            
        } else {
            $obj = json_decode($json, TRUE);
        }
        $fullname = isset($obj['fullname']) ? $obj['fullname'] : '';
        $phone = isset($obj['phone']) ? $obj['phone'] : '';
        $email = isset($obj['email']) ? $obj['email'] : '';
        $type = isset($obj['type']) ? $obj['type'] : 1;
        $username = isset($obj['username']) ? $obj['username'] : '';
        $password = isset($obj['password']) ? $obj['password'] : '';

        if($fullname == '')
        {
            $this->res['msg'] = 'Vui lòng nhập tên người dùng';
            $this->res_return($this->res);
        }
        if($phone == '')
        {
            $this->res['msg'] = 'Vui lòng nhập số điện thoại';
            $this->res_return($this->res);
        }
        if($email == '')
        {
            $this->res['msg'] = 'Vui lòng nhập email';
            $this->res_return($this->res);
        }
        if($username == '')
        {
            $this->res['msg'] = 'Vui lòng nhập tên đăng nhập';
            $this->res_return($this->res);
        }
        if($password == '')
        {
            $this->res['msg'] = 'Vui lòng nhập mật khẩu';
            $this->res_return($this->res);
        }

        // check user || phone || email 
        $check_user = $this->User->find('first',array(
            'conditions' => array(
                'OR' => array(
                    'username' => $username,
                    'phone' => $phone,
                    'email' => $email,
                )

            )
        ));

        if(is_array($check_user) && count($check_user) > 0)
        {
            $this->res['msg'] = 'Username hoặc số điện thoại hoặc email đã được sử dụng, vui lòng thử lại';
            $this->res_return($this->res);
        }

        $hash_pass = md5($password);
        $save = array(
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email,
            'type' => $type,
            'username' => $username,
            'password' => $hash_pass,
            'created' => time(),
            'modified' => time(),
        );

        $this->User->create();
        $this->User->save($save);

        $this->res['res'] ='done';
        $this->res['msg'] ='Đăng ký thành công';
        
        $this->res_return($this->res);
    }

    public function login()
    {
        $json = file_get_contents('php://input');

        if (isset($_GET['dev']) && $_GET['dev'] == 1) {

            $obj['username'] = $_GET['username'];
            $obj['password'] = $_GET['password'];

        } else {
            $obj = json_decode($json, TRUE);
         
        }
       
        $username = isset($obj['username']) ? $obj['username'] : '';
        $password = isset($obj['password']) ? $obj['password'] : '';

        if($username == '')
        {
            $this->res['msg'] = 'Vui lòng nhập tên đăng nhâp';
            $this->res_return($this->res);
        }
        if($password == '')
        {
            $this->res['msg'] = 'Vui lòng nhập mật khẩu';
            $this->res_return($this->res);
        }
        $hash_pass = md5($password);

        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.username' => $username,
                'User.password' => $hash_pass,
            )
        ));

        if(!is_array($user) || count($user) <= 0)
        {
            $this->res['msg'] = 'Tên đăng nhập hoặc mật khẩu không đúng';
            $this->res_return($this->res);
        }

        $time = time();
        $token_arr = array(
            'uid' => $user['User']['id'],
            'time' => $time,
        );

        $check_sum = '';
        $token_str = json_encode($token_arr);
        $token = $this->Capp->encrypt_string($token_str);
        $user['User']['token'] = $token;
        $user['User']['check_sum'] = $check_sum;

        $this->User->id = $user['User']['id'];
        $this->User->saveField('token', $token);

        $this->res['msg'] = 'Đăng nhập thành công';
        $this->res['data'] = $user;

        $this->res_return($this->res);

    }
    public function log_out()
    {
        $json = file_get_contents('php://input');

        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            // $obj['appID'] = $_GET['appID'];
            // $obj['from'] = $_GET['from'];
        } else {
            $obj = json_decode($json, TRUE);
         
        }
        

        echo json_encode($this->res);
        die();
    }
}