<?php

namespace App\Http\Controllers;

use App\CandidateType;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;

class CandidateTypeController extends APIBaseController
{
    public function index(){
    	$can_type = CandidateType::all();

    	return $this->sendResponse($can_type->toArray(), 'Types of candidates retrieved successfully.');
    }

    public function searchCandidateType(){
        

        $type_name = request('type_name');
        
        $can_type = CandidateType::
        where('type_name', 'LIKE', "%{$type_name}%")
        ->with('candidate')->get();
       
     return $this->sendResponse($can_type->toArray(), 'Candidates found successfully.');   
    }
}
