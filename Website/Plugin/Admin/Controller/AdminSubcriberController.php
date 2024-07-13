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


class AdminSubcriberController extends AdminAppController
{
    public $uses = array('Subcriber');
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
    }
    
    public function index()
    {
        $this->paginate = array(
            'limit' => 10,
            'order' => 'Subcriber.id DESC',
        );
        
        $this->data = $this->paginate('Subcriber');
    }

    
   public function delete($id = null)
   {
       $this->autoRender = FALSE;
       $this->Subcriber->delete($id);
       $this->redirect($this->referer());
   }
}