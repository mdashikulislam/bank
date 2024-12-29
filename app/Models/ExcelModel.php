<?php
namespace App\Models;
use App\Models\BaseModel;
class ExcelModel extends BaseModel
{
    protected $table      = 'excels';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $allowedFields = ['name','bank_id','header','data'];
    public function getAllExcels()
    {
        return $this->select('excels.*, banks.name as bank_name')
            ->join('banks', 'banks.id = excels.bank_id')
            ->findAll();
    }
}