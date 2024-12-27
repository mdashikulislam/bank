<?php

namespace App\Models;

use App\Models\BaseModel;

class LocationModel extends BaseModel
{
	protected $table      = 'locations';
	protected $primaryKey = 'id';
	protected $returnType     = 'object';
	protected $allowedFields = ['name'];
}
