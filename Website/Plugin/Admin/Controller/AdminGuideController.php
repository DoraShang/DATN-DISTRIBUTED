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

class AdminGuideController extends AdminAppController
{
    public $uses = array('Node', 'Guide', 'Image', 'Video', 'CategoryLinked', 'GuideDetail', 'Agency', 'Tag', 'Filter', 'FilterLinked', 'FilterItem', 'City', 'Size');
    public $limit = 10;

    public $status = array(
        '0' => 'Hết hàng',
        '1' => 'Còn hàng',
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->get_categories(array('type' => 'guide'));

        $filters = $this->City->find('list', array('fields' => array('id', 'title')));
        $sizes = $this->Size->find('list', array('fields' => array('id', 'title')));
        $filter_data = array();
        foreach ($filters as $k => $v) {
            $filter_data[$k]['id'] = $k;
            $filter_data[$k]['title'] = $v;

            // $check_items = $this->FilterItem->find('all', array(
            //     'conditions' => array(
            //         'FilterItem.filter_id' => $k,
            //     )
            // ));


            // if (is_array($check_items) && count($check_items) > 0) {
            //     foreach ($check_items as $ck => $cv) {
            //         $filter_data[$k]['data'][] = $cv['FilterItem'];
            //         $filter_data[$k]['items'][] = $cv['FilterItem']['id'];
            //     }
            // }
        }

        $this->set('filters', $filter_data);
        $this->set('sizes', $sizes);

        // $this->set('agencies', $this->Agency->find('list', array('fields'=>array('id', 'title'))));
        $this->Session->delete('news_title');
        $this->Session->delete('news_title_org');


        $this->set('limit', $this->limit);
        $this->set('status', $this->status);
    }

