<?php

namespace App\Controllers;

use App\Controllers\AdminBaseController;
use App\Models\AttributeModel;
use App\Models\BankModel;
use App\Models\EmailTemplateModel;
use App\Models\LocationModel;

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
        $banks = (new BankModel())->getAllWithLocations();
		return view('admin/banks/add',compact('banks'));
	}

	public function save()
	{
		$this->permissionCheck('banks_add');
		postAllowed();
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $data = array_map('trim', $data);
        $validation->setRules([
            'name' => 'required|min_length[3]',
            'location_id' => 'required',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        try {
            $bank = new BankModel();
            $bank = $bank->create($data);
            model('App\Models\ActivityLogModel')->add("New Bank #$bank Created by User: #" . logged('id'));
            return redirect()->to('/banks/add')->with('notifySuccess', 'New Bank Created Successfully');
        }catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to create bank: ' . $e->getMessage());
        }
	}
    public function edit($id = 0)
    {
        $this->permissionCheck('banks_edit');
        $bank = (new BankModel())->find($id);
        if (empty($bank)){
            return redirect()->to('/banks/add')->with('notifyError', 'Bank not found');
        }
        return view('admin/banks/edit', compact('bank'));
    }

    public function update($id = 0)
    {
        $this->permissionCheck('banks_edit');
        postAllowed();
        $bank = (new BankModel())->find($id);
        if (empty($bank)){
            return redirect()->to('/banks/add')->with('notifyError', 'Bank not found');
        }
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $data = array_map('trim', $data);
        $validation->setRules([
            'name' => 'required|min_length[3]',
            'location_id' => 'required',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        try{
            (new BankModel())->update($id, $data);
            model('App\Models\ActivityLogModel')->add("Bank #$id Updated by User:".logged('name'));
            return redirect()->to('banks/add')->with('notifySuccess', 'Bank has been Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to update bank: ' . $e->getMessage());
        }
    }
    public function delete($id = 0)
    {
        $this->permissionCheck('locations_delete');
        $bank = (new BankModel())->find($id);
        if (empty($bank)){
            return redirect()->to('/banks/add')->with('notifyError', 'Bank not found');
        }
        (new BankModel())->delete($id);
        model('App\Models\ActivityLogModel')->add("Bank #$id Deleted by User:".logged('name'));
        return redirect()->to('banks/add')->with('notifySuccess', 'Bank has been Deleted Successfully');
    }

    public function change_status($id = 0)
    {
        (new BankModel())->update($id, ['status' => get('status') == 'true' ? 1 : 0 ]);
        echo 'done';
    }

}
