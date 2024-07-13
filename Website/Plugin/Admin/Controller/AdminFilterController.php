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

class AdminFilterController extends AdminAppController {

    public $uses = array('Filter', 'FilterItem');
    public $limit = 10;
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('limit', $this->limit);
    }

    public function filter_index($type = null) {
        $this->autoRender = false;
    }

    public function filter_list() {
     
        $this->paginate = array(
            'limit' => 12,
            'order' => array('Filter.pos'=>'desc', 'Filter.id'=>'desc')
        );

        $this->data = $this->paginate('Filter');
    }

    public function filter_add() 
    {
        if ($this->data) 
        {
            $data = $this->data;
            $title = $data['Filter']['title'];
            $items = array();
        
            if(isset($data['items']) && count($data['items']) > 0)
            {
                foreach($data['items'] as $v)
                {
                    $items[] = $v;
                }
            }

            if(count($items) <= 0)
            {
                $this->alert("Bạn chưa tạo thành phần bộ lọc", $this->referer());
                die;
            }

            $this->Filter->create();
            $this->Filter->save(array('title'=>$title));
            $filter_id = $this->Filter->getLastInsertID();

            if(count($items) > 0)
            {
                foreach($items as $it)
                {
                    $save = array(
                        'title'=>$it['name'],
                        'filter_id'=>$filter_id,
                        'pos'=>$it['pos'],
                    );

                    $this->FilterItem->create();
                    $this->FilterItem->save($save);
                }
            }
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->get_redirect('filter', 'add', $filter_id)); die;
        }
    }

    public function filter_edit($id = null) {
        if ($this->data) 
        {
            $data = $this->data;
            // pr($data); 
            // die;

            $title = $data['Filter']['title'];

            $items = array();
            $old_items = array();
            $old_id_items = isset($data['old_id_items']) ? explode(',', $data['old_id_items']) : array();
        
            if(isset($data['items']) && count($data['items']) > 0)
            {
                foreach($data['items'] as $v)
                {
                    $items[] = $v;
                }
            }

            if(isset($data['old_items']) && count($data['old_items']) > 0)
            {
                foreach($data['old_items'] as $k=>$v)
                {
                    $old_items[$k] = $v;
                }
            }

            // var_dump(count($items));
            // var_dump(count($old_items));
            // pr($items);
            // pr($old_items);
            // die;

            if(count($items) <= 0 && count($old_items) <= 0)
            {
                $this->alert("Bạn chưa tạo thành phần bộ lọc", $this->referer());
                die;
            }

            // pr($old_items);
            // pr($items);
            // die;

            $this->Filter->id = $id;
            $this->Filter->saveField('title', $title);

            if(count($items) > 0)
            {
                $filter_id = $id;

                foreach($items as $it)
                {
                    $save = array(
                        'title'=>$it['name'],
                        'filter_id'=>$filter_id,
                        'pos'=>$it['pos'],
                    );

                    $this->FilterItem->create();
                    $this->FilterItem->save($save);
                }
            }

            //updte dữ liệu cũ
            $updated = array();
            if(count($old_items) > 0)
            {
                foreach($old_items as $iID=>$it)
                {
                    $this->FilterItem->updateAll(
                        array(
                            'title' => "'" . $it['name'] . "'",
                            'pos' => "'" . $it['pos'] . "'",
                        ),
                        array('id'=>$iID)
                    );

                    $updated[] = $iID;
                }
            }

            //Nếu tổng bản ghi cũ và mới ko giống nhau thì xóa ID đã bị loại
            if(count($old_id_items) > count($updated))
            {
                foreach($old_id_items as $iID)
                {
                    if(!in_array($iID, $updated))
                    {
                        $this->FilterItem->delete($iID);
                    }
                }
            }
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('filter', 'edit', $id)); die;
        }

        $items = $this->FilterItem->find('all', array(
            'conditions'=>array(
                'FilterItem.filter_id'=>$id,
            ),
            'order'=>array('FilterItem.pos' => 'desc', 'FilterItem.id' => 'asc')
        ));
        
        $this->set('items', $items);
        $this->data = $this->Filter->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Filter->updateAll(
                    array(
                'Filter.pos' => $v,
                    ), array(
                'Filter.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function filter_delete($id = null) {
        $this->autoRender = false;
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $redirect_list = DOMAINAD . 'admin_banner/banner_list/' . $redirect_page;

        $this->Filter->id = $id;
        $this->Filter->delete($id);
        
        $this->FilterItem->deleteAll(array('filter_id'=>$id));
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($redirect_list);
    }

}
