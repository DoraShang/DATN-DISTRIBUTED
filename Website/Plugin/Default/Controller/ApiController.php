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

class ApiController extends DefaultAppController
{
	public $uses = array('CategoryLinked');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->autoRender = false;
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}

	public function get_last_id()
	{
		$lastID = 0;

		$data = $this->Node->find('first', array(
			'conditions' => array(
				'Node.type'=>'news'
			),
			'order' => array('Node.source_id' => 'desc', 'Node.id' => 'desc')
		));

		if(is_array($data) && count($data) > 0)
		{
			$lastID = $data['Node']['source_id'];
		}

		echo $lastID;
		die;
	}

	public function insert_data($news_id)
	{
		$url = trim(API_DOMAIN, ' /');
		$url .= '/default/api/get/' . $news_id;
		$data = $this->Capp->curl_html($url);

		// pr($data);
		if($this->Capp->is_valid_json($data))
		{
			$d = json_decode($data);

			if($d->title != '')
			{
				$node = array(
					'source_id' => $d->news_id,
					'title' => $d->title,
					'slug' => $d->slug,
					'type'=>'news'
				);
	            $this->Node->save($node);
	            $node_id = $this->Node->getLastInsertID();

	            $data = array();
	            $data['image'] = $d->image;
	            $data['description'] = $d->description;
	            $data['content'] = $d->content;
	            $data['node_id'] = $node_id;
	            $this->News->save($data);

	            if(isset($d->category_txt))
	            {
	            	$check = $this->Category->findByTitle($d->category_txt);

	            	if(is_array($check) && count($check) > 0)
	            	{
	            		$this->CategoryLinked->create();

	                    $this->CategoryLinked->save(array(
	                        'category_id'=>$check['Category']['id'],
	                        'node_id'=>$node_id
	                    ));
	            	}
	            }

				echo '1';
				die;
			}
		}

		echo '0';
		die;
	}
}