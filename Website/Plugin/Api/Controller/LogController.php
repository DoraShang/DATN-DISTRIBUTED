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

class LogController extends ApiAppController
{
    private $res = array(
        'res' =>'err',
        'msg' =>'',
        'data' =>array(),
    );

    public function write_log()
    {
        $json = file_get_contents('php://input');

        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            // $obj['appID'] = $_GET['appID'];
            // $obj['from'] = $_GET['from'];
            
        } else {
            $obj = json_decode($json, TRUE);
        }
        $uid = isset($obj['uid']) && is_numeric($obj['uid']) ? $obj['uid'] : 1;
    
        $vehicle_id = isset($obj['data']['vehicle_id']) && is_numeric($obj['data']['vehicle_id']) ? $obj['data']['vehicle_id'] : 0;
        $content = isset($obj['data']['content'])  ? $this->Capp->removeXss($obj['data']['content']) : 'xxxxx';

        // if($uid == 0)
        // {
        //     $this->res['msg'] ='Người dùng không hợp lệ';
        //     $this->res_return($this->res);
        // }

        $save = array(
            'vehicle_id' => $vehicle_id,
            'content' => $content,
            'created' => time(),
        );
        $this->SensorLog->create();
        $this->SensorLog->save($save);

        $this->res['res'] ='done';
        $this->res['msg'] ='ghi log thành công';

        $this->res_return($this->res);
        
    }


}