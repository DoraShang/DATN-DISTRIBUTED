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

class AdminCommentController extends AdminAppController {

    public $uses = array('Comment', 'Node');

    public function comment_index($type = null) {
        $this->autoRender = false;
    }

    public function comment_list($id = null) {

        $node_id = $id != null ? $id : 0;

        $this->Session->write('comment_node_id', $node_id);

        if($node_id > 0)
        {
            $this->paginate = array(
                'joins'=>array(
                    array(
                        'table' => 'nodes',
                        'alias' => 'Node',
                        'conditions' => array('Node.id = Comment.node_id'),
                        'type' => 'INNER'
                    )
                ),
                'conditions'=>array(
                    'Comment.node_id'=>$node_id
                ),
                'limit' => 12,
                'order' => 'Comment.id DESC',
                'fields'=>array('Node.*', 'Comment.*')
            );
        }
        else
        {
            $this->paginate = array(
                'joins'=>array(
                    array(
                        'table' => 'nodes',
                        'alias' => 'Node',
                        'conditions' => array('Node.id = Comment.node_id'),
                        'type' => 'INNER'
                    )
                ),
                'limit' => 12,
                'order' => 'Comment.id DESC',
                'fields'=>array('Node.*', 'Comment.*')
            );
        }

        $this->data = $this->paginate('Comment');
        $data = array();
        if($node_id> 0)
            $data = $this->Node->findById($node_id);
        $this->set('node_comment', $data);
    }

    public function comment_edit($node_id = null) {
        if ($this->data) {
            $data = $this->data['Comment'];
            if(isset($data['image']))
                $data['image'] = $this->remove_hostname ($data['image']);
            $this->Comment->id = $node_id;
            $this->Comment->save($data);
            $this->Session->setFlash('Đã sửa bình luận', 'success');
            $comment_node_id = $this->Session->read('comment_node_id');
            $this->redirect(DOMAINAD . 'admin_comment/comment_list/' . $comment_node_id);
        }

        $this->data = $this->Comment->findById($node_id);
        $this->set('node_comment', $this->Node->findById($node_id));
    }
    
    public function comment_count($node_id)
    {
        $this->autoRender = false;
        
        return $this->Comment->find('count', array(
            'conditions'=>array(
                'Comment.node_id'=>$node_id
            )
        ));
    }
    
    public function update_status($node_id)
    {
        $updated = 0;
        $check = $this->Comment->findById($node_id);
        if($check['Comment']['status'] == 0)
            $updated = 1;
        
        $this->Comment->id = $node_id;
        $this->Comment->saveField('status', $updated);
        
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function comment_delete($id = null) {
        $this->autoRender = false;
        $this->Comment->id = $id;
        $this->Comment->delete($id);
        $this->Session->setFlash('Đã xóa bình luận');
        $this->redirect($this->referer());
    }

}
