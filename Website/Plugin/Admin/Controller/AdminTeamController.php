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

class AdminTeamController extends AdminAppController
{

    public $uses = array('Team');
    public $team_categories = array();

    public function beforeFilter()
    {
        parent::beforeFilter();

        $cat = $this->settings['cat_bld'];
        $cat_arr = explode("\n", $cat);

        $team_categories = array();

        $key = 'Ban lãnh đạo';
        $i = 0;

        foreach ($cat_arr as $v) {
            $t = explode(':', $v);
            if (count($t) > 1) {
                if ($t[0] <= 3)
                    $key = 'Ban Lãnh Đạo';
                else if ($t[0] > 3 && $t[0] <= 11)
                    $key = 'Ban chuyên môn';
                else
                    $key = 'Hội viên';

                $team_categories[$key][$t[0]] = $t[1];
            }
        }

        $this->team_categories = $team_categories;
        $this->set('team_categories', $this->team_categories);
    }

    public function team_index($type = null)
    {
        $this->autoRender = false;
    }

    public function team_list()
    {
        $this->_role('team_list');
        $this->paginate = array(
            'limit' => 12,
            'order' => 'Team.pos DESC, Team.id DESC'
        );

        $this->data = $this->paginate('Team');
    }

    public function team_add()
    {
        $this->_role('team_add');
        if ($this->data) {
            $data = $this->data['Team'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['banner'] = $this->remove_hostname($data['banner']);

            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;


            $data_thuoctinh = isset($this->data['Thuoctinh']) ? $this->data['Thuoctinh'] : array();
            if (isset($data_thuoctinh['att1']))
                $data['att1'] = json_encode($data_thuoctinh['att1']);


            $data['slug'] = strtolower(Inflector::slug($data['fullname'], '-'));

            $check = $this->Team->findBySlug($data['slug']);
            if (is_array($check) && count($check) > 0) {
                $data['slug'] = $data['slug'] . '-' . time();
                // $this->Session->setFlash("Tên đã tồn tại", "error");
                // $this->redirect($this->referer()); die;
            }
            $this->Team->save($data);
            $this->Session->setFlash('Đã thêm mới hình ảnh', 'success');
            $this->redirect($this->referer());
        }
    }

    public function team_edit($id = null)
    {
        $this->_role('team_edit');
        if ($this->data) {
            $data = $this->data['Team'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['banner'] = $this->remove_hostname($data['banner']);

            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;


            $data_thuoctinh = isset($this->data['Thuoctinh']) ? $this->data['Thuoctinh'] : array();
            if (isset($data_thuoctinh['att1']))
                $data['att1'] = json_encode($data_thuoctinh['att1']);


            $data['slug'] = strtolower(Inflector::slug($data['fullname'], '-'));

            $check = $this->Team->findBySlug($data['slug']);
            if (is_array($check) && count($check) > 0) {
                $data['slug'] = $data['slug'] . '-' . time();
                // $this->Session->setFlash("Tên đã tồn tại", "error");
                // $this->redirect($this->referer()); die;
            }
            $this->Team->id = $id;
            $this->Team->save($data);
            $this->Session->setFlash('Đã sửa hình ảnh', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Team->findById($id);
    }

    public function update_field($field, $hang_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Team->findById($hang_id);
        if ($check['Team'][$field] == 1)
            $changed = 0;

        $this->Team->id = $hang_id;
        $this->Team->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function save_pos()
    {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Team->updateAll(
                array(
                    'Team.pos' => $v,
                ),
                array(
                    'Team.id' => $k
                )
            );
        }

        $this->redirect($this->referer());
    }

    public function team_delete($id = null)
    {
        $this->_role('team_delete');
        $this->autoRender = false;
        $this->Team->id = $id;
        $this->Team->delete($id);
        $this->Session->setFlash('Đã xóa hình ảnh');
        $this->redirect($this->referer());
    }
}
