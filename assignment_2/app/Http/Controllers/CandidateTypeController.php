<?php

namespace App\Http\Controllers;

use App\\Model\CandidateType;

use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;

class CandidateTypeController extends APIBaseController
{
    public function index(){
    	$can_type = CandidateType::all();

    	return $this->sendResponse($can_type->toArray(), 'Types of candidates retrieved successfully.');
    }
}
