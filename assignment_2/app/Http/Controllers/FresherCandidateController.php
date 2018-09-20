<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;
use App\Freshers;
use App\Candidates;
use Validator;
class FresherCandidateController extends APIBaseController
{
    public function index(){
 		// $fresh = Freshers::all();
 		// $can = Candidates::all();

 		$fresh = DB::table('freshers')
 		->join('candidates', 'candidates.candidateID', '=', 'freshers.can_id')
 		// ->where(['candidates.candidateID'=> 'experiences.can_id'])
 		->select('candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','freshers.graduation_date', 'freshers.graduation_rank', 'freshers.education')
 		->get();

 		return $this->sendResponse($fresh->toArray(), 'Freshers list retrieved successfully.');
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
 			'graduation_date' => 'required',
 			 'graduation_rank' => 'required', 
 			 'education' => 'required',

 		]);

 		if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        $can = Candidates::create($input);
        
        $can->fresher()->save(new Freshers($input));

        return response()->json([
            'message' => 'Successfully created an Experience Candidates!'
        ], 201);
 	} 

 	public function show($can_id){
        //dung can_id sao find duoc, phai dung id cua experiences chu, no lay nham mat, the can_id do la khoa chinh luon ak 
        //đúng r á bạn
        //vì bảng này mình lấy can_id foreign với candidateID 
        // Vậy có vấn đề gì ở đây nhỉ 
        //create - update - delete nó bị 

    	$fresher = Freshers::with('candidate')->find($can_id);
        
        // $exp = DB::table('experience')
        // ->join('candidates', 'candidates.candidateID', '=', 'experiences.can_id')
        // ->select('candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','experiences.yearOfExp', 'experiences.proSkill')
        // ->find($can_id);
        if (is_null($fresher)) {

            return $this->sendError('Candidates not found.');
        }
        // $fresher = Freshers::with('candidate')->get();
        return $this->sendResponse($fresher->toArray(), 'Intern retrieved successfully.');

    }
    public function destroy($can_id){
        
    	$fresher = Freshers::find($can_id);

        if (is_null($fresher)) {
            return $this->sendError('Fresher candidate not found.');
        }



        $fresher->candidate()->delete();
        $fresher->delete();
        return $this->sendResponse($can_id, 'Tag deleted successfully.');
    }

}
