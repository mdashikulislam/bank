<?php

namespace App\Models;

use App\Models\BaseModel;

class BankModel extends BaseModel
{
	protected $table      = 'banks';
	protected $primaryKey = 'id';
	protected $returnType     = 'object';
	protected $allowedFields = ['name'];
}
