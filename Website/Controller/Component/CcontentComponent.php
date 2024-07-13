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

class CcontentComponent extends Component
{
    public function startup(Controller $Controller)
    {
        $this->Controller = $Controller;
    }

    public function getContent($type, $node_id = 0, $limit = 10, $action = '')
    {
        if ($type == 'home')
            return $this->getHomeData();

        $queryParams = $this->Controller->Cmodal->getQueryParams($type);
        $act = $queryParams['action'];

        if ($action != '')
            $act = $action;

        if (preg_match('/detail/', $act)) {
            $this->Controller->set('is_single', 1);
            $this->Controller->set('is_' . $type, 1);
            $data = $this->Controller->{$queryParams['modal']}->{$queryParams['action']}($node_id);

            if (isset($data['CategoryLinked']['category_id'])) {
                $this->Controller->currentCategory = $this->Controller->Category->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'nodes',
                            'alias' => 'Node',
                            'type' => 'INNER',
                            'conditions' => array('Node.id=Category.node_id')
                        ),
                    ),
                    'conditions' => array(
                        'Category.id' => $data['CategoryLinked']['category_id']
                    ),
                    'fields' => array('Node.*', 'Category.*'),
                ));
            }

            if (isset($data['Product']['image']))
                $this->Controller->pageImage = $data['Product']['image'] == '' ? '' : $data['Product']['image'];
            if (isset($data['News']['image']))
                $this->Controller->pageImage = $data['News']['image'] == '' ? '' : $data['News']['image'];

            // if(isset($data['Product']['image']))
            //     $this->Controller->pageImage = $data['Product']['image'] == '' ? '' : 'thumb/1200x630/' . $data['Product']['image'];
            // if(isset($data['News']['image']))
            //     $this->Controller->pageImage = $data['News']['image'] == '' ? '' : 'thumb/1200x630/' . $data['News']['image'];   

            return $data;
        }

        return $this->getArchive($node_id, $limit);
    }

    public function get_filter_price($current_category, $return_array)
    {
        if (!is_array($current_category) || count($current_category) <= 0) return $return_array;

        if (
            isset($current_category['Category']['filter_price'])
            && trim($current_category['Category']['filter_price']) != ""
        ) {
            $filterData = explode("\r", $current_category['Category']['filter_price']);
            $filters = array();

            foreach ($filterData as $v) {
                $txt_arr = explode('|', $v);
                $filter = $txt_arr[1];
                $filter_arr = explode('-', $filter);

                $txt = trim($txt_arr[0]);
                $min = isset($filter_arr[0]) ? trim($filter_arr[0]) : 0;
                $max = isset($filter_arr[1]) ? trim($filter_arr[1]) : 0;

                $item = array(
                    'txt' => $txt,
                    'min' => $min,
                    'max' => $max,
                );
                $filters[] = $item;
            }

            return $filters;
        } else {
            if (is_numeric($current_category['Category']['parent_id']) && $current_category['Category']['parent_id'] > 0) {
                $check = $this->getDetailCategory($current_category['Category']['parent_id']);

                if (is_array($check) && count($check) > 0)
                    return $this->get_filter_price($check, $return_array);
                else
                    return $return_array;
            } else
                return $return_array;
        }

        return $return_array;
    }

    public function get_filter_hang($current_category, $return_array)
    {
        if (!is_array($current_category) || count($current_category) <= 0) return $return_array;

        if (
            isset($current_category['Category']['hang_id_list'])
            && trim($current_category['Category']['hang_id_list']) != ""
        ) {
            $filterData = explode(",", $current_category['Category']['hang_id_list']);

            $filters = array();

            foreach ($filterData as $v) {
                $hang = $this->Controller->Hang->findById($v);

                if (is_array($hang) && count($hang) > 0) {
                    $item = $hang['Hang'];
                    $filters[] = $item;
                }
            }

            return $filters;
        } else {
            if (is_numeric($current_category['Category']['parent_id']) && $current_category['Category']['parent_id'] > 0) {
                $check = $this->getDetailCategory($current_category['Category']['parent_id']);

                if (is_array($check) && count($check) > 0)
                    return $this->get_filter_hang($check, $return_array);
                else
                    return $return_array;
            } else
                return $return_array;
        }

        return $return_array;
    }

    public function get_filter_filter($current_category, $return_array)
    {
        if (!is_array($current_category) || count($current_category) <= 0) return $return_array;

        if (
            isset($current_category['Category']['filter_id_list'])
            && trim($current_category['Category']['filter_id_list']) != ""
        ) {
            $filterData = explode(",", $current_category['Category']['filter_id_list']);

            $filters = array();

            foreach ($filterData as $v) {
                $d = $this->Controller->Filter->findById($v);

                if (is_array($d) && count($d) > 0) {
                    $item = $d['Filter'];
                    $filters[] = $item;
                }
            }

            return $filters;
        } else {
            if (is_numeric($current_category['Category']['parent_id']) && $current_category['Category']['parent_id'] > 0) {
                $check = $this->getDetailCategory($current_category['Category']['parent_id']);

                if (is_array($check) && count($check) > 0)
                    return $this->get_filter_filter($check, $return_array);
                else
                    return $return_array;
            } else
                return $return_array;
        }

        return $return_array;
    }

    public function getDetailCategory($cid)
    {
        $d = Cache::read(PREFIX . 'cid_' . $cid);

        if ($d === false) {
            $d = $this->Controller->Category->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'nodes',
                        'alias' => 'Node',
                        'type' => 'INNER',
                        'conditions' => array('Node.id=Category.node_id')
                    ),
                ),
                'conditions' => array(
                    'Category.id' => $cid
                ),
                'fields' => array('Node.*', 'Category.*'),
            ));
        }

        return $d;
    }

    public function getArchive($node_id, $limit)
    {
        $currentCategory = $this->Controller->Category->find('first', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Node.id=Category.node_id')
                ),
            ),
            'conditions' => array(
                'Node.id' => $node_id
            ),
            'fields' => array('Node.*', 'Category.*'),
        ));

        $currentCategory['_price'] = $this->get_filter_price($currentCategory, array());
        $currentCategory['_filter'] = $this->get_filter_filter($currentCategory, array());
        $currentCategory['_hang'] = $this->get_filter_hang($currentCategory, array());

        $this->Controller->currentCategory = $currentCategory;

        $node_type = $this->Controller->currentCategory['Category']['type'];

        if ($node_type == 'page') {
            $this->Controller->set('is_' . $node_type, 1);
            // return $this->Controller->Page->page_detail($node_id);


            // $this->is_mobile = $is_mobile;
            // if(isset($currentCategory)){

            // if(isset($_GET['dev']))
            //     pr($currentCategory);


            if (
                isset($this->Controller->category_cid_landingpage)
                && is_array($this->Controller->category_cid_landingpage)
                && count($this->Controller->category_cid_landingpage) > 0
                && in_array($currentCategory['Category']['id'], $this->Controller->category_cid_landingpage)
            ) {
                $this->Controller->lang = 'vi';
                $this->Controller->set('lang', 'vi');
                $this->Controller->Session->write('lang', 'vi');
            }
            // }



            return $currentCategory;
        }

        if ($node_type == 'link') {
            $this->Controller->redirect($this->Controller->currentCategory['Category']['link']);
            die;
        }

        if ($node_type == 'link_inline') {
            $link_inline = $this->Controller->LinkInline->link_detail($this->Controller->currentCategory['Category']['id']);
            return $this->getContent($link_inline['data_type'], $node_id = $link_inline['node_id'], $limit = 1, $action = $link_inline['data_type'] . '_detail');
            die;
        }

        $pagination_limit_setting = $node_type . '_pagination_limit';

        if (isset($this->Controller->{$pagination_limit_setting}))
            $limit = $this->Controller->{$pagination_limit_setting};

        if ($this->Controller->currentCategory['Category']['type'] == 'ketnoi')
            $this->Controller->paginate = $this->Controller->Ketnoi->getPaginate($this->Controller->currentCategory['Category']['id'], $limit);
        else
            $this->Controller->paginate = $this->Controller->Node->getPaginate($this->Controller->currentCategory['Category']['id'], $node_type, $limit, $this->Controller->sort, 'desc', $this->Controller->filter_price);
        $this->Controller->set('is_archive', 1);

        $this->Controller->set('is_' . $node_type, 1);

        if (in_array($node_type, $this->Controller->hook)) {
            $mdl = ucfirst($node_type);
            // $extra_data = $this->Controller->{$mdl}->hook($this->Controller->currentCategory['Category']['id']);
            // $this->Controller->set('extra_data', $extra_data);
            $this->Controller->paginate = $this->Controller->{$mdl}->hook($this->Controller->currentCategory, $limit);
        }

        $data = $this->Controller->paginate('Node');

        if (isset($data[0]['Product']['image']))
            $this->Controller->pageImage = $data[0]['Product']['image'] == '' ? '' : 'thumb/1200x630/' . $data[0]['Product']['image'];
        if (isset($data[0]['News']['image']))
            $this->Controller->pageImage = $data[0]['News']['image'] == '' ? '' : 'thumb/1200x630/' . $data[0]['News']['image'];
        if (isset($data[0]['Category']['image']))
            $this->Controller->pageImage = $data[0]['Category']['image'] == '' ? '' : 'thumb/1200x630/' . $data[0]['Category']['image'];

        return $data;

        // return $this->Controller->paginate('Node');
    }

    public function getHomeData()
    {
        $this->Controller->set('is_home', 1);
        return $this->Controller->Home->getHomeData();
    }
}
