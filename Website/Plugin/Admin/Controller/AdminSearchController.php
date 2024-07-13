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

class AdminSearchController extends AdminAppController
{
    public $uses = array('Node', 'Product', 'News', 'Tag');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->get_categories(array('type' => 'product'));
        $this->set('limit', $this->limit);
    }

    public function search_get_image($node_id, $node_type)
    {
        if ($node_type == 'product') {
            $checkData = $this->Product->findByNodeId($node_id);
            return '<img src="' . DOMAIN . 'timthumb.php?src=' . DOMAIN . $checkData['Product']['image'] . '&w=70&zc=1" />';
        }

        if ($node_type == 'news') {
            $checkData = $this->News->findByNodeId($node_id);
            return '<img src="' . DOMAIN . 'timthumb.php?src=' . DOMAIN . $checkData['News']['image'] . '&w=70&zc=1" />';
        }

        return '';
    }

    public function search()
    {
        $conditions = array();
        $conditions['Node.type'] = array('product', 'news', 'tag', 'category');
        $conditions['News.type'] = array('product', 'news', 'tag', 'category');

        $key = isset($_GET['s']) ? $_GET['s'] : '';
        $conditions['Node.title LIKE'] = '%' . $key . '%';
        $conditions['News.content LIKE'] = '%' . $key . '%';

        $this->paginate = array(
            'conditions' => $conditions,
            'limit' => 10,
            'order' => array('Node.pos' => 'desc', 'Node.id' => 'desc'),
            'fields' => array('Node.*')
        );

        $this->data = $this->paginate('Node', 'News');
    }
}
