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

class AdminSettingserverController extends AdminAppController {

    public $uses = array('Setting', 'Admin', 'Server', 'Cloud');

    public function settinghdd_index2() {

        $this->layout = 'cart';

        $hdd_for_packages = $this->settings['hdd_for_packages'];
        $hdd_list = $this->settings['hdd_list'];

        $data['servers'] = $this->Server->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Server.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Server.*')
        ));

        $data['cloud'] = $this->Cloud->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Cloud.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Cloud.*')
        ));

        $hdds = explode("\n", $hdd_list);
        $data['hdd'] = array();

        foreach($hdds as $v)
            $data['hdd'][] = trim($v);

        $data['detail'] = array();

        $buff = array();
        $hdd_for_packages = unserialize($hdd_for_packages);


        if ($this->data) {
            $data_update = $this->data;
            
            // pr($data_update); die;
            
            $buff_update = array();

            if(isset($data_update['server']))
            {
                foreach($data_update['server'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['servers'] as $v)
                {
                    $buff['server'][$v['Server']['id']]['server_title'] = $v['Node']['title'];
                    $buff['server'][$v['Server']['id']]['server_hdd'] = $v['Server']['hdd'];
                    if(isset($buff_update[$v['Server']['id']]))
                        $buff['server'][$v['Server']['id']]['hdd'] = $buff_update[$v['Server']['id']];
                    else
                    {
                        $buff['server'][$v['Server']['id']]['hdd'] = array();
                    }
                }
            }

            $buff_update = array();


            if(isset($data_update['cloud']))
            {
                foreach($data_update['cloud'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['cloud'] as $v)
                {
                    $buff['cloud'][$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                    $buff['cloud'][$v['Cloud']['id']]['server_hdd'] = $v['Cloud']['hdd'];
                    if(isset($buff_update[$v['Cloud']['id']]))
                        $buff['cloud'][$v['Cloud']['id']]['hdd'] = $buff_update[$v['Cloud']['id']];
                    else
                    {
                        $buff['cloud'][$v['Cloud']['id']]['hdd'] = array();
                    }
                }
            }


            // pr($data_update); 
            // pr($buff); 
            // pr($data); 
            // die;

            $save = serialize($buff);

            $check = $this->Setting->findByName('hdd_for_packages');
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }

        foreach($data['servers'] as $v)
        {
            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_hdd'] = $v['Server']['hdd'];

            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_hdd'] = $v['Server']['hdd'];
            if(isset($hdd_for_packages['server'][$v['Server']['id']]['hdd']))
                $buff[$v['Server']['id']]['hdd'] = $hdd_for_packages['server'][$v['Server']['id']]['hdd'];
            else 
                $buff[$v['Server']['id']]['hdd'] = array();
        }

        $data['detail']['server'] = $buff;

        $buff = array();

        foreach($data['cloud'] as $v)
        {
            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Cloud']['id']]['server_hdd'] = $v['Cloud']['hdd'];

            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                $buff[$v['Cloud']['id']]['server_hdd'] = $v['Cloud']['hdd'];
                if(isset($hdd_for_packages['cloud'][$v['Cloud']['id']]['hdd']))
                    $buff[$v['Cloud']['id']]['hdd'] = $hdd_for_packages['cloud'][$v['Cloud']['id']]['hdd'];
                else 
                    $buff[$v['Cloud']['id']]['hdd'] = array();
        }

        $data['detail']['cloud'] = $buff;

        $this->data = $data;
    }

    public function settinghdd_index() {

        $this->layout = 'cart';

        $hdd_for_packages = $this->settings['hdd_for_packages'];
        $hdd_list = $this->settings['hdd_list'];

        $data['servers'] = $this->Server->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Server.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Server.*')
        ));

        $data['cloud'] = $this->Cloud->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Cloud.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Cloud.*')
        ));

        $hdds = explode("\n", $hdd_list);
        $data['hdd'] = array();

        foreach($hdds as $v)
            $data['hdd'][] = trim($v);

        $data['detail'] = array();

        $buff = array();
        $hdd_for_packages = unserialize($hdd_for_packages);


        if ($this->data) {
            $data_update = $this->data;
            
            // pr($data_update); die;
            
            $buff_update = array();

            if(isset($data_update['server']))
            {
                foreach($data_update['server'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['servers'] as $v)
                {
                    $buff['server'][$v['Server']['id']]['server_title'] = $v['Node']['title'];
                    $buff['server'][$v['Server']['id']]['server_hdd'] = $v['Server']['hdd'];
                    if(isset($buff_update[$v['Server']['id']]))
                        $buff['server'][$v['Server']['id']]['hdd'] = $buff_update[$v['Server']['id']];
                    else
                    {
                        $buff['server'][$v['Server']['id']]['hdd'] = array();
                    }
                }
            }

            $buff_update = array();


            if(isset($data_update['cloud']))
            {
                foreach($data_update['cloud'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['cloud'] as $v)
                {
                    $buff['cloud'][$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                    $buff['cloud'][$v['Cloud']['id']]['server_hdd'] = $v['Cloud']['hdd'];
                    if(isset($buff_update[$v['Cloud']['id']]))
                        $buff['cloud'][$v['Cloud']['id']]['hdd'] = $buff_update[$v['Cloud']['id']];
                    else
                    {
                        $buff['cloud'][$v['Cloud']['id']]['hdd'] = array();
                    }
                }
            }


            // pr($data_update); 
            // pr($buff); 
            // pr($data); 
            // die;

            $save = serialize($buff);

            $check = $this->Setting->findByName('hdd_for_packages');
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }

        foreach($data['servers'] as $v)
        {
            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_hdd'] = $v['Server']['hdd'];

            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_hdd'] = $v['Server']['hdd'];
            if(isset($hdd_for_packages['server'][$v['Server']['id']]['hdd']))
                $buff[$v['Server']['id']]['hdd'] = $hdd_for_packages['server'][$v['Server']['id']]['hdd'];
            else 
                $buff[$v['Server']['id']]['hdd'] = array();
        }

        $data['detail']['server'] = $buff;

        $buff = array();

        foreach($data['cloud'] as $v)
        {
            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Cloud']['id']]['server_hdd'] = $v['Cloud']['hdd'];

            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                $buff[$v['Cloud']['id']]['server_hdd'] = $v['Cloud']['hdd'];
                if(isset($hdd_for_packages['cloud'][$v['Cloud']['id']]['hdd']))
                    $buff[$v['Cloud']['id']]['hdd'] = $hdd_for_packages['cloud'][$v['Cloud']['id']]['hdd'];
                else 
                    $buff[$v['Cloud']['id']]['hdd'] = array();
        }

        $data['detail']['cloud'] = $buff;

        $this->data = $data;
    }

    public function settingram_index() {

        $this->layout = 'cart';

        $ram_for_packages = $this->settings['ram_for_packages'];
        $ram_list = $this->settings['ram_list'];

        $data['servers'] = $this->Server->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Server.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Server.*')
        ));

        $data['cloud'] = $this->Cloud->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Cloud.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Cloud.*')
        ));

        $rams = explode("\n", $ram_list);
        $data['ram'] = array();

        foreach($rams as $v)
            $data['ram'][] = trim($v);

        $data['detail'] = array();

        $buff = array();
        $ram_for_packages = unserialize($ram_for_packages);


        if ($this->data) {
            $data_update = $this->data;
            
            // pr($data_update); die;
            
            $buff_update = array();

            if(isset($data_update['server']))
            {
                foreach($data_update['server'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['servers'] as $v)
                {
                    $buff['server'][$v['Server']['id']]['server_title'] = $v['Node']['title'];
                    $buff['server'][$v['Server']['id']]['server_ram'] = $v['Server']['ram'];
                    if(isset($buff_update[$v['Server']['id']]))
                        $buff['server'][$v['Server']['id']]['ram'] = $buff_update[$v['Server']['id']];
                    else
                    {
                        $buff['server'][$v['Server']['id']]['ram'] = array();
                    }
                }
            }

            $buff_update = array();


            if(isset($data_update['cloud']))
            {
                foreach($data_update['cloud'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['cloud'] as $v)
                {
                    $buff['cloud'][$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                    $buff['cloud'][$v['Cloud']['id']]['server_ram'] = $v['Cloud']['ram'];
                    if(isset($buff_update[$v['Cloud']['id']]))
                        $buff['cloud'][$v['Cloud']['id']]['ram'] = $buff_update[$v['Cloud']['id']];
                    else
                    {
                        $buff['cloud'][$v['Cloud']['id']]['ram'] = array();
                    }
                }
            }


            // pr($data_update); 
            // pr($buff); 
            // pr($data); 
            // die;

            $save = serialize($buff);

            $check = $this->Setting->findByName('ram_for_packages');
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }

        foreach($data['servers'] as $v)
        {
            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_ram'] = $v['Server']['ram'];

            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_ram'] = $v['Server']['ram'];
            if(isset($ram_for_packages['server'][$v['Server']['id']]['ram']))
                $buff[$v['Server']['id']]['ram'] = $ram_for_packages['server'][$v['Server']['id']]['ram'];
            else 
                $buff[$v['Server']['id']]['ram'] = array();
        }

        $data['detail']['server'] = $buff;

        $buff = array();

        foreach($data['cloud'] as $v)
        {
            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Cloud']['id']]['server_ram'] = $v['Cloud']['ram'];

            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                $buff[$v['Cloud']['id']]['server_ram'] = $v['Cloud']['ram'];
                if(isset($ram_for_packages['cloud'][$v['Cloud']['id']]['ram']))
                    $buff[$v['Cloud']['id']]['ram'] = $ram_for_packages['cloud'][$v['Cloud']['id']]['ram'];
                else 
                    $buff[$v['Cloud']['id']]['ram'] = array();
        }

        $data['detail']['cloud'] = $buff;

        $this->data = $data;
    }

     public function settingram_index2() {

        $this->layout = 'cart';

        $ram_for_packages = $this->settings['ram_for_packages'];
        $ram_list = $this->settings['ram_list'];

        $data['servers'] = $this->Server->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Server.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Server.*')
        ));

        $data['cloud'] = $this->Cloud->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Cloud.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Cloud.*')
        ));

        $rams = explode("\n", $ram_list);
        $data['ram'] = array();

        foreach($rams as $v)
            $data['ram'][] = trim($v);

        $data['detail'] = array();

        $buff = array();
        $ram_for_packages = unserialize($ram_for_packages);


        if ($this->data) {
            $data_update = $this->data;
            
            // pr($data_update); die;
            
            $buff_update = array();

            if(isset($data_update['server']))
            {
                foreach($data_update['server'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['servers'] as $v)
                {
                    $buff['server'][$v['Server']['id']]['server_title'] = $v['Node']['title'];
                    $buff['server'][$v['Server']['id']]['server_ram'] = $v['Server']['ram'];
                    if(isset($buff_update[$v['Server']['id']]))
                        $buff['server'][$v['Server']['id']]['ram'] = $buff_update[$v['Server']['id']];
                    else
                    {
                        $buff['server'][$v['Server']['id']]['ram'] = array();
                    }
                }
            }

            $buff_update = array();


            if(isset($data_update['cloud']))
            {
                foreach($data_update['cloud'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['cloud'] as $v)
                {
                    $buff['cloud'][$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                    $buff['cloud'][$v['Cloud']['id']]['server_ram'] = $v['Cloud']['ram'];
                    if(isset($buff_update[$v['Cloud']['id']]))
                        $buff['cloud'][$v['Cloud']['id']]['ram'] = $buff_update[$v['Cloud']['id']];
                    else
                    {
                        $buff['cloud'][$v['Cloud']['id']]['ram'] = array();
                    }
                }
            }


            // pr($data_update); 
            // pr($buff); 
            // pr($data); 
            // die;

            $save = serialize($buff);

            $check = $this->Setting->findByName('ram_for_packages');
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }

        foreach($data['servers'] as $v)
        {
            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_ram'] = $v['Server']['ram'];

            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_ram'] = $v['Server']['ram'];
            if(isset($ram_for_packages['server'][$v['Server']['id']]['ram']))
                $buff[$v['Server']['id']]['ram'] = $ram_for_packages['server'][$v['Server']['id']]['ram'];
            else 
                $buff[$v['Server']['id']]['ram'] = array();
        }

        $data['detail']['server'] = $buff;

        $buff = array();

        foreach($data['cloud'] as $v)
        {
            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Cloud']['id']]['server_ram'] = $v['Cloud']['ram'];

            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                $buff[$v['Cloud']['id']]['server_ram'] = $v['Cloud']['ram'];
                if(isset($ram_for_packages['cloud'][$v['Cloud']['id']]['ram']))
                    $buff[$v['Cloud']['id']]['ram'] = $ram_for_packages['cloud'][$v['Cloud']['id']]['ram'];
                else 
                    $buff[$v['Cloud']['id']]['ram'] = array();
        }

        $data['detail']['cloud'] = $buff;

        $this->data = $data;
    }

    public function settingserver_index() {

        $this->layout = 'cart';

        $cpu_for_packages = $this->settings['cpu_for_packages'];
        $cpu_list = $this->settings['cpu_list'];

        $data['servers'] = $this->Server->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Server.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Server.*')
        ));

        $data['cloud'] = $this->Cloud->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Cloud.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Cloud.*')
        ));

        $cpus = explode("\n", $cpu_list);
        $data['cpu'] = array();

        foreach($cpus as $v)
            $data['cpu'][] = trim($v);

        $data['detail'] = array();

        $buff = array();
        $cpu_for_packages = unserialize($cpu_for_packages);


        if ($this->data) {
            $data_update = $this->data;
            
            // pr($data_update); die;
            
            $buff_update = array();

            if(isset($data_update['server']))
            {
                foreach($data_update['server'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['servers'] as $v)
                {
                    $buff['server'][$v['Server']['id']]['server_title'] = $v['Node']['title'];
                    $buff['server'][$v['Server']['id']]['server_cpu'] = $v['Server']['cpu'];
                    if(isset($buff_update[$v['Server']['id']]))
                        $buff['server'][$v['Server']['id']]['cpu'] = $buff_update[$v['Server']['id']];
                    else
                    {
                        $buff['server'][$v['Server']['id']]['cpu'] = array();
                    }
                }
            }

            $buff_update = array();


            if(isset($data_update['cloud']))
            {
                foreach($data_update['cloud'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['cloud'] as $v)
                {
                    $buff['cloud'][$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                    $buff['cloud'][$v['Cloud']['id']]['server_cpu'] = $v['Cloud']['cpu'];
                    if(isset($buff_update[$v['Cloud']['id']]))
                        $buff['cloud'][$v['Cloud']['id']]['cpu'] = $buff_update[$v['Cloud']['id']];
                    else
                    {
                        $buff['cloud'][$v['Cloud']['id']]['cpu'] = array();
                    }
                }
            }


            // pr($data_update); 
            // pr($buff); 
            // pr($data); 
            // die;

            $save = serialize($buff);

            $check = $this->Setting->findByName('cpu_for_packages');
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }

        foreach($data['servers'] as $v)
        {
            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_cpu'] = $v['Server']['cpu'];

            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_cpu'] = $v['Server']['cpu'];
            if(isset($cpu_for_packages['server'][$v['Server']['id']]['cpu']))
                $buff[$v['Server']['id']]['cpu'] = $cpu_for_packages['server'][$v['Server']['id']]['cpu'];
            else 
                $buff[$v['Server']['id']]['cpu'] = array();
        }

        $data['detail']['server'] = $buff;

        $buff = array();

        foreach($data['cloud'] as $v)
        {
            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Cloud']['id']]['server_cpu'] = $v['Cloud']['cpu'];

            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                $buff[$v['Cloud']['id']]['server_cpu'] = $v['Cloud']['cpu'];
                if(isset($cpu_for_packages['cloud'][$v['Cloud']['id']]['cpu']))
                    $buff[$v['Cloud']['id']]['cpu'] = $cpu_for_packages['cloud'][$v['Cloud']['id']]['cpu'];
                else 
                    $buff[$v['Cloud']['id']]['cpu'] = array();
        }

        $data['detail']['cloud'] = $buff;

        $this->data = $data;
    }

    public function settingserver_index2() {

        $this->layout = 'cart';

        $cpu_for_packages = $this->settings['cpu_for_packages'];
        $cpu_list = $this->settings['cpu_list'];

        $data['servers'] = $this->Server->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Server.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Server.*')
        ));

        $data['cloud'] = $this->Cloud->find('all',array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'conditions'=>array('Node.id=Cloud.node_id'),
                    'type'=>'INNER'
                )
            ),
            'fields'=>array('Node.*', 'Cloud.*')
        ));

        $cpus = explode("\n", $cpu_list);
        $data['cpu'] = array();

        foreach($cpus as $v)
            $data['cpu'][] = trim($v);

        $data['detail'] = array();

        $buff = array();
        $cpu_for_packages = unserialize($cpu_for_packages);


        if ($this->data) {
            $data_update = $this->data;
            
            // pr($data_update); die;
            
            $buff_update = array();

            if(isset($data_update['server']))
            {
                foreach($data_update['server'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['servers'] as $v)
                {
                    $buff['server'][$v['Server']['id']]['server_title'] = $v['Node']['title'];
                    $buff['server'][$v['Server']['id']]['server_cpu'] = $v['Server']['cpu'];
                    if(isset($buff_update[$v['Server']['id']]))
                        $buff['server'][$v['Server']['id']]['cpu'] = $buff_update[$v['Server']['id']];
                    else
                    {
                        $buff['server'][$v['Server']['id']]['cpu'] = array();
                    }
                }
            }

            $buff_update = array();


            if(isset($data_update['cloud']))
            {
                foreach($data_update['cloud'] as $bk=>$bv)
                {
                    if(is_numeric($bk))
                        foreach($bv as $bvl)
                            $buff_update[$bk][] = $bvl;
                }

                foreach($data['cloud'] as $v)
                {
                    $buff['cloud'][$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                    $buff['cloud'][$v['Cloud']['id']]['server_cpu'] = $v['Cloud']['cpu'];
                    if(isset($buff_update[$v['Cloud']['id']]))
                        $buff['cloud'][$v['Cloud']['id']]['cpu'] = $buff_update[$v['Cloud']['id']];
                    else
                    {
                        $buff['cloud'][$v['Cloud']['id']]['cpu'] = array();
                    }
                }
            }


            // pr($data_update); 
            // pr($buff); 
            // pr($data); 
            // die;

            $save = serialize($buff);

            $check = $this->Setting->findByName('cpu_for_packages');
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }

        foreach($data['servers'] as $v)
        {
            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_cpu'] = $v['Server']['cpu'];

            $buff[$v['Server']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Server']['id']]['server_cpu'] = $v['Server']['cpu'];
            if(isset($cpu_for_packages['server'][$v['Server']['id']]['cpu']))
                $buff[$v['Server']['id']]['cpu'] = $cpu_for_packages['server'][$v['Server']['id']]['cpu'];
            else 
                $buff[$v['Server']['id']]['cpu'] = array();
        }

        $data['detail']['server'] = $buff;

        $buff = array();

        foreach($data['cloud'] as $v)
        {
            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
            $buff[$v['Cloud']['id']]['server_cpu'] = $v['Cloud']['cpu'];

            $buff[$v['Cloud']['id']]['server_title'] = $v['Node']['title'];
                $buff[$v['Cloud']['id']]['server_cpu'] = $v['Cloud']['cpu'];
                if(isset($cpu_for_packages['cloud'][$v['Cloud']['id']]['cpu']))
                    $buff[$v['Cloud']['id']]['cpu'] = $cpu_for_packages['cloud'][$v['Cloud']['id']]['cpu'];
                else 
                    $buff[$v['Cloud']['id']]['cpu'] = array();
        }

        $data['detail']['cloud'] = $buff;

        $this->data = $data;
    }
}
