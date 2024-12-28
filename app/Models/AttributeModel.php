<?php
namespace App\Models;
use App\Models\BaseModel;
class AttributeModel extends BaseModel{
    protected $table      = 'attributes';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $allowedFields = ['name','status','bank_id'];
    public function getAttribute($id = 0)
    {
        return $this->find($id);
    }
    public function getAllAttributes()
    {
        return $this->select('attributes.*, banks.name as bank_name')
            ->join('banks', 'banks.id = attributes.bank_id')
            ->findAll();
    }
}