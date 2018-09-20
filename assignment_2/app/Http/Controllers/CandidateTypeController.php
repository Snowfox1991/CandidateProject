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
        // $can_type = DB::table('candidate_types')
        // ->join('candidates',  'candidates.candidateID', '=' ,'candidate_types.type_id')
        // ->where('type_name', 'LIKE', "%$type_name%")
        // ->select('candidates.candidateID','candidates.firstName', ßß'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','candidate_types.type_name')
        // ->get();
        
        //còn cái nà search theo experience, fresher hay intern dính liền 
       	//experience đang có 2 nhưng search kết quả chỉ có 1
        $can_type = CandidateType::
        where('type_name', 'LIKE', "%{$type_name}%")
        ->
        with('candidate')->get();
       // dd(\App\Candidates::where('candidate_type_id', $can_type[0]->type_id)->get());
        // có 1 thằng thì phải
        //có 2 thằng experience lận a 

       // $can_type = DB::table('candidate_types')
        //->join('candidates',  'candidates.candidateID', '=' ,'candidate_types.type_id')
        // ->select('candidates.candidateID','candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','candidate_types.type_name')
       // ->get();
     return $this->sendResponse($can_type->toArray(), 'Candidates found successfully.');   
    }
}
