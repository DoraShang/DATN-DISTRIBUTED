<?php

App::uses('CKEditor', 'Vendor');
App::uses('CKFinder', 'Vendor');
class AdminCartController extends AdminAppController
{
	public $uses = array('Customer', 'Order', 'City');

	public function cart_list($all = null)
	{
		// $this->layout = 'cart';

		$status = 0;

		if ($all != null)
			$status = array(0, 1);

		$customer_id = isset($_GET['uid']) && is_numeric($_GET['uid']) ? $_GET['uid'] : 0;

		$conditions['Order.status'] = $status;
		if ($customer_id != 0)
			$conditions['Order.customer_id'] = $customer_id;

		$this->data = $this->Order->find('all', array(
			'joins' => array(
				array(
					'table' => 'customers',
					'alias' => 'Customer',
					'type' => 'INNER',
					'conditions' => array('Order.customer_id = Customer.id'),
				),
			),
			'conditions' => $conditions,
			'fields' => array('Order.*', 'Customer.*'),
			'order' => 'Order.id DESC',
		));
	}

	public function cart_edit($id = null)
	{
		if ($this->data) {
			$data = $this->data['Order'];

			$this->Order->id = $id;
			$this->Order->save($data);
			$this->redirect(DOMAINAD . 'admin_cart/cart_list/');
		}
		$this->data = $this->Order->findById($id);
	}

	public function cart_delete($order_id)
	{
		$this->autoRender = false;
		$this->Order->delete($order_id);
		$this->Session->setFlash('Đã xóa', 'success');
		$this->redirect($this->referer());
	}

	public function cart_done($order_id)
	{
		$this->autoRender = false;
		$this->Order->id = $order_id;
		$this->Order->saveField('status', 1);
		$this->Session->setFlash('Đã thay đổi', 'success');
		$this->redirect($this->referer());
	}
}
