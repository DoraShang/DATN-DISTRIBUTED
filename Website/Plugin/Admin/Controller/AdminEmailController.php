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
App::import('Vendor', 'phpmailer', array('file' => 'phpmailer'.DS.'PHPMailerAutoload.php'));

class AdminEmailController extends AdminAppController {

    public $uses = array('Email', 'Admin.Category', 'Node', 'CategoryLinked', 'Customer');

    public $type = 'email';
    public $tbl = 'Email';
    public $table = 'emails';
    public $form_title = 'Email';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('form_title', $this->form_title);
        $this->set('type', $this->type);
        $this->set('tbl', $this->tbl);

        $this->get_categories(array('type'=>'email'));
    }


    public function send_smtp_mail() {
        $this->autoRender = false;
        $to = $this->data['email']; 
        $subject = $this->data['title']; 
        $message = $this->data['content']; 

        $sender = $this->settings['smtp_email']; 

        $dm = parse_url(DOMAIN);
        $domain = '';
        if(isset($dm['host']))
            $domain = $dm['host'];

        if($domain == '') return;

        $from = 'noreply@' . $domain;
     
        $header =  'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $to . "\r\n" .
                    "X-Mailer: PHP/".phpversion() . "Return-Path: $sender";
     
        $mail = new PHPMailer();
        $mail->charSet = 'UTF-8';
        $mail->CharSet = 'UTF-8';
     
        $mail->IsSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = $this->settings['smtp_host']; 
        $mail->SMTPSecure = trim($this->settings['smtp_secure']) == 1 ? 'ssl' : '';
        $mail->Port = $this->settings['smtp_port'];
        $mail->Username   = trim($this->settings['smtp_email']);  
        $mail->Password   = trim($this->settings['smtp_pass']);

        $mail->SMTPDebug  = 0; // turn it off in production 0 or 1
        $mail->From = $sender;
        $mail->FromName = $this->settings['title'];
        $mail->FromName = $subject;
     
        $mail->AddAddress($to);
     
        $mail->IsHTML(true);
        $mail->CreateHeader($header);
     
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);
        $mail->AltBody = nl2br($message);
     
        // return an array with two keys: error & message
        if(!$mail->Send()) {
          $msg = array('error' => true, 'message' => 'Mailer Error: ' . $mail->ErrorInfo);
        } else {
          $msg = array('error' => false, 'message' =>  "Message sent!");
        }

        echo json_encode($msg); 
        die;
    }

    public function email_list($node_id = null) 
    {
        $this->_role('email_list');
            
        $data = $this->Customer->find('all');
        $lst = array();
        foreach($data as $v)
        {
            $lst[$v['Customer']['email']] = $v['Customer']['email'] . ' - (' . $v['Customer']['fullname'] . ')';
        }

        $this->set('thanhvien', $lst);
    }

    public function add($node_id = null) {
        if($this->data)
        {
            $data = $this->data[$this->tbl];
            $data_node = $this->data['Node'];
            $data_node['type'] = 'email';
            
            $data['image'] = $this->remove_hostname($data['image']);

            $data['price'] = preg_replace('/[^0-9]/', '', $data['price']);
            $data['price2y'] = preg_replace('/[^0-9]/', '', $data['price2y']);
            $data['price3y'] = preg_replace('/[^0-9]/', '', $data['price3y']);
            $data['price4y'] = preg_replace('/[^0-9]/', '', $data['price4y']);
            $data['price5y'] = preg_replace('/[^0-9]/', '', $data['price5y']);

            $this->Node->save($data_node);
            $data['node_id'] = $this->Node->getLastInsertId();


            $this->{$this->tbl}->save($data);
            $lastID = $this->{$this->tbl}->getLastInsertId();

            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect(DOMAINAD . 'admin_'.$this->type.'/edit/' . $lastID);
        }
    }

    public function setting()
    {
        $f = 'email-compare';
        $s = $this->settings[$f];
        $setting = unserialize($s);

        $fields = array(
            'space' => 'Dung lượng', 
            'email' => 'Địa chỉ Email', 
            'forwarder' => 'Email forwarder', 
            'mail_list' => 'Mail list', 
            'park' => 'Park domain', 
            'price' => 'Giá thành',
            'price2y' => 'Giá đk >= 2 năm',
            'price3y' => 'Giá đk >= 3 năm',
            'price4y' => 'Giá đk >= 4 năm',
            'price5y' => 'Giá đk >= 5 năm',
            'email_category'=>'Danh mục',
        );


        if($this->data)
        {
            $data = $this->data;

            $buff = array();

            foreach($fields as $k=>$v)
            {
                if(isset($data[$k]['show']))
                {
                    $buff[$k]['show'] = $data[$k]['show'];
                    $buff[$k]['title'] = $v;
                }
                else
                {
                    $buff[$k]['title'] = $fields[$k];
                    $buff[$k]['show'] = 0;
                }
            }

            $save = serialize($buff);

            $check = $this->Setting->findByName($f);
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->redirect(DOMAINAD . 'admin_'.$this->type.'/setting');
            die;
        }

        $data = array();

        foreach($fields as $k=>$v)
        {
            if(isset($setting[$k]))
            {
                $data[$k]['title'] = $fields[$k];
                $data[$k]['show'] = $setting[$k]['show'];
            }
            else
            {
                $data[$k]['title'] = $fields[$k];
                $data[$k]['show'] = 0;
            }
        }

        $this->data = $data;
    }

    public function edit($id = null) {
        if ($this->data) {
            $data = $this->data[$this->tbl];
            $data_node = $this->data['Node'];
            $data_node['type'] = 'email';

            if(isset($data['image']))
            $data['image'] = $this->remove_hostname($data['image']);

            $data['price'] = preg_replace('/[^0-9]/', '', $data['price']);
            $data['price2y'] = preg_replace('/[^0-9]/', '', $data['price2y']);
            $data['price3y'] = preg_replace('/[^0-9]/', '', $data['price3y']);
            $data['price4y'] = preg_replace('/[^0-9]/', '', $data['price4y']);
            $data['price5y'] = preg_replace('/[^0-9]/', '', $data['price5y']);

            $check = $this->{$this->tbl}->findById($id);
            $node_id = $check[$this->tbl]['node_id'];

            $this->Node->id = $node_id;
            $this->Node->save(array(
                'title'=>$data_node['title'],
                'status'=>$data_node['status'],
            ));

            $this->{$this->tbl}->id = $id;
            $this->{$this->tbl}->save($data);

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect(DOMAINAD . 'admin_'.$this->type.'/index/');
        }

        $this->data = $this->{$this->tbl}->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array($this->tbl . '.node_id = Node.id')
                ),
            ),
            'conditions'=>array(
                $this->tbl.'.id'=>$id
            ),
            'fields'=>array('Node.*', $this->tbl . '.*')
        ));
    }

    public function copy($node_id)
    {
        $this->edit($node_id);
    }

    public function count_node($node_id)
    {
        $this->autoRender = false;

        $tbl = $this->tbl;

        return $this->{$this->tbl}->find('count', array(
            'conditions'=>array(
                $tbl . '.node_id'=>$node_id
            )
        ));
    }

    public function update_field($field, $tbl_id)
    {
        $change = 0;
        $data = $this->{$this->tbl}->findById($tbl_id);
        if($data[$this->tbl][$field] == 0)
            $change = 1;
        
        $this->{$this->tbl}->id = $tbl_id;
        $this->{$this->tbl}->saveField($field, $change);

        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }

    public function delete($id = null)
    {
        $this->autoRender = false;
        $this->{$this->tbl}->delete($id);
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }
}
