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

class SensorLog extends AppModel
{
    public $name = 'SensorLog';
    public $useTable = 'sensor_logs';
    
    // public function check_exist_slug($slug, $exclude_news_id = null)
    // {
    //     if($news_id == null)
    //         $check = $this->findBySlug($slug);
    //     else
    //         $check = $this->find('first', array(
    //             'conditions'=>array(
    //                 'NOT'=>array(
    //                     'id'=>$exclude_news_id
    //                 )
    //             )
    //         ));
        
    //     return (is_array($check) && count($check)>0) ? true : false;
    // }
}