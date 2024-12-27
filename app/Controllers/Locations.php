<?php

namespace App\Controllers;

use App\Controllers\AdminBaseController;
use App\Models\LocationModel;
use App\Models\EmailTemplateModel;

class Locations extends AdminBaseController
{

	public $title = 'Locations';
	public $menu = 'banks';

	// public function index()
	// {
	// 	return $this->locations();
	// }

	// public function locations()
	// {
	// 	$this->permissionCheck('locations_list');

	// 	$this->updatePageData(['submenu' => 'locations']);

	// 	return view('admin/locations/list');
	// }


	public function add()
	{
		$this->permissionCheck('locations_add');
        $locations = (new LocationModel())->findAll();
		return view('admin/locations/add',compact('locations'));
	}


	public function save()
	{
		$this->permissionCheck('locations_add');
		postAllowed();
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $data = array_map('trim', $data);
        $validation->setRules([
            'name' => 'required|min_length[3]|is_unique[locations.name]',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        try {
            $locationModel = new LocationModel();
            $location = $locationModel->create($data);
            model('App\Models\ActivityLogModel')->add("New Location #$location Created by User: #" . logged('id'));
            return redirect()->to('/locations/add')->with('notifySuccess', 'New Location Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to create location: ' . $e->getMessage());
        }
	}

    public function edit($id = 0)
    {
        $this->permissionCheck('locations_edit');
        $location = (new LocationModel())->find($id);
        if (empty($location)){
            return redirect()->to('/locations/add')->with('notifyError', 'Location not found');
        }
        return view('admin/locations/edit', compact('location'));
    }

    public function update($id = 0)
    {
        $this->permissionCheck('locations_edit');
        $location = (new LocationModel())->find($id);
        if (empty($location)){
            return redirect()->to('/locations/add')->with('notifyError', 'Location not found');
        }
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $data = array_map('trim', $data);
        $validation->setRules([
            'name' => 'required|min_length[3]|is_unique[locations.name,id,'.$id.']',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        try{
            (new LocationModel())->update($id, $data);
            model('App\Models\ActivityLogModel')->add("Location #$id Updated by User:".logged('name'));
            return redirect()->to('locations/add')->with('notifySuccess', 'Location has been Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to update location: ' . $e->getMessage());
        }
    }
    public function delete($id = 0)
    {
        $this->permissionCheck('locations_delete');
        $location = (new LocationModel())->find($id);
        if (empty($location)){
            return redirect()->to('/locations/add')->with('notifyError', 'Location not found');
        }
        (new LocationModel())->delete($id);
        model('App\Models\ActivityLogModel')->add("Location #$id Deleted by User:".logged('name'));
        return redirect()->to('locations/add')->with('notifySuccess', 'Location has been Deleted Successfully');
    }

    public function change_status($id = 0)
    {
        (new LocationModel())->update($id, ['status' => get('status') == 'true' ? 1 : 0 ]);
        echo 'done';
    }
}

/* End of file Locations.php */
/* Location: ./application/controllers/Locations.php */