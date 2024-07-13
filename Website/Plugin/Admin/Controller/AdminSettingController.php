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

class AdminSettingController extends AdminAppController
{

    public $uses = array('Setting', 'Admin');
    public $_buildpc = array(
        'cpu' => array(
            'name' => 'Bộ vi xử lý',
            'cid' => 0,
        ),
        'main' => array(
            'name' => 'Bo mạch chủ',
            'cid' => 0,
        ),
        'ram' => array(
            'name' => 'RAM',
            'cid' => 0,
        ),
        'ssd' => array(
            'name' => 'Ổ cứng SSD',
            'cid' => 0,
        ),
        'hdd' => array(
            'name' => 'Ổ cứng HDD',
            'cid' => 0,
        ),
        'vga' => array(
            'name' => 'VGA',
            'cid' => 0,
        ),
        'power' => array(
            'name' => 'Nguồn',
            'cid' => 0,
        ),
        'case' => array(
            'name' => 'Vỏ case',
            'cid' => 0,
        ),
        'cooling' => array(
            'name' => 'Quạt tản nhiệt',
            'cid' => 0,
        ),
        'fan' => array(
            'name' => 'Tản nhiệt CPU',
            'cid' => 0,
        ),
        'coolingwater' => array(
            'name' => 'Tản nhiệt nước',
            'cid' => 0,
        ),
        'lcd' => array(
            'name' => 'Màn hình',
            'cid' => 0,
        ),
        'keyboard' => array(
            'name' => 'Bàn phím',
            'cid' => 0,
        ),
        'mouse' => array(
            'name' => 'Chuột',
            'cid' => 0,
        ),
        'headphone' => array(
            'name' => 'Tai nghe',
            'cid' => 0,
        ),
        'desk' => array(
            'name' => 'Bàn máy tính',
            'cid' => 0,
        ),
        'chair' => array(
            'name' => 'Ghế gaming',
            'cid' => 0,
        ),
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('_buildpc', $this->_buildpc);
    }

    public function setting_advanced()
    {
        $this->_role('setting_list');
        if ($this->data) {
            $data = $this->data;

            $add = $data['add'];
            $edit = $data['edit'];
            $buildpc = isset($this->data['build']) ? $this->data['build'] : null;

            $check = $this->Setting->find('all');
            $update_str_build_pc = "";

            if ($buildpc != null) {
                foreach ($this->_buildpc as $k => $v) {
                    if (isset($buildpc[$k])) {
                        $this->_buildpc[$k]['cid'] = $buildpc[$k];
                    }
                }

                $update_str_build_pc = json_encode($this->_buildpc);
            }

            foreach ($check as $v) {
                if ($v['Setting']['name'] == 'buildPC') {
                    $this->Setting->id = $v['Setting']['id'];
                    $this->Setting->saveField('value', $update_str_build_pc);
                }

                if ($v['Setting']['name'] == 'continueAdd') {
                    $this->Setting->id = $v['Setting']['id'];
                    $this->Setting->saveField('value', $add);
                }

                if ($v['Setting']['name'] == 'continueEdit') {
                    $this->Setting->id = $v['Setting']['id'];
                    $this->Setting->saveField('value', $edit);
                }
            }

            $this->redirect($this->referer());
            die;
        }

        $add = $this->Setting->findByName('continueAdd');
        $edit = $this->Setting->findByName('continueEdit');
        $buildpc = $this->Setting->findByName('buildPC');

        $data = array(
            'add' => $add['Setting']['value'],
            'edit' => $edit['Setting']['value'],
            'buildpc' => $buildpc['Setting']['value'],
        );

        $this->set('data', $data);
    }

    public function parse_file_css()
    {
        $s = $this->Setting->findById(41);
        $s = $s['Setting']['value'];

        if (!$this->is_valid_json($s)) return false;

        $arr = json_decode($s, true);
        $str = '';

        if (isset($arr['s1'])) {
            $d = $arr['s1'];

            if ($d['background'] != '')
                $str .= 'button.dropdownMenuButtonsearch, .main-menu { background: ' . $d['background'] . ' !important; }' . "\n";

            if ($d['background_2'] != '')
                $str .= '.main-menu .mn-dropdown button.dropdownMenuButtonmm { background: ' . $d['background_2'] . ' !important; }' . "\n";

            if ($d['text'] != '')
                $str .= '#s1 { color: ' . $d['link'] . ' !important; }' . "\n";

            if ($d['link'] != '')
                $str .= '#s1 a { color: ' . $d['link'] . ' !important; }' . "\n";

            if ($d['hover'] != '')
                $str .= '#s1 a:hover { color: ' . $d['hover'] . ' !important; }' . "\n";
        }

        for ($i = 2; $i <= 13; $i++) {
            $d = $arr['s' . $i];

            if ($d['background'] != '')
                $str .= '#s' . $i . ' { background: ' . $d['background'] . ' !important; }' . "\n";

            if ($d['background'] != '')
                $str .= '#s' . $i . ' .s' . $i . ' { background: ' . $d['background'] . ' !important; }' . "\n";

            if ($d['text'] != '')
                $str .= '#s' . $i . ' { color: ' . $d['text'] . ' !important; }' . "\n";

            if ($d['link'] != '')
                $str .= '#s' . $i . ' a { color: ' . $d['link'] . ' !important; }' . "\n";

            if ($d['hover'] != '')
                $str .= '#s' . $i . ' a:hover { color: ' . $d['hover'] . ' !important; }' . "\n";


            if ($i == 10) {
                if (isset($arr['s11'])) {
                    if ($arr['s11']['background'] != '')
                        $str .= '#s10 .s10 {background: ' . $arr['s11']['background'] . ' !important;}' . "\n";

                    if ($arr['s10']['background'] != '')
                        $str .= '#s10 .s10 .s10-shape {border-top: 55px solid ' . $arr['s10']['background'] . ' !important;}' . "\n";
                }
            }
        }

        $fp = fopen(WWW_ROOT . 'style.css', 'w');
        fwrite($fp, $str);
        fclose($fp);
    }


