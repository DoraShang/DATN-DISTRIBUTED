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

App::import('Vendor', 'phpexcel');
class AdminCustomerController extends AdminAppController
{
    public $uses = array('User', 'Admin');

    public function customer_list()
    {
        
        $this->paginate = array(
            'limit' => 10,
            'order' => 'User.id DESC'
        );

        $this->data = $this->paginate('User');
    }

    public function customer_add()
    {
        // $this->check_permission('account');
        if ($this->data) {
            $data = $this->data['Customer'];
            $data['password'] = md5($data['password']);
            // $data['role'] = (is_array($this->data['role']) && count($this->data['role']) > 0) ? implode(',', $this->data['role']) : '';

            $data['type'] = 'ship';

            $this->User->save($data);

            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->referer());
        }
    }

    public function customer_edit($id = null)
    {
        // $this->check_permission('account');
        if ($this->data) {
            $data = $this->data['Customer'];
            if ($data['password'] != '')
                $data['password'] = md5($data['password']);
            else
                unset($data['password']);

            // $data['role'] = (is_array($this->data['role']) && count($this->data['role']) > 0) ? implode(',', $this->data['role']) : '';

            $this->User->id = $id;
            $this->User->save($data);

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->User->findById($id);
    }

    public function customer_delete($id = null)
    {
        $this->_role('customer_delete');
        $this->autoRender = false;

        $this->Customer->id = $id;
        $this->Customer->delete($id);

        // $this->News->updateAll(
        //     array('News.admin_id' => 1),
        //     array('News.admin_id' => $id)
        // );

        // $this->Product->updateAll(
        //     array('Product.admin_id' => 1),
        //     array('Product.admin_id' => $id)
        // );

        $this->Session->setFlash('Đã xóa');
        $this->redirect($this->referer());
    }

    public function customer_profile()
    {
        $u = $this->Session->read('admin');

        if ($this->data) {
            $data = $this->data['Admin'];
            $current_pass = $data['current_password'];
            $new_pass = $data['new_password'];

            if (trim($new_pass) != '') {
                $current_pass = md5($current_pass);

                if ($current_pass != $u['password']) {
                    $this->Session->setFlash('Mật khẩu hiện tại không đúng');
                    $this->redirect($this->referer());
                    die;
                } else {
                    $data['password'] = md5($data['new_password']);
                    $this->Admin->id = $u['id'];
                    $this->Admin->saveField('password', $data['password']);
                }
            } else {
                unset($data['current_password']);
                unset($data['new_password']);
            }

            foreach ($data as $k => $v) {
                $u[$k] = $v;
            }

            $this->Session->write('admin', $u);
            $this->Session->setFlash('Cập nhật thành công', 'success');
            $this->redirect($this->referer());
            die;
        }

        $this->data = $this->Admin->findById($u['id']);
    }

    public function change_field($field, $news_id)
    {
        $change = 0;
        $data = $this->Customer->findById($news_id);
        if ($data['Customer'][$field] == 0)
            $change = 1;

        $this->Customer->id = $news_id;
        $this->Customer->saveField($field, $change);
        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }
}