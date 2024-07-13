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

class AdminAccountController extends AdminAppController
{
    public $uses = array('User', 'Admin', 'News');
    
    public function account_list()
    {
        $this->_role('account_list');
        
        $type = isset($_GET['type']) && is_numeric($_GET['type']) ? $_GET['type'] : '';
        $parent_id = isset($_GET['mtype']) && is_numeric($_GET['mtype']) ? $_GET['mtype'] : '';

        $conn = array();
        $conn['NOT']['Admin.id'] = 1;

        if($type != '')
            $conn['Admin.type'] = $type;

        if($parent_id != '')
            $conn['Admin.parent_id'] = $parent_id;
        

        $this->paginate = array(
            'conditions'=> $conn,
            'limit' => 20,
            'order' => 'Admin.id DESC'
        );

        $total = $this->Admin->find('count', array('conditions'=>$conn));
        $this->set('total', $total);
        $this->set('type', $type);
        $this->set('parent_id', $parent_id);

        $this->data = $this->paginate('Admin');
    }

    public function account_add() {
        $this->_role('account_add');
        if ($this->data) {
            $data = $this->data['Admin'];
            $data['password'] = md5($data['password']);
            $data['role'] = (isset($this->data['role']) && is_array($this->data['role']) && count($this->data['role']) > 0) 
                            ? implode(',', $this->data['role']) : '';
            
            $this->Admin->save($data);

            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect(DOMAINAD . 'admin_account/account_list');
        }
    }

    public function account_edit($id = null) {
        $this->_role('account_edit');

        if ($this->data) {
            $data = $this->data['Admin'];
            if($data['password'] != '')
                $data['password'] = md5($data['password']);
            else
                unset($data['password']);
            
            $data['role'] = (isset($this->data['role']) && is_array($this->data['role']) && count($this->data['role']) > 0) 
                            ? implode(',', $this->data['role']) : '';

            $this->Admin->id = $id;
            $this->Admin->save($data);

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Admin->findById($id);
    }

    public function account_delete($id = null) {

        $this->_role('account_delete');
        $this->autoRender = false;
        
        $this->Admin->id = $id;
        $this->Admin->delete($id);

        $this->News->updateAll(
            array('News.admin_id' => 1),
            array('News.admin_id' => $id)
        );

        $this->Session->setFlash('Đã xóa');
        $this->redirect($this->referer());
    }
    
    public function account_profile(){
        $u = $this->Session->read('admin');
        $uid = $u['id'];

        if ($this->data) {
            $data = $this->data['Admin'];
            $current_pass = $data['current_password'];
            $new_pass = $data['new_password'];

            if(trim($new_pass) != '')
            {
                $current_pass = md5($current_pass);
                
                if ($current_pass != $u['password']) {
                    $this->Session->setFlash('Mật khẩu hiện tại không đúng');
                    $this->redirect($this->referer());
                    die;
                }
                else
                {
            		$data['password'] = md5($data['new_password']);
                	// $this->Admin->id = $u['id'];
                    // $this->Admin->saveField('password', $data['password']);
                }
            }
            else
            {
                unset($data['current_password']);
                unset($data['new_password']);
            }

            unset($data['id']);
            $update = array();

            foreach ($data as $k => $v) {
                $u[$k] = $v;
                $tmp = str_replace("'", '"', $v);
                $update[$k] = "'" . $tmp . "'";
            }

            $this->Admin->updateAll(
                $update,
                array('id' => $u['id'])
            );

            $this->Session->write('admin', $u);
            $this->Session->setFlash('Cập nhật thành công','success');
            $this->redirect($this->referer());
            die;
        }

        $this->data = $this->Admin->findById($u['id']);
    }
}