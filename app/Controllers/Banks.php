<?php

namespace App\Controllers;

use App\Controllers\AdminBaseController;
use App\Models\BankModel;
use App\Models\EmailTemplateModel;

class Banks extends AdminBaseController
{

	public $title = 'Banks';
	public $menu = 'banks';

	// public function index()
	// {
	// 	return $this->banks();
	// }

	// public function banks()
	// {
	// 	$this->permissionCheck('banks_list');

	// 	$this->updatePageData(['submenu' => 'banks']);

	// 	return view('admin/banks/list');
	// }


	public function add()
	{
		$this->permissionCheck('banks_add');
        $banks = (new BankModel())->findAll();
		return view('admin/banks/add',compact('banks'));
	}

	public function save()
	{
		$this->permissionCheck('banks_add');
		postAllowed();
		$location = (new BankModel)->create([
			'name' => post('name'),
		]);

		model('App\Models\ActivityLogModel')->add("New Location #$location Created by User: #" . logged('id'));

		return redirect()->to('/banks/add')->with('notifySuccess', 'New Location Created Successfully');
	}
}

/* End of file Banks.php */
/* Location: ./application/controllers/Banks.php */