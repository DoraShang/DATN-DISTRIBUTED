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

class AdminCategoryController extends AdminAppController
{

    public $uses = array('Admin.Category', 'Node', 'Page', 'Filter', 'Hang', 'Page');
    public $components = array('CCache', 'Cookie', 'Session');
    public $limit = 10;

    public $page_template = array(
        'page_whyadt' => 'trang why ADT',
        'page_gallery' => 'trang tin tức gallery',



        // 'page_service' => 'trang travel service',
        // 'page-tochuc'=>'Ban Lãnh đạo',
        // 'page-hoivien'=>'Hội viên',
        // 'page_hocsinh'=>'Học sinh',
        // 'page_dichvu'=>'Page dịch vụ',
        // 'page_giangvien'=>'Giảng viên',
        // 'page-ketnoi'=>'Kết nối',
        // 'page_gioithieu'=>'Giới thiệu',
        // 'page_lotrinhhoc'=>'Lộ trình học',
        // 'page_dangkyhoc'=>'Đăng ký học',
        // 'page_dangkykiemtra'=>'Đăng ký kiểm tra',
        // 'page_goctienganh'=>'Góc tiếng anh',
        // 'page-landing'=>'Landing Page',
        // 'page-landing-2'=>'Landing Page Mẫu 2',
        // 'page-landing-3'=>'Landing Page Mẫu 3',
        // 'page-daily'=>'Đại lý',
        // 'page-doitac'=>'Đối tác',
        // 'baiduthi'=>'Bài dự thi',
        //  'album'=>'Thư viện ảnh',
        // 'videos'=>'Thư viện video',
        // 'dailyphanphoi'=>'Đại lý phân phối',
        // 'canho'=>'Căn hộ',
        // 'vitri'=>'Vị trí dự án',
        // 'page-nangluc'=>'Năng lực',
        // 'page-thanhtuu'=>'Thành tựu',
        //        'lienhe'=>'Liên hệ',
        //        'dangkytenmien'=>'Đăng ký tên miền',
        //        'pagecode'=>'Page theo định dạng: mô tả -> Mã html -> Nội dung',
    );

    public $news_template = array(
        'news_list' => 'Tin tức',
        // 'news_blog'=>'Blog',
    );

