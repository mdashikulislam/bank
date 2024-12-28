<?php
namespace App\Controllers;
use App\Models\AttributeModel;

class Attributes extends AdminBaseController
{
    public $title = 'Attributes';
    public $menu = 'banks';

    public function index()
    {
        $this->permissionCheck('attributes_list');
        $attributes = (new AttributeModel())->getAllAttributes();
        return view('admin/attributes/list',compact('attributes'));
    }

    public function add()
    {
        $this->permissionCheck('attributes_add');
        return view('admin/attributes/add');
    }


    public function save()
    {
        $this->permissionCheck('attributes_add');
        postAllowed();
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $data = array_map('array_trim', $data);
        $validation->setRules([
            'bank_id' => 'required',
            'name' => 'required|is_array',
            'name.*' => 'min_length[1]',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        $exist = (new AttributeModel())->where('bank_id', $data['bank_id'])->first();
        if($exist){
            return redirect()->back()->with('notifyError', 'Attribute already exists');
        }
        $data['name'] = json_encode($data['name']);
        try {
            $attribute = new AttributeModel();
            $attribute = $attribute->create($data);
            model('App\Models\ActivityLogModel')->add("New Attribute #$attribute Created by User: #" . logged('id'));
            return redirect()->to('attributes')->with('notifySuccess', 'New Attribute Created Successfully');
        }catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to create attribute: ' . $e->getMessage());
        }
    }

    public function edit($id = 0)
    {
        $this->permissionCheck('attributes_edit');
        $attribute = (new AttributeModel())->getAttribute($id);
        if (empty($attribute)){
            return redirect()->to('/attributes/add')->with('notifyError', 'Attributes not found');
        }
        return view('admin/attributes/edit', compact('attribute'));
    }

    public function update($id = 0)
    {
        $this->permissionCheck('attributes_edit');
        postAllowed();
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $data = array_map('array_trim', $data);
        $validation->setRules([
            'bank_id' => 'required',
            'name' => 'required|is_array',
            'name.*' => 'min_length[1]',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        $data['name'] = json_encode($data['name']);
        try {
            $attribute = new AttributeModel();
            $attribute = $attribute->update($id, $data);
            model('App\Models\ActivityLogModel')->add("Attribute #$attribute Updated by User: #" . logged('id'));
            return redirect()->to('attributes')->with('notifySuccess', 'Attribute Updated Successfully');
        }catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to update attribute: ' . $e->getMessage());
        }
    }
    public function delete($id = 0)
    {
        $this->permissionCheck('locations_delete');
        $bank = (new AttributeModel())->find($id);
        if (empty($bank)){
            return redirect()->to('/attributes')->with('notifyError', 'Attribute not found');
        }
        (new AttributeModel())->delete($id);
        model('App\Models\ActivityLogModel')->add("Attribute #$id Deleted by User:".logged('name'));
        return redirect()->to('attributes')->with('notifySuccess', 'Attribute has been Deleted Successfully');
    }
    public function change_status($id = 0)
    {
        (new AttributeModel())->update($id, ['status' => get('status') == 'true' ? 1 : 0 ]);
        echo 'done';
    }
}