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


class CCacheComponent extends Component
{
    public function initialize(Controller $Controller) 
    {
        $this->Controller = $Controller;
    }

    public function cache_delete_category($type, $id)
    {
        $cat = Cache::read(PREFIX . 'cid_' . $id);
        $cnid = PREFIX . 'cnid_' . $cat['Category']['node_id'];
        $cid = PREFIX . 'cid_' . $id;
        
        Cache::delete($cid);
        Cache::delete($cnid);
    }
    
    public function get_cache($type, $node_id)
    {
        $data = array();
        $id = PREFIX . $type . '_' . $node_id;
        return Cache::read($id);
    }
    
    public function update_cache($type, $node_id, $data = array())
    {
        switch ($type) {
            case 'news':
                $this->cache_news_id($node_id);
                break;
            case 'category':
                $this->cache_categories($node_id);
                break;
            default:
                break;
        }
    }
      
    public function delete_cache($type, $node_id)
    {
        $nid = PREFIX . $type . '_' . $node_id;
        Cache::delete($nid);
    }
 
    
    public function cache_categories()
    {
        $all = $this->Controller->Category->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Node.id = Category.node_id'
                    )
                )
            ),
            'conditions' => array(
                'Node.status' => 1,
            ),
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields' => array('Node.*', 'Category.*')
        ));

        foreach($all as $v)
        {
            $key = PREFIX . '_ckey__' . $v['Category']['id'];
            Cache::delete($key);
            Cache::write(PREFIX . 'cid_' . $v['Category']['id'], $v);
        }

        Cache::delete(PREFIX . 'categories');

        $categories = $this->Controller->Category->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Node.id = Category.node_id'
                    )
                )
            ),
            'conditions' => array(
                'Node.status' => 1,
                'Category.parent_id' => null,
                'Category.menu' => 1,
            ),
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields' => array('Node.*', 'Category.*')
        ));

        $nameID = PREFIX . 'categories';
        Cache::write($nameID, $categories);

        foreach($this->Controller->category_fields as $k => $v)
        {
            $nameID = PREFIX . 'categories_' . $k;
            Cache::delete($nameID);

            $data =  $this->Controller->Category->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'nodes',
                        'alias' => 'Node',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Node.id = Category.node_id'
                        )
                    )
                ),
                'conditions' => array(
                    'Node.status' => 1,
                    'Category.' . $k => 1
                ),
                'order' => array('Node.pos'=>'desc', 'Node.id'=>'desc'),
                'fields' => array('Node.*', 'Category.*')
            ));

            Cache::write($nameID, $data);
        }

        return true;
    }
    
    public function update_cache_category($id, $cached=0)
    {
        $data = $this->Controller->Category->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id=Category.node_id')
                )
            ),
            'conditions'=>array(
                'Category.id'=>$id
            ),
            'fields'=>array('Node.*', 'Category.*')
        ));
        
        $cnid = PREFIX . 'cnid_' . $data['Category']['node_id'];
        $cid = PREFIX . 'cid_' . $data['Category']['id'];
        $cslug = PREFIX . 'cslug_' . $data['Node']['slug'];

        $el_parent_id = is_numeric($data['Category']['parent_id']) ? $data['Category']['parent_id'] : 0;
        $ctemplate = $data['Category']['template'] != '' ? $data['Category']['template'] : '0';

        $el = array(
            'node_id'=>$data['Node']['id'],
            'title'=>$data['Node']['title'],
            'slug'=>$data['Node']['slug'],
            'type'=>$data['Node']['type'],
            'link'=>$data['Category']['link'],
            'description'=>$data['Category']['description'],
            'seo_title'=>$data['Category']['seo_title'],
            'seo_keyword'=>$data['Category']['seo_keyword'],
            'seo_description'=>$data['Category']['seo_description'],
            'id'=>$data['Category']['id'],
            'parent_id'=> $el_parent_id,
            'lft'=>$data['Category']['lft'],
            'rght'=>$data['Category']['rght'],
            'ctype'=>$data['Category']['type'],
            'template'=> $ctemplate,
        );

        $child = $this->Controller->Category->find('all', array(
            'joins'=>array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Node.id = Category.node_id'
                    )
                )
            ),
            'conditions'=>array(
                'Category.parent_id'=>$data['Category']['id'],
            ),
            'fields'=>array('Node.*', 'Category.*')
        ));

        $childs = array();
        $child_ids = array();

        if(is_array($child) && count($child) > 0)
        {
            foreach($child as $val)
            {
                $ctemplate2 = $val['Category']['template'] != '' ? $val['Category']['template'] : '0';
                $child_item = array(
                    'node_id' => $val['Node']['id'],
                    'category_id' => $val['Category']['id'],
                    'title' => $val['Node']['title'],
                    'slug' => $val['Node']['slug'],
                    'robots' => $val['Node']['robots'],
                    'type'=>$val['Node']['type'],
                    'link'=>$val['Category']['link'],
                    'description'=>$val['Category']['description'],
                    'seo_title'=>$val['Category']['seo_title'],
                    'seo_keyword'=>$val['Category']['seo_keyword'],
                    'seo_description'=>$val['Category']['seo_description'],
                    'id'=>$val['Category']['id'],
                    'parent_id'=> $el_parent_id,
                    'lft'=>$val['Category']['lft'],
                    'rght'=>$val['Category']['rght'],
                    'ctype'=>$val['Category']['type'],
                    'template'=> $ctemplate2
                );


                $childs[] = $child_item;
                $child_ids[] = $val['Category']['id'];
            }

            
        }

        $el['child'] = $childs;
        $el['child_ids'] = implode(',', $child_ids);
        
        Cache::write($cnid, $data);
        Cache::write($cid, json_encode($el));
        
        if($cached == 0)
            $this->category_front();
    }
    
}