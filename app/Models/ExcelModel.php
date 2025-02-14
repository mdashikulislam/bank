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
        $user = logged();
        if ($user->role == '1') {
            return $this->select('excels.*,users.name as user_name, banks.name as bank_name')
                ->join('banks', 'banks.id = excels.bank_id')
                ->join('users', 'users.id = excels.user_id')
                ->findAll();
        }else{
            return $this->select('excels.*, banks.name as bank_name')
                ->join('banks', 'banks.id = excels.bank_id')
                ->where('excels.user_id',$user->id)
                ->findAll();
        }
    }
}