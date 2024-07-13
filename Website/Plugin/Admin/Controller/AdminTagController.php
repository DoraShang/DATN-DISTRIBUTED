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

class AdminTagController extends AdminAppController {

    public $uses = array('Node', 'Tag');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function tag_index($type = null) {
        $this->autoRender = false;
    }

    public function tag_list() {
     
        $this->paginate = array(
            'limit' => 10,
            'conditions'=>array(
                'Node.type'=>'tag'
            ),
            'order' => 'Node.id DESC'
        );

        $this->data = $this->paginate('Node');
    }

    public function tag_add() {
        if ($this->data) {

            $data = $this->data['Node'];

            $tag_slug = strtolower(Inflector::slug($data['title'], '-'));
            $check_tag = $this->Node->findBySlug($tag_slug);

            if(is_array($check_tag) && count($check_tag))
            {
                $this->alert('Đã tồn tại', $this->referer());
                die;
            }

            $data['type'] = 'tag';
            $this->Node->save($data);
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->referer());
        }
    }

    public function tag_edit($id = null) {
        if ($this->data) {

            $data = $this->data['Node'];

            $tag_slug = strtolower(Inflector::slug($data['title'], '-'));

            $check_tag = $this->Node->find('first', array(
                'conditions'=>array(
                    'NOT'=>array(
                        'Node.id'=>$id
                    ),
                    'Node.slug' => $tag_slug
                )
            ));

            if(is_array($check_tag) && count($check_tag))
            {
                $this->alert('Đã tồn tại', $this->referer());
                die;
            }

            $data['type'] = 'tag';
            $this->Node->id = $id;
            $this->Node->save($data);
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Node->findById($id);
    }

    public function tag_count_post($node_id)
    {
        $count = $this->Tag->find('count', array(
            'conditions'=>array(
                'Tag.node_tag_id'=>$node_id
            ),
            'group'=>array('Tag.node_id')
        ));
        return $count;
    }

    public function tag_delete($id = null) {
        $this->autoRender = false;
        $this->Node->id = $id;
        $this->Node->delete($id);
        
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }

}
