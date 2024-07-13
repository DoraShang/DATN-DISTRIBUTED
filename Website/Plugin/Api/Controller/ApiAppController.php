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
App::uses('AppController', 'Controller');

class ApiAppController extends AppController
{
    //Danh sách components
    public $components = array('Capp', 'RequestHandler', 'Upload');

    //Các bảng được sử dụng trong chương trình
    public $uses = array(
        'Api.User',
        'Api.Vehicle',
        'Api.SensorLog',
        'Api.Request',
    );
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->autoRender = false;
    }

    public function test_api()
    {
        echo 'xxxx ahihi';
    }

    public function res_return($data)
    {
        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            echo '<pre>';
            print_r($data);
            echo '/<pre>';
            die();
        }else {
            echo json_encode($data);
            die();
        }
    }

}