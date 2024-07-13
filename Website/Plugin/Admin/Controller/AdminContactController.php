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

App::import('Vendor', 'phpexcel');

class AdminContactController extends AdminAppController
{
    public $uses = array('Contact', 'Node', 'Hang');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $hangs =  $this->Hang->find('list', array('id', 'title'));
        $this->set('hangs', $hangs);
    }

    public function check_reports($form_num)
    {
        // $this->autoRender = false;

        $num = $this->Contact->find('count', array(
            'conditions' => array(
                'Contact.status' => 0,
                'Contact.form' => $form_num
            ),
        ));

        return $num;
    }

    public function export_excel()
    {
        $this->autoRender = false;
        $data = $this->Contact->find('all', array(
            'conditions' => array(
                'Contact.form > ' => 0,
            ),
            'order' => array('Contact.status' => 'asc', 'Contact.id' => 'desc'),
        ));


        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $i = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, 'Họ tên');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Điện thoại');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, 'Form');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, 'Độ tuổi');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, 'Khóa Học');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, 'Ngày');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);


        foreach ($data as $v) {
            $i++;

            $formdk = isset($this->form_dk[$v['Contact']['form']]) ? $this->form_dk[$v['Contact']['form']] : '';
            $age = isset($this->hangs[$v['Contact']['age']]) ? $this->hangs[$v['Contact']['age']] : '';
            $date = date('d/m/Y', $v['Contact']['created']);

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $v['Contact']['fullname']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $v['Contact']['email']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $v['Contact']['phone']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $formdk);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $age);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $v['Contact']['content']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $date);
        }

        $time = time();
        $d = date('d-m-Y', $time);
        $filename = 'data-form-' . $d . '.xls';

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=" . $filename);
        header("Content-Transfer-Encoding: binary ");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        // $objWriter->save('some_excel_file.xlsx'); 
        $objWriter->save('php://output');
    }

    public function contact_list()
    {
        $conn = array();
        $form_num = isset($_GET['form_type']) && $_GET['form_type'] != '' ? $_GET['form_type'] : 0;

        $view_tuvan = array(1, 2, 5, 7, 8);
        $view_hocthu = array(3, 4, 6);
        $view_dangkyfiles = array(9);
        $view_all = array(999);

        $conn = array();
        $conn['Contact.form'] = $form_num;

        if ($form_num == 999) {
            unset($conn['Contact.form']);
            $conn['Contact.form >'] = 0;
        }

        $this->paginate = array(
            'conditions' => $conn,
            'limit' => 12,
            'order' => array('Contact.status' => 'asc', 'Contact.id' => 'desc'),
        );

        $this->data = $this->paginate('Contact');

        // if(in_array($form_num, $view_tuvan))
        //     $this->render('contact_tuvan');
        // else if(in_array($form_num, $view_hocthu))
        //     $this->render('contact_hocthu');
        // else if(in_array($form_num, $view_dangkyfiles))
        //     $this->render('contact_dangkyfile');
        // else if(in_array($form_num, $view_all))
        //     $this->render('contact_all');
        // else
        //     $this->render('contact_list');
    }


    public function update_status($node_id)
    {
        $updated = 0;
        $check = $this->Contact->findById($node_id);
        if ($check['Contact']['status'] == 0)
            $updated = 1;

        $this->Contact->id = $node_id;
        $this->Contact->saveField('status', $updated);

        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function contact_delete($id = null)
    {
        $this->autoRender = false;
        $this->Contact->id = $id;
        $this->Contact->delete($id);
        $this->Session->setFlash('Đã xóa bình luận');
        $this->redirect($this->referer());
    }
}