    public $category_type = array(
        'page' => 'Trang page',
        'link' => 'Chọn link',
        'link_inline' => 'Link tới bài viết',
        'news' => 'Tin tức',
        'product' => 'Sản phẩm',
        // 'guide' => 'Guide',
        'service' => 'Dịch vụ',
        // 'tochuc'=>'Tổ chức',
        //  'ketnoi'=>'Kết nối',
        // 'document'=>'Tài liệu',
        // 'tiendo'=>'Tiến độ',
        // 'picture'=>'Hình ảnh',
        // 'video'=>'Videos',
        // 'sale'=>'Khuyến mãi',
        // 'rate'=>'Khách hàng đánh giá',
        // 'collection'=>'Bộ sưu tập',
        // 'tuvan'=>'Tư vấn',
        // 'adv' => 'Quảng cáo',
        // 'cloud' => 'VPS - Cloud',
        // 'colo' => 'Colocation',
        // 'domain' => 'Tên miền',
        // 'server' => 'Server',
        // 'hosting' => 'Hosting',
        // 'email' => 'Email',
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        $pages = $this->Page->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'conditions' => array('Node.id=Page.node_id'),
                    'type' => 'INNER'
                )
            ),
            'conditions' => array(
                'Node.status' => 1
            ),
            'fields' => array('Node.*', 'Page.*')
        ));

        $page_buff = array();
        foreach ($pages as $v) {
            $page_buff[$v['Page']['id']] = $v['Node']['title'];
        }

        $this->set('pages', $page_buff);

        $this->set('category_type', $this->category_type);
        $this->set('news_template', $this->news_template);

        $this->set('hang_list', $this->Hang->find('list', array(
            'fields' => array(
                'id', 'title'
            )
        )));

        $this->set('filter_list', $this->Filter->find('list', array(
            'fields' => array(
                'id', 'title'
            )
        )));

        $this->set('limit', $this->limit);

        $page_custom = $this->Page->find('all', array());
        if (is_array($page_custom) && count($page_custom) > 0) {

            $tmp_page = array();
            foreach ($page_custom as $v) {
                $tmp_page[$v['Page']['slug']] = $v['Page']['title'];
            }
            $this->page_template = array_merge($this->page_template, $tmp_page);
        }
        $this->set('page_template', $this->page_template);
    }

    public function category_list($category_id = null)
    {
        $this->_role('category_list');
        $this->data = $this->Category->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Category.node_id = Node.id'),
                ),
            ),
            'conditions' => array(
                'Category.parent_id' => $category_id
            ),
            'order' => 'Node.pos DESC, Category.id DESC',
            'fields' => array('Category.*', 'Node.*')
        ));

        if ($category_id == null)
            $this->set('get_category_id', 0);
        else
            $this->set('get_category_id', $category_id);
    }

    public function category_add()
    {
        $this->_role('category_add');
        if ($this->data) {
            $data = $this->data['Category'];

            $data['hang_id_list'] = isset($this->data['lkHang']) ? implode(',', $this->data['lkHang']) : "";
            $data['filter_id_list'] = isset($this->data['lkBoloc']) ? implode(',', $this->data['lkBoloc']) : "";

            $data_node = $this->data['Node'];
            // if(trim($data_node['slug']) == '')
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));

            $data_node['type'] = 'category';
            $data_node['created'] = time();
            $data_node['modified'] = time();

            $check = $this->Node->findBySlug($data_node['slug']);
            if (is_array($check) && count($check) > 0) {
                // $this->Session->setFlash('Tên mục lục đã tồn tại', 'error');
                // $this->redirect($this->referer());
                $data_node['slug'] = $data_node['slug'] . '-' . time();
            }

            $data['title'] = $data_node['title'];
            $data['seo_keyword'] = preg_replace('/\r|\n/', ' ', $data['seo_keyword']);
            $data['seo_keyword'] = preg_replace('/\s+/', ' ', $data['seo_keyword']);
            // $data['seo_description'] = preg_replace('/\r|\n/', ' ', $data['seo_description']);
            // $data['seo_description'] = preg_replace('/\s+/', ' ', $data['seo_description']);

            if (isset($data['image']))
                $data['image'] = $this->remove_hostname($data['image']);

            if (isset($data['image_hover']))
                $data['image_hover'] = $this->remove_hostname($data['image_hover']);
            // $data['image_single'] = $this->remove_hostname($data['image_single']);

            $words = explode(' ', $data_node['title']);
            $data['word_count'] = count($words);

            if ($data['link_inline'] != '0') {
                $link_inline = trim($data['link_inline']);

                if ($link_inline != '') {
                    if (is_numeric($link_inline)) {
                        $data['link_inline'] = $link_inline;
                    } else {
                        $l = trim($link_inline, '/ ');
                        $sarr = explode('/', $l);
                        $sl = str_replace('.html', '', end($sarr));

                        $link_inline_slug = $sl;
                        echo $link_inline_slug;

                        $check_nodes = $this->Node->find('first', array(
                            'conditions' => array(
                                'Node.slug' => $link_inline_slug
                            )
                        ));

                        if (is_array($check_nodes) && count($check_nodes) > 0) {
                            $data['link_inline'] = $check_nodes['Node']['id'];
                        }
                    }
                }
            }

            if (isset($this->data['Images'])) {
                $images = $this->data['Images'];
                $data_images = array();

                foreach ($images as $v) {
                    $data_images[] = $this->remove_hostname($v);
                }

                $data['images'] = implode(',', $data_images);
            }

            if (isset($this->data['Video'])) {
                $videos = $this->data['Video'];
                $data_video = array();

                foreach ($videos as $v) {
                    $data_video[] = $v;
                }

                $data['videos'] = implode(',', $data_video);
            }

            $this->Node->save($data_node);
            $data['node_id'] = $this->Node->getLastInsertID();

            $this->Category->save($data);

            $this->CCache->cache_categories();

            $this->Session->setFlash('Đã thêm mục lục', 'success');
            $this->redirect($this->get_redirect('category', 'add', $data['node_id']));
            die;
        }
    }

    public function category_edit($id = null)
    {
        $this->_role('category_edit');
        if ($this->data) {
            //pr($this->data);
            $data = $this->data['Category'];
            $data_node = $this->data['Node'];

            $data['hang_id_list'] = isset($this->data['lkHang']) ? implode(',', $this->data['lkHang']) : "";
            $data['filter_id_list'] = isset($this->data['lkBoloc']) ? implode(',', $this->data['lkBoloc']) : "";

            $data_node['slug'] = str_replace(' ', '', strtolower($data_node['slug']));
            $data_node['type'] = 'category';
            $data_node['modified'] = time();

            $words = explode(' ', $data_node['title']);
            $data['word_count'] = count($words);

            if (isset($this->data['news_template']) && $data['type'] == 'news') {
                $data['template'] = $this->data['news_template'];
            }

            if (isset($this->data['tech'])) {
                $data_tech = $this->data['tech'];
                $data_tech_2 = array();
                foreach ($data_tech as $k => $v) {
                    foreach ($v as $key => $val) {
                        // pr($key); 
                        // pr($val); 
                        if ($key == 'option') {
                            switch ($val) {
                                case 'RAM':
                                    $val = 'ram';
                                    break;
                                case 'IP Address':
                                    $val = 'ip';
                                    break;
                                case 'CPU':
                                    $val = 'cpu';
                                    break;
                                case 'Bandwidth':
                                    $val = 'bandwidth';
                                    break;
                                case 'HDD/SSD':
                                    $val = 'hdd';
                                    break;

                                default:
                                    break;
                            }
                            // pr($val);
                        }
                        $data_tech_2[$k][$key] = trim($val);
                    }
                }



                $buff = array();
                $data_tech_3 = array();

                foreach ($data_tech_2 as $v) {
                    // if(isset($buff[$v['option']]))
                    $buff[$v['option']][] = $v;
                    // else
                    // $buff[] = $v;
                }

                foreach ($buff as $k => $v) {
                    foreach ($v as $val) {
                        $data_tech_3[] = $val;
                    }
                }


                $data['tech'] = serialize($data_tech_3);

                // pr($data_tech_2); 
                // pr($data['tech']); die;
            }




            if ($data['link_inline'] != '0') {
                $link_inline = trim($data['link_inline']);

                if ($link_inline != '') {
                    if (is_numeric($link_inline)) {
                        $data['link_inline'] = $link_inline;
                    } else {
                        $l = trim($link_inline, '/ ');
                        $sarr = explode('/', $l);
                        $sl = str_replace('.html', '', end($sarr));

                        $link_inline_slug = $sl;
                        echo $link_inline_slug;

                        $check_nodes = $this->Node->find('first', array(
                            'conditions' => array(
                                'Node.slug' => $link_inline_slug
                            )
                        ));

                        if (is_array($check_nodes) && count($check_nodes) > 0) {
                            $data['link_inline'] = $check_nodes['Node']['id'];
                        }
                    }
                }
            }


            $check = $this->Node->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'categories',
                        'alias' => 'Category',
                        'type' => 'INNER',
                        'conditions' => array('Category.node_id=Node.id')
                    )
                ),
                'conditions' => array(
                    'Node.slug' => $data_node['slug'],
                    'NOT' => array(
                        'Node.id' => $data_node['id']
                    )
                ),
                'fields' => array('Node.*', 'Category.*')
            ));

            if (is_array($check) && count($check) > 0) {
                if ($check['Category']['id'] != $data['id']) {
                    // $this->Session->setFlash('Tên mục lục đã tồn tại', 'error');
                    // $this->redirect($this->referer());
                    $data_node['slug'] = $data_node['slug'] . '-' . time();
                }
            }
            $this->Node->id = $data_node['id'];
            unset($data_node['id']);
            $this->Node->save($data_node);

            $data['title'] = $data_node['title'];
            $data['seo_keyword'] = preg_replace('/\r|\n/', ' ', $data['seo_keyword']);
            $data['seo_keyword'] = preg_replace('/\s+/', ' ', $data['seo_keyword']);
            // $data['seo_description'] = preg_replace('/\r|\n/', ' ', $data['seo_description']);
            // $data['seo_description'] = preg_replace('/\s+/', ' ', $data['seo_description']);

            if (isset($data['image']))
                $data['image'] = $this->remove_hostname($data['image']);

            if (isset($data['image_hover']))
                $data['image_hover'] = $this->remove_hostname($data['image_hover']);

            // $data['image'] = $this->remove_hostname($data['image']);
            // $data['image_hover'] = $this->remove_hostname($data['image_hover']);
            $data['images'] = '';
            $data['videos'] = '';
            // $data['image_single'] = $this->remove_hostname($data['image_single']);

            if (isset($this->data['Images'])) {
                $images = $this->data['Images'];
                $data_images = array();

                foreach ($images as $v) {
                    $data_images[] = $this->remove_hostname($v);
                }

                $data['images'] = implode(',', $data_images);
            }

            if (isset($this->data['Video'])) {
                $videos = $this->data['Video'];
                $data_video = array();

                foreach ($videos as $v) {
                    $data_video[] = $v;
                }

                $data['videos'] = implode(',', $data_video);
            }

            $check_cat = $this->Category->find('first', array(
                'conditions' => array(
                    'Category.node_id' => $id
                )
            ));

            $this->Category->id = $check_cat['Category']['id'];
            $this->Category->save($data);

            $this->CCache->cache_categories();
            $this->Session->setFlash('Đã sửa mục lục', 'success');
            $this->redirect($this->get_redirect('category', 'edit', $id));
            die;
        }

        $this->data = $this->Category->find('first', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'conditions' => array('Node.id = Category.node_id'),
                    'type' => 'INNER'
                )
            ),
            'conditions' => array(
                'Node.id' => $id,
            ),
            'fields' => array('Node.*, Category.*')
        ));


        $this->set('images', explode(',', $this->data['Category']['images']));
        $this->set('videos', explode(',', $this->data['Category']['videos']));
    }

    public function update_field($field, $category_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Category->findById($category_id);
        if ($check['Category'][$field] == 1) {
            $changed = 0;
        }

        $this->Category->id = $category_id;
        $this->Category->saveField($field, $changed);

        $this->CCache->cache_categories();

        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }
}
