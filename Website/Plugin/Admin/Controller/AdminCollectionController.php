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

class AdminCollectionController extends AdminAppController {

    public $uses = array('Admin.Collection', 'Node', 'Page', 'CategoryLinked');
    
    public function beforeFilter()
    {
    	parent::beforeFilter();
        
        $pages = $this->Page->find('all', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Page.node_id'),
                    'type'=>'INNER'
                )
            ),
            'conditions'=>array(
                'Node.status'=>1
            ),
            'fields'=>array('Node.*', 'Page.*')
        ));
        
        $page_buff = array();
        foreach($pages as $v) 
        {
            $page_buff[$v['Page']['id']] = $v['Node']['title'];
        }
        
        $this->set('pages', $page_buff);

        $this->set('collection_type', array(
            'page'=>'Trang page',
            'link'=>'Chọn link',
            'news'=>'Tin tức',
            // 'picture'=>'Hình ảnh',
            // 'video'=>'Videos',
            // 'sale'=>'Khuyến mãi',
            // 'rate'=>'Khách hàng đánh giá',
            'product'=>'Sản phẩm',
            // 'faq'=>'Hỏi đáp',
        ));
    }
    
    public function collection_list($collection_id = null) {
        $conditions = array();
        $conditions['Node.type'] = 'collection';
        
        $filter_category = isset($_GET['list_category']) ? $_GET['list_category'] : '';
        $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
                
        if($filter_category != '')
        {
            $check_cat = $this->Category->findById($filter_category);
            $data_check_cat = $this->Category->find('all', array(
                'conditions'=>array(
                    'Category.lft >=' => $check_cat['Category']['lft'],
                    'Category.rght <=' => $check_cat['Category']['rght']
                )
            ));

            $buff[] = array();

            foreach($data_check_cat as $v)
            {
                $buff[] = $v['Category']['id'];
            }

            $conditions['CategoryLinked.category_id'] = $buff;
        }
        
        if($filter_status != '')
            $conditions['Node.status'] = $filter_status;

        $this->paginate = array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Collection.node_id = Node.id'),
                ),
                array(
                    'table'=>'category_linkeds',
                    'alias'=>'CategoryLinked',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = CategoryLinked.node_id')
                )
            ),
            'conditions'=>$conditions,
            'order' => 'Node.pos DESC, Node.id DESC',
            'limit'=>10,
            'group'=>'CategoryLinked.node_id',
            'fields'=>array('Collection.*', 'Node.*')
        );

        $this->data = $this->paginate('Collection');

        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);

        // $this->Collection->find('all', array(
        //     'joins'=>array(
        //         array(
        //             'table'=>'nodes',
        //             'alias'=>'Node',
        //             'type'=>'INNER',
        //             'conditions'=>array('Collection.node_id = Node.id'),
        //         ),
        //     ),
        //     'order' => 'Node.pos DESC, Node.id DESC',
        //     'limit'=>10,
        //     'fields'=>array('Collection.*', 'Node.*')
        // ));
    }

    public function collection_add() {
        if ($this->data) {
            $data = $this->data['Collection'];
            $data_node = $this->data['Node'];            
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            $data_node['type'] = 'collection';
            $data_node['created'] = time();
            $data_node['modified'] = time();
            
            $check = $this->Node->findBySlug($data_node['slug']);
            if(is_array($check) && count($check)>0)
            	$data_node['slug'] = $data_node['slug'].'-'.time();
            
            $data['title'] = $data_node['title'];
            $data['seo_keyword'] = preg_replace('/\r|\n/', ' ', $data['seo_keyword']);
            $data['seo_keyword'] = preg_replace('/\s+/', ' ', $data['seo_keyword']);
            // $data['seo_description'] = preg_replace('/\r|\n/', ' ', $data['seo_description']);
            // $data['seo_description'] = preg_replace('/\s+/', ' ', $data['seo_description']);
            $data['image'] = $this->remove_hostname($data['image']);
            // $data['image_single'] = $this->remove_hostname($data['image_single']);

            if(isset($this->data['Images']))
            {
                $images = $this->data['Images'];
                $data_images = array();

                foreach($images as $v)
                {
                    $data_images[] = $this->remove_hostname($v);
                }

                $data['images'] = implode(',', $data_images);
            }
            
            $this->Node->save($data_node);
            $data['node_id'] = $this->Node->getLastInsertID();
            $node_id = $data['node_id'];
            
            $this->Collection->save($data);

            if(isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0)
            {
                $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id), false);

                foreach($this->data['category_id'] as $v)
                {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id'=>$v,
                        'node_id'=>$node_id
                    ));
                }
            }

            $this->Session->setFlash('Đã thêm mục lục', 'success');
            $this->redirect($this->referer());
        }
    }

    public function collection_edit($id = null) {    	
        if ($this->data) {
            // pr($this->data); die;
            $data = $this->data['Collection'];
            $data_node = $this->data['Node'];            
            $data_node['slug'] = strtolower($data_node['slug']);
            $data_node['type'] = 'collection';
            $data_node['modified'] = time();
            
            $check = $this->Node->find('first', array(
                'joins'=>array(
                    array(
                        'table'=>'categories',
                        'alias'=>'Collection',
                        'type'=>'INNER',
                        'conditions'=>array('Collection.node_id=Node.id')
                    )
                ),
                'conditions'=>array(
                    'Node.slug'=>$data_node['slug'],
                    'NOT'=>array(
                        'Node.id'=>$id
                    )
                ),
                'fields'=>array('Node.*', 'Collection.*')
            ));
            
            if(is_array($check) && count($check)>0)
            {
            	if($check['Collection']['id'] != $data['id'])
                    $data_node['slug'] = $data_node['slug'].'-'.time();
            }
            $this->Node->id = $id;
            unset($data_node['id']);
            $this->Node->save($data_node);
            
            $data['title'] = $data_node['title'];
            $data['seo_keyword'] = preg_replace('/\r|\n/', ' ', $data['seo_keyword']);
            $data['seo_keyword'] = preg_replace('/\s+/', ' ', $data['seo_keyword']);
            // $data['seo_description'] = preg_replace('/\r|\n/', ' ', $data['seo_description']);
            // $data['seo_description'] = preg_replace('/\s+/', ' ', $data['seo_description']);
            $data['image'] = $this->remove_hostname($data['image']);
            $data['images'] = '';
            // $data['videos'] = '';
            // $data['image_single'] = $this->remove_hostname($data['image_single']);

            if(isset($this->data['Images']))
            {
                $images = $this->data['Images'];
                $data_images = array();

                foreach($images as $v)
                {
                    $data_images[] = $this->remove_hostname($v);
                }
                
                $data['images'] = implode(',', $data_images);
            }

            if(isset($this->data['Video']))
            {
                $videos = $this->data['Video'];
                $data_video = array();

                foreach($videos as $v)
                {
                    $data_video[] = $v;
                }
                
                $data['videos'] = implode(',', $data_video);
            }
            
            $check_cat = $this->Collection->find('first', array(
                'conditions'=>array(
                    'Collection.node_id'=>$id
                )
            ));

            $this->Collection->id = $check_cat['Collection']['id'];
            $this->Collection->save($data);



            if(isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0)
            {
                $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $id), false);

                foreach($this->data['category_id'] as $v)
                {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id'=>$v,
                        'node_id'=>$id
                    ));
                }
            }

            $this->Session->setFlash('Đã sửa mục lục', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Collection->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id = Collection.node_id'),
                    'type'=>'INNER'
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id,
            ),
            'fields'=>array('Node.*, Collection.*')
        ));

        $this->set('images', explode(',', $this->data['Collection']['images']));

        $cats = $this->CategoryLinked->find('all', array(
            'conditions'=>array(
                'CategoryLinked.node_id' => $id
            ),
            'fields'=>'category_id'
        ));

        $buff = array();

        if(is_array($cats) && count($cats) > 0)
        {
            foreach($cats as $v)
            {
                $buff[] = $v['CategoryLinked']['category_id'];
            }
        }

        $this->set('cat_selected', $buff);

        // $this->set('videos', explode(',', $this->data['Collection']['videos']));
    }
    
    public function get_list_category_name($news_node_id)
    {
        $this->autoRender = false;

        $data = $this->CategoryLinked->find('all', array(
            'conditions'=>array(
                'CategoryLinked.node_id'=>$news_node_id
            ),
            'limit'=>20
        ));

        $str  = "";

        if(is_array($data) && count($data)>0)
        {
            foreach($data as $v)
            {
                if(isset($this->category_tree[$v['CategoryLinked']['category_id']]))
                    $str .= $this->category_tree[$v['CategoryLinked']['category_id']] . ' ,';
            }
        }

        return trim($str, ', ');
    }

    public function update_field($field, $collection_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Collection->findById($collection_id);
        if($check['Collection'][$field] == 1)
        {
            $changed = 0;
        }
        
        $this->Collection->id = $collection_id;
        $this->Collection->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }
}
