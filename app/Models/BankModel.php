<?php

namespace App\Models;

use App\Models\BaseModel;

class BankModel extends BaseModel
{
	protected $table      = 'banks';
	protected $primaryKey = 'id';
	protected $returnType     = 'object';
	protected $allowedFields = ['name','location_id','status'];
    public function getBankWithLocation($id = 0)
    {
        return $this->select('banks.*, locations.name as location_name')
            ->join('locations', 'locations.id = banks.location_id')
            ->find($id);
    }
    public function getAllWithLocations()
    {
        return $this->select('banks.*, locations.name as location_name')
        ->join('locations', 'locations.id = banks.location_id')
            ->findAll();
    }
    public function getBankWithExcel()
    {
        return $this->select('banks.id, banks.name, attributes.name as attribute_name')
            ->join('excels', 'excels.bank_id = banks.id', 'inner')
            ->join('attributes', 'attributes.bank_id = banks.id', 'left')
            ->groupBy('banks.id, banks.name, attributes.name')
            ->findAll();
    }

}