    public function guide_list()
    {
        $this->_role('guide_list');
        $conditions = array();
        $conditions['Node.type'] = 'guide';

        $filter_category = isset($_GET['list_category']) ? $_GET['list_category'] : '';
        $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
        $search = isset($_GET['s']) ? $_GET['s'] : '';

        if ($filter_category != '') {
            $check_cat = $this->Category->findById($filter_category);
            $data_check_cat = $this->Category->find('all', array(
                'conditions' => array(
                    'Category.lft >=' => $check_cat['Category']['lft'],
                    'Category.rght <=' => $check_cat['Category']['rght']
                )
            ));

            $buff[] = array();

            foreach ($data_check_cat as $v) {
                $buff[] = $v['Category']['id'];
            }

            $conditions['CategoryLinked.category_id'] = $buff;
        }
        if ($search != '') {
            $conditions['Node.title LIKE'] = '%' . $search . '%';
        }
        if ($filter_status != '')
            $conditions['Node.status'] = $filter_status;

        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'guides',
                    'alias' => 'Guide',
                    'type' => 'INNER',
                    'conditions' => array('Node.id=Guide.node_id')
                ),
                array(
                    'table' => 'category_linkeds',
                    'alias' => 'CategoryLinked',
                    'type' => 'LEFT',
                    'conditions' => 'CategoryLinked.node_id = Node.id'
                )
            ),
            'conditions' => $conditions,
            'limit' => $this->limit,
            'order' => 'Node.pos DESC, Node.id DESC',
            'group' => 'CategoryLinked.node_id',
            'fields' => array('Node.*', 'Guide.*')
        );

        $this->data = $this->paginate('Node');
        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);
    }

    public function guide_add()
    {
        $this->_role('guide_add');
        if ($this->data) {
            // pr($this->data); die;
            $data_node = $this->data['Node'];
            $data_guide = $this->data['Guide'];
            $data_guide_detailt = $this->data['GuideDetail'];

            $data_thuoctinh = isset($this->data['Thuoctinh']) ? $this->data['Thuoctinh'] : array();
            $data_filters = isset($this->data['filters']) ? $this->data['filters'] : array();

            if (isset($data_thuoctinh['att1'])) {
                $data_guide['att1'] = json_encode($data_thuoctinh['att1']);
            } else {
                $data_guide['att1'] = '';
            }
            if (isset($data_thuoctinh['att2'])) {
                $data_guide['att2'] = json_encode($data_thuoctinh['att2']);
            } else {
                $data_guide['att2'] = '';
            }
            if (isset($data_thuoctinh['att3'])) {
                $data_guide['att3'] = json_encode($data_thuoctinh['att3']);
            } else {
                $data_guide['att3'] = '';
            }
            if (isset($data_thuoctinh['att4'])) {
                $data_guide['att4'] = json_encode($data_thuoctinh['att4']);
            } else {
                $data_guide['att4'] = '';
            }
            if (isset($data_thuoctinh['att5'])) {
                $data_guide['att5'] = json_encode($data_thuoctinh['att5']);
            } else {
                $data_guide['att5'] = '';
            }

            if (isset($this->data['Size'])) {
                // $data_size = $this->data['Size'];
                // $data_price = $this->data['Price'];

                // $buff = array();
                // foreach ($data_size as $k => $v) {
                //     $buff_price = preg_replace('/[^0-9]/', '', $data_price[$k]);
                //     $buff[$v] = $buff_price;
                // }
                // $data_guide['size'] = json_encode($buff);
                $data_guide['size'] = json_encode($this->data['Size']);
            }


            if (isset($this->data['sidebar_category_id'])) {
                $data_guide['sidebar_category_id'] = implode(',', $this->data['sidebar_category_id']);
            }

            $data_images = isset($this->data['Images']) ? $this->data['Images'] : null;
            $data_videos = isset($this->data['Videos']) ? $this->data['Videos'] : null;
            $data_required = isset($this->data['Required']) ? $this->data['Required'] : null;

            if (is_array($data_required) && count($data_required) > 0) {
                $buff_slug = array();
                $buff_required_nodes = array();

                foreach ($data_required as $v) {
                    $v = trim($v);

                    if ($v == '')
                        continue;

                    if (is_numeric($v))
                        $buff_required_nodes[] = $v;
                    else if (!preg_match('/\.html/', $v)) {
                        $v = preg_replace('/[^0-9]/', '', $v);
                        $buff_required_nodes[] = $v;
                    } else {
                        $l = trim($v, '/ ');
                        $sarr = explode('/', $l);
                        $sl = str_replace('.html', '', end($sarr));

                        $buff_slug[] = $sl;
                    }
                }

                if (count($buff_slug) > 0) {
                    $check_nodes = $this->Node->find('all', array(
                        'conditions' => array(
                            'Node.slug' => $buff_slug
                        )
                    ));

                    if (is_array($check_nodes) && count($check_nodes) > 0) {
                        foreach ($check_nodes as $v) {
                            $buff_required_nodes[] = $v['Node']['id'];
                        }

                        $data_guide['required_post_ids'] = implode(',', $buff_required_nodes);
                    }
                }
            }

            if (is_array($this->data['sizes'])) {
                $data_node['size_id'] = join(',', $this->data['sizes']);
            } else {
                $data_node['size_id'] = '';
            }
            $data_node['type'] = 'guide';
            $data_node['city_id'] = isset($this->data['Node']['city_id']) ? $this->data['Node']['city_id'] : null;
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));

            $check = $this->Node->findBySlug($data_node['slug']);
            if (is_array($check) && count($check) > 0) {
                $data_node['slug'] = $data_node['slug'] . '-' . time();
                // $this->Session->setFlash("Tên đã tồn tại", "error");
                // $this->redirect($this->referer()); die;
            }
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            if (isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0) {
                foreach ($this->data['category_id'] as $v) {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id' => $v,
                        'node_id' => $node_id
                    ));
                }
            }

            $data_guide['node_id'] = $node_id;
            $data_guide_detailt['node_id'] = $node_id;
            $data_guide['image'] = $this->remove_hostname($data_guide['image']);

            //update data_filters
            if (is_array($data_filters) && count($data_filters) > 0) {
                foreach ($data_filters as $v) {
                    $this->FilterLinked->create();
                    $this->FilterLinked->save(array(
                        'node_id' => $node_id,
                        'filter_item_id' => $v
                    ));
                }
            }


            $data_tags = array();
            if (isset($data_guide['tags']))
                $data_tags = $data_guide['tags'] != "" ? explode(',', $data_guide['tags']) : '';

            //save tags
            $tag_relation = array();

            if (is_array($data_tags) && count($data_tags) > 0) {
                foreach ($data_tags as $v) {
                    $tag_title = trim($v);

                    if ($tag_title != "") {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if (is_array($check_tag) && count($check_tag)) {
                            $tag_relation[] = $check_tag['Node']['id'];
                        } else {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title' => $tag_title,
                                'slug' => $tag_slug,
                                'type' => 'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if (count($tag_relation) > 0) {
                foreach ($tag_relation as $v) {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id' => $node_id,
                        'node_tag_id' => $v
                    ));
                }
            }
            // pr($data_guide);
            // pr($data_guide_detailt);
            // die;
            $this->Guide->save($data_guide);
            $this->GuideDetail->save($data_guide_detailt);

            if (is_array($data_images) && count($data_images) > 0) {
                foreach ($data_images as $v) {
                    if (trim($v) != '') {
                        if (!preg_match('/http/', $v))
                            $v = $this->remove_hostname($v);

                        $save = array(
                            'node_id' => $node_id,
                            'image' => $v
                        );

                        $this->Image->create();
                        $this->Image->save($save);
                    }
                }
            }
            if (is_array($data_videos) && count($data_videos) > 0) {
                foreach ($data_videos as $v) {
                    if (trim($v) != '') {
                        if (!preg_match('/http/', $v))
                            $v = $this->remove_hostname($v);

                        $save = array(
                            'node_id' => $node_id,
                            'video' => $v,
                            'image' => $v
                        );

                        $this->Video->create();
                        $this->Video->save($save);
                    }
                }
            }

            $this->Session->setFlash("Đã thêm", "success");

            if (isset($this->data['copy']) && $this->data['copy'] == 1) {
                $this->redirect($this->referer());
                die;
            }

            $this->redirect($this->get_redirect('guide', 'add', $node_id));
            die;
        }
    }

    public function guide_edit($node_id)
    {
        $this->_role('guide_edit');
        if ($this->data) {
            // pr($this->data); die;
            $data_node = $this->data['Node'];
            $data_guide = $this->data['Guide'];
            $data_guide_detailt = $this->data['GuideDetail'];
            $data_images = isset($this->data['Images']) ? $this->data['Images'] : null;
            $data_videos = isset($this->data['Videos']) ? $this->data['Videos'] : null;

            $data_thuoctinh = isset($this->data['Thuoctinh']) ? $this->data['Thuoctinh'] : array();
            $data_filters = isset($this->data['filters']) ? $this->data['filters'] : array();

            if (isset($data_thuoctinh['att1'])) {
                $data_guide['att1'] = json_encode($data_thuoctinh['att1']);
            } else {
                $data_guide['att1'] = '';
            }
            if (isset($data_thuoctinh['att2'])) {
                $data_guide['att2'] = json_encode($data_thuoctinh['att2']);
            } else {
                $data_guide['att2'] = '';
            }
            if (isset($data_thuoctinh['att3'])) {
                $data_guide['att3'] = json_encode($data_thuoctinh['att3']);
            } else {
                $data_guide['att3'] = '';
            }
            if (isset($data_thuoctinh['att4'])) {
                $data_guide['att4'] = json_encode($data_thuoctinh['att4']);
            } else {
                $data_guide['att4'] = '';
            }
            if (isset($data_thuoctinh['att5'])) {
                $data_guide['att5'] = json_encode($data_thuoctinh['att5']);
            } else {
                $data_guide['att5'] = '';
            }

            if (isset($data_thuoctinh['att1_en']))
                $data_guide['att1_en'] = json_encode($data_thuoctinh['att1_en']);
            if (isset($data_thuoctinh['att1_cn']))
                $data_guide['att1_cn'] = json_encode($data_thuoctinh['att1_cn']);

            if (isset($data_guide['khaigiang']) && $data_guide['khaigiang'] != '')
                $data_guide['khaigiang'] = strtotime($data_guide['khaigiang']);

            // pr($this->data); die;
            // if (isset($this->data['colors'])) {
            //     $colors = $this->data['colors'];
            //     $arr = array();
            //     $i = 0;
            //     foreach ($colors as $v) {
            //         $arr[$i]['color'] = $v['color'];
            //         if (isset($v['image']) && count($v['image']) > 0) {
            //             foreach ($v['image'] as $val) {
            //                 if (trim($val != ''))
            //                     $arr[$i]['image'][] = $this->remove_hostname($val);
            //             }
            //         }
            //         $i++;
            //     }

            //     $data_guide['guide_images'] = json_encode($arr);
            // }


            // if (isset($this->data['sidebar_category_id'])) {
            //     $data_guide['sidebar_category_id'] = implode(',', $this->data['sidebar_category_id']);
            // }


            $data_node['type'] = 'guide';

            if (is_array($this->data['sizes'])) {
                $data_node['size_id'] = join(',', $this->data['sizes']);
            } else {
                $data_node['size_id'] = '';
            }
            $data_node['city_id'] = $data_node['city_id'];
            $data_node['slug'] = $data_node['slug'];

            $check = $this->Node->find('first', array(
                'conditions' => array(
                    'Node.slug' => $data_node['slug'],
                    'NOT' => array(
                        'Node.id' => $node_id
                    )
                )
            ));

            // pr($check); die;
            if (is_array($check) && count($check) > 0) {
                $this->Session->setFlash("Tên đã tồn tại", "error");
                $this->redirect($this->referer());
                die;
            }

            $this->Node->id = $node_id;
            $this->Node->save($data_node);

            $data_guide['node_id'] = $node_id;
            $data_guide_detailt['node_id'] = $node_id;
            $data_guide['image'] = $this->remove_hostname($data_guide['image']);
            $data_guide['banner'] = $this->remove_hostname($data_guide['banner']);

            $check_guide = $this->Guide->find('first', array(
                'conditions' => array(
                    'Guide.node_id' => $node_id
                )
            ));
            $check_guide_detail = $this->GuideDetail->find('first', array(
                'conditions' => array(
                    'GuideDetail.node_id' => $node_id
                )
            ));
            //update data_filters
            $this->FilterLinked->deleteAll(array('FilterLinked.node_id' => $node_id), false);
            if (is_array($data_filters) && count($data_filters) > 0) {
                foreach ($data_filters as $v) {
                    $this->FilterLinked->create();
                    $this->FilterLinked->save(array(
                        'node_id' => $node_id,
                        'filter_item_id' => $v
                    ));
                }
            }

            $data_tags = array();
            if (isset($data_guide['tags'])) {
                $data_tags = $data_guide['tags'] != "" ? explode(',', $data_guide['tags']) : '';
            }

            //update tag
            $this->Tag->deleteAll(array('Tag.node_id' => $node_id), false);

            //save tags
            $tag_relation = array();

            if (is_array($data_tags) && count($data_tags) > 0) {
                foreach ($data_tags as $v) {
                    $tag_title = trim($v);

                    if ($tag_title != "") {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if (is_array($check_tag) && count($check_tag)) {
                            $tag_relation[] = $check_tag['Node']['id'];
                        } else {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title' => $tag_title,
                                'slug' => $tag_slug,
                                'type' => 'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if (count($tag_relation) > 0) {
                foreach ($tag_relation as $v) {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id' => $node_id,
                        'node_tag_id' => $v
                    ));
                }
            }

            $this->Guide->id = $check_guide['Guide']['id'];
            $this->Guide->save($data_guide);
            $this->GuideDetail->id = $check_guide_detail['GuideDetail']['id'];
            $this->GuideDetail->save($data_guide_detailt);

            if (isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0) {
                $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id), false);

                foreach ($this->data['category_id'] as $v) {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id' => $v,
                        'node_id' => $node_id
                    ));
                }
            }

            $this->Image->deleteAll(array('Image.node_id' => $node_id), false);
            $this->Video->deleteAll(array('Video.node_id' => $node_id), false);

            if (is_array($data_images) && count($data_images) > 0) {
                foreach ($data_images as $v) {
                    if (trim($v) != '') {
                        if (!preg_match('/http/', $v))
                            $v = $this->remove_hostname($v);

                        $save = array(
                            'node_id' => $node_id,
                            'image' => $v
                        );

                        $this->Image->create();
                        $this->Image->save($save);
                    }
                }
            }

            if (is_array($data_videos) && count($data_videos) > 0) {
                foreach ($data_videos as $v) {
                    if (trim($v) != '') {
                        if (!preg_match('/http/', $v))
                            $v = $this->remove_hostname($v);

                        $save = array(
                            'node_id' => $node_id,
                            'video' => $v,
                            'image' => $v
                        );

                        $this->Video->create();
                        $this->Video->save($save);
                    }
                }
            }
            $this->Session->setFlash("Đã sửa", "success");
            $this->redirect($this->get_redirect('guide', 'edit', $node_id));
            die;
        }

        $data = $this->Node->find('first', array(
            'joins' => array(
                array(
                    'table' => 'guides',
                    'alias' => 'Guide',
                    'type' => 'INNER',
                    'conditions' => array('Guide.node_id=Node.id')
                ),
                array(
                    'table' => 'guide_details',
                    'alias' => 'GuideDetail',
                    'type' => 'INNER',
                    'conditions' => array('GuideDetail.node_id=Node.id')
                )
            ),
            'conditions' => array(
                'Node.id' => $node_id
            ),
            'fields' => array('Guide.*', 'Node.*', 'GuideDetail.*')
        ));

        // if ($this->is_valid_json($data['Guide']['guide_images'])) {
        //     $data['Guide']['guide_images'] = json_decode($data['Guide']['guide_images']);
        // }

        $this->data = $data;

        $this->set('images', $this->Image->find('all', array(
            'conditions' => array(
                'Image.node_id' => $node_id
            )
        )));
        $this->set('videos', $this->Video->find('all', array(
            'conditions' => array(
                'Video.node_id' => $node_id
            )
        )));
        // pr($this->Image->find('all', array(
        //     'conditions' => array(
        //         'Image.node_id' => $node_id
        //     )
        // )));
        // die;
        $cats = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $node_id
            ),
            'fields' => 'category_id'
        ));

        $buff = array();

        if (is_array($cats) && count($cats) > 0) {
            foreach ($cats as $v) {
                $buff[] = $v['CategoryLinked']['category_id'];
            }
        }

        $this->set('cat_selected', $buff);

        $guide_filters = $this->FilterLinked->find('all', array(
            'conditions' => array(
                'FilterLinked.node_id' => $node_id
            ),
            'fields' => 'filter_item_id'
        ));

        $buff = array();

        if (is_array($guide_filters) && count($guide_filters) > 0) {
            foreach ($guide_filters as $v) {
                $buff[] = $v['FilterLinked']['filter_item_id'];
            }
        }

        $this->set('filter_selected', $buff);

        $this->set('data_tags', $this->Tag->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Tag.node_tag_id=Node.id')
                )
            ),
            'conditions' => array(
                'Tag.node_id' => $node_id
            ),
            'limit' => 6,
            'fields' => array('Node.*', 'Tag.*')
        )));
    }

    public function guide_copy($node_id)
    {
        $this->data = $this->Node->find('first', array(
            'joins' => array(
                array(
                    'table' => 'guides',
                    'alias' => 'Guide',
                    'type' => 'INNER',
                    'conditions' => array('Guide.node_id=Node.id')
                ), array(
                    'table' => 'guide_details',
                    'alias' => 'GuideDetail',
                    'type' => 'INNER',
                    'conditions' => array('GuideDetail.node_id=Node.id')
                )
            ),
            'conditions' => array(
                'Node.id' => $node_id
            ),
            'fields' => array('Guide.*', 'Node.*', 'GuideDetail.*')
        ));

        $this->set('images', $this->Image->find('all', array(
            'conditions' => array(
                'Image.node_id' => $node_id
            )
        )));

        $cats = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $node_id
            ),
            'fields' => 'category_id'
        ));

        $buff = array();

        if (is_array($cats) && count($cats) > 0) {
            foreach ($cats as $v) {
                $buff[] = $v['CategoryLinked']['category_id'];
            }
        }

        $this->set('cat_selected', $buff);
    }

    public function get_list_category_name($news_node_id)
    {
        $this->autoRender = false;

        $data = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $news_node_id
            )
        ));

        $str  = "";

        $tree = $this->Category->find('list', array('fields' => array('id', 'title')));

        if (is_array($data) && count($data) > 0) {
            foreach ($data as $v) {
                if (isset($tree[$v['CategoryLinked']['category_id']]))
                    $str .= $tree[$v['CategoryLinked']['category_id']] . ' ,';
            }
        }

        return trim($str, ', ');
    }


    public function guide_change($field, $id)
    {
        $change = 0;
        $data = $this->Guide->findById($id);
        if ($data['Guide'][$field] == 0)
            $change = 1;

        $this->Guide->id = $id;
        $this->Guide->saveField($field, $change);

        $this->Session->setFlash("Đã thay đổi", "success");
        $this->redirect($this->referer());
        die;
    }
}
