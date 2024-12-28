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
}