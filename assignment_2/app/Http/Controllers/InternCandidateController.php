<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;
use App\Interns;
use App\Candidates;
use Validator;
class InternCandidateController extends APIBaseController
{
     public function index(){
 		$intern = Interns::all();
 		$can = Candidates::all();

 		$intern = DB::table('interns')
 		->join('candidates', 'candidates.candidateID', '=', 'interns.can_id')
 		// ->where(['candidates.candidateID'=> 'experiences.can_id'])
 		->select('candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','interns.major', 'interns.semester', 'interns.university_name')
 		->get();

 		return $this->sendResponse($intern->toArray(), 'Interns list retrieved successfully.');
 	} 

 	public function store(Request $request) {
 		$input = $request->all();
 		$exp = DB::table('experiences')
 		->join('candidates', 'candidates.candidateID', '=', 'experiences.can_id')
 		->get();
 		$validator = Validator::make($input, [
 			'firstName' => 'required',
            'lastName' => 'required',
            'birthdate' => 'required|date|date_format:Y-m-d|before:today',
            'address' => 'required',
            'phone_number' => 'required|max:11',
            'email' => 'required|string|email',
            'candidate_type_id' => 'required|integer|between:1,3',
 			'major' => 'required',
 			'semester' => 'required', 
 			'university_name' => 'required',

 		]);

 		if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        $can = Candidates::create($input);
        
        $can->intern()->save(new Interns($input));

        return response()->json([
            'message' => 'Successfully created an Experience Candidates!'
        ], 201);
 	}   
}
