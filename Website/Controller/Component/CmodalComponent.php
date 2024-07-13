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

class CmodalComponent extends Component
{
    public function initialize(Controller $Controller) 
    {
        $this->Controller = $Controller;
    }
    
    public function getQueryParams($type)
    {
        $this->Controller->cModalName = $this->compile_modal_name($type);
        $this->Controller->cActionName = $this->compile_action_name($type);
        
        return array(
            'action' =>  $this->Controller->cActionName,
            'modal'=>  $this->Controller->cModalName
        );
    }
    
    public function compile_modal_name($data)
    {
        if(preg_match('/_/', $data))
        {
            $data = explode('_', $data);
        }
        
        if(is_array($data))
        {
            foreach($data as $k=>$v)
            {
                $data[$k] = ucfirst($v);
            }
            
            return implode('', $data);
        }
        
        return ucfirst($data);
    }
    
    public function compile_action_name($type)
    {
        $this->Controller->cBuff = array();
        
        foreach($this->Controller->action_array as $k => $v)
            $this->Controller->cBuff[] = $k;
        
        return in_array($type, $this->Controller->cBuff) ? $type . '_' . $this->Controller->action_array[$type] : null;
    }
}