    public function get_map_coord($str_map_url)
    {
        if (preg_match('/http/', $str_map_url)) {
            $g = explode("/@", $str_map_url);
            $gm = $g[1];
            $ga = explode(',', $gm);

            return $ga[0] . ',' . $ga[1];
        }

        return $str_map_url;
    }

    public function setting_index()
    {
        $this->_role('setting_list');
        if ($this->data) {
            $save = array();
            $data = $this->data['Setting'];

            // if(isset($data['google_map']) && $data['google_map'] != '')
            //     $data['google_map']=$this->get_map_coord($data['google_map']);
            // if(isset($data['google_map_1']) && $data['google_map_1'] != '')
            //     $data['google_map_1']=$this->get_map_coord($data['google_map_1']);

            // if(isset($data['google_map_2']) && $data['google_map_2'] != '')
            //     $data['google_map_2']=$this->get_map_coord($data['google_map_2']);

            // if(isset($data['google_map_3']) && $data['google_map_3'] != '')
            //     $data['google_map_3']=$this->get_map_coord($data['google_map_3']);

            // if(isset($data['google_map_4']) && $data['google_map_4'] != '')
            //     $data['google_map_4']=$this->get_map_coord($data['google_map_4']);

            // if(isset($data['google_map_5']) && $data['google_map_5'] != '')
            //     $data['google_map_5']=$this->get_map_coord($data['google_map_5']);

            // if(isset($data['google_map_6']) && $data['google_map_6'] != '')
            //     $data['google_map_6']=$this->get_map_coord($data['google_map_6']);



            foreach ($data as $key => $value) {
                if (preg_match('/image|file/', $key)) {
                    $value = $this->remove_hostname($value);
                    // echo $key;
                    // echo $value;
                    // die;
                }

                $value = str_replace("'", '"', $value);

                $this->Setting->updateAll(
                    array('value' => "'" . $value . "'"),
                    array('name' => $key)
                );
            }

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());
            die;
        }


        $s = $this->Setting->find('all', array(
            'conditions' => array(
                'Setting.status' => 1,
                'NOT' => array(
                    'Setting.id' => 41
                )
            ),
            'order' => 'Setting.pos DESC, Setting.id ASC'
        ));

        $arr = array();

        foreach ($s as $v) {
            $arr[$v['Setting']['name']]['value'] = $v['Setting']['value'];
            $arr[$v['Setting']['name']]['classify'] = $v['Setting']['classify'];
            $arr[$v['Setting']['name']]['label'] = $v['Setting']['label'];
            $arr[$v['Setting']['name']]['type'] = $v['Setting']['type'];
            $arr[$v['Setting']['name']]['maxlength'] = $v['Setting']['maxlength'];
        }

        $this->data = $arr;
    }



    public function setting_theme()
    {
        if ($this->data) {
            $data = $this->data;

            unset($data['submit']);

            $update = json_encode($data);
            $this->Setting->id = 41;
            $this->Setting->saveField('value', $update);

            $this->parse_file_css();

            $this->Session->setFlash('Đã cập nhật cấu hình', 'success');
            $this->redirect($this->referer());

            die;
        }


        $s = $this->Setting->findById(41);
        $this->set('theme_setting', $s['Setting']['value']);
    }

    public function setting_profile()
    {
        $u = $this->Session->read('admin');

        if ($this->data) {
            $data = $this->data['Admin'];
            $current_pass = $data['current_password'];
            $new_pass = $data['new_password'];

            if (trim($new_pass) != '') {
                $current_pass = md5($current_pass);

                if ($current_pass != $u['password']) {
                    $this->Session->setFlash('Mật khẩu hiện tại không đúng');
                    $this->redirect($this->referer());
                    die;
                }

                $data['password'] = md5($new_pass);
            } else {
                unset($data['current_password']);
                unset($data['new_password']);
            }

            $this->Admin->id = $u['id'];
            $this->Admin->save($data);

            foreach ($data as $k => $v) {
                $u[$k] = $v;
            }

            $this->Session->write('admin', $u);
            $this->Session->setFlash('Cập nhật thành công', 'success');
            $this->redirect($this->referer());
            die;
        }

        $this->data = $this->Admin->findById($u['id']);
    }
}
