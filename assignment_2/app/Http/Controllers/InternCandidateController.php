<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;
use App\Interns;
use App\y ;
use Validator;
class InternCandidateController extends APIBaseController
{
     public function index(){

 		$intern = DB::table('interns')
 		->join('candidates', 'candidates.candidateID', '=', 'interns.can_id')
 		// ->where(['candidates.candidateID'=> 'experiences.can_id'])
 		->select('candidates.candidateID','candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','interns.major', 'interns.semester', 'interns.university_name')
 		->get();

 		return $this->sendResponse($intern->toArray(), 'Interns list retrieved successfully.');
 	} 

 	public function store(Request $request) {
 		$input = $request->all();
 		$validator = Validator::make($input, [
 			'firstName' => 'required',
            'lastName' => 'required',
            'birthdate' => 'required|date|date_format:Y-m-d|before:today',
            'address' => 'required',
            'phone_number' => 'required|max:11',
            'email' => 'required|string|email',
 			'major' => 'required',
 			'semester' => 'required', 
 			'university_name' => 'required',

 		]);
        $input['candidate_type_id'] = 3;
 		if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        $can = Candidates::create($input);
        
        $can->intern()->save(new Interns($input));

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

    	$intern = Interns::with('candidate')->find($can_id);
        
        // $exp = DB::table('experience')
        // ->join('candidates', 'candidates.candidateID', '=', 'experiences.can_id')
        // ->select('candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','experiences.yearOfExp', 'experiences.proSkill')
        // ->find($can_id);
        if (is_null($intern)) {

            return $this->sendError('Candidates not found.');
        }
        // $intern = Interns::with('candidate')->get();
        return $this->sendResponse($intern->toArray(), 'Intern retrieved successfully.');

    }

        public function edit($can_id){
        $intern = Candidates::find($can_id);
    }

    public function update(Request $request , $can_id){
        $intern = Experiences::with('candidate')->find($can_id);

        $intern->major = $request->yearOfExp;
        $intern ->semester = $request->proSkill;
        $intern ->university_name = $request->university_name;

        $intern->save();

        
        if (is_null($intern)) {
            return $this->sendError('Candidates not found.');
        }

        return $this->sendResponse($intern->toArray(), 'Experienced candidate updated successfully.');
    }

    public function destroy($can_id){
        
    	$intern = Interns::find($can_id);

        if (is_null($intern)) {
            return $this->sendError('Experience candidate not found.');
        }



        $intern->candidate()->delete();
        $intern->delete();
        return $this->sendResponse($can_id, 'Tag deleted successfully.');
    }

}
