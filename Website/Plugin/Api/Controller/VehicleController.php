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

class VehicleController extends ApiAppController
{
    private $res = array(
        'res' =>'err',
        'msg' =>'',
        'data' =>array(),
    );

    public function get_ip_socket()
    {
        $json = file_get_contents('php://input');

        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            // $obj['appID'] = $_GET['appID'];
            // $obj['from'] = $_GET['from'];
            
        } else {
            $obj = json_decode($json, TRUE);
        }
        $uid = isset($obj['uid']) && is_numeric($obj['uid']) ? $obj['uid'] : 1;
        $vehicle_id = isset($obj['vehicle_id']) && is_numeric($obj['vehicle_id']) ? $obj['vehicle_id'] : 1;

        // if($uid == 0)
        // {
        //     $this->res['msg'] ='Người dùng không hợp lệ';
        //     $this->res_return($this->res);
        // }

        $vehicle = $this->Vehicle->findById($vehicle_id);
        if(!is_array($vehicle) || count($vehicle) < 0 )
        {
            $this->res['msg'] ='Xe không tồn tại hoặc chưa được đăng ký';
            $this->res_return($this->res);
        }
        $url = $vehicle['Vehicle']['ip_socket'];
        if($url == null || $url == '')
        {
            $this->res['msg'] ='Socket chưa được host';
            $this->res_return($this->res);
        }
        $this->res['res'] ='done';
        $this->res['msg'] ='Lấy thành công';
        $this->res['data'] = ['url' => $url];

        $this->res_return($this->res);
    }

    public function push_ip_socket()
    {
        $json = file_get_contents('php://input');

        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            // $obj['appID'] = $_GET['appID'];
            // $obj['from'] = $_GET['from'];
            
        } else {
            $obj = json_decode($json, TRUE);
        }
        // $uid = isset($obj['uid']) && is_numeric($obj['uid']) ? $obj['uid'] : 1;
        $vehicle_id = isset($obj['vehicle_id']) && is_numeric($obj['vehicle_id']) ? $obj['vehicle_id'] : 1;
        $vehicle_ip = isset($obj['vehicle_ip']) ? $this->Capp->removeXss($obj['vehicle_ip']) : 'http://localhost';

        
        $vehicle = $this->Vehicle->findById($vehicle_id);
        if(!is_array($vehicle) || !count($vehicle) <= 0)
        {   
            $this->res['msg']= 'Xe chưa được đăng ký';
            $this->res_return($this->res);
        }
        $this->Vehicle->id = $vehicle['Vehicle']['id'];
        $this->Vehicle->saveField('ip_socket', $vehicle_ip);

        $this->res['res'] ='done';
        $this->res['msg'] ='Đã cập nhật IP';

        $this->res_return($this->res);
        
    }
}