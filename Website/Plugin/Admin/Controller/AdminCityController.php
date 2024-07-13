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

class AdminCityController extends AdminAppController
{

    public $uses = array('City', 'Mien');
    public function beforeFilter()
    {
        parent::beforeFilter();

        $mien = $this->Mien->find('list', array('fields' => array('id', 'title')));
        $this->set('mien', $mien);
    }

    public function city_index($type = null)
    {
        $this->autoRender = false;
    }

    public function city_list()
    {

        $this->paginate = array(
            'limit' => 100,
            'order' => 'City.pos DESC,City.id DESC'
        );

        $this->data = $this->paginate('City');
    }

    public function city_add()
    {
        if ($this->data) {
            $data = $this->data['City'];
            $data['image'] = $this->remove_hostname($data['image']);
            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->City->save($data);
            $this->Session->setFlash('Đã thêm mới tỉnh thành', 'success');
            $this->redirect($this->referer());
        }
    }

    public function city_edit($id = null)
    {
        if ($this->data) {
            $data = $this->data['City'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->City->id = $id;
            $this->City->save($data);
            $this->Session->setFlash('Đã sửa tỉnh thành', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->City->findById($id);
    }

    public function save_pos()
    {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->City->updateAll(
                array(
                    'City.pos' => $v,
                ),
                array(
                    'City.id' => $k
                )
            );
        }

        $this->redirect($this->referer());
    }

    public function city_delete($id = null)
    {
        $this->autoRender = false;
        $this->City->id = $id;
        $this->City->delete($id);
        $this->Session->setFlash('Đã xóa tỉnh thành', 'success');
        $this->redirect($this->referer());
    }

    public function city_change($field, $id)
    {
        $change = 0;
        $data = $this->City->findById($id);
        if ($data['City'][$field] == 0)
            $change = 1;

        $this->City->id = $id;
        $this->City->saveField($field, $change);

        $this->Session->setFlash("Đã thay đổi", "success");
        $this->redirect($this->referer());
        die;
    }
}
