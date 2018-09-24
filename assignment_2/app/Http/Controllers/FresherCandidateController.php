<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
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
 		->select('candidates.candidateID','candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','freshers.graduation_date', 'freshers.graduation_rank', 'freshers.education')
 		->get();

 		return $this->sendResponse($fresh->toArray(), 'Freshers list retrieved successfully.');
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
 			'graduation_date' => 'required',
 			 'graduation_rank' => ['required', Rule::in(['Excellence', 'Good', 'Fair', 'Poor']), ], 
 			 'education' => 'required',

 		]);

        $input['candidate_type_id'] = 2;

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

        $fresh = Freshers::with('candidate')->find($can_id);

        if (is_null($fresh)) {
            return $this->sendError('Candidates not found.');
        }
        // viet thêm dòng này ở đây thì no chẳng lấy toàn tbioj 
       // $exp = Experiences::with('candidate')->get();
        return $this->sendResponse($fresh->toArray(), 'Experience candidates retrieved successfully.');

    }


 	public function edit($can_id){
        $fresh = Candidates::find($can_id);
    }

    public function update(Request $request , $can_id){
        $fresh = Freshers::with('candidate')->find($can_id);

        $fresh->graduation_date = $request->graduation_date;
        $fresh ->graduation_rank = $request->graduation_rank;
        $fresh ->education = $request->education;

        $fresh->save();

        
        if (is_null($fresh)) {
            return $this->sendError('Candidates not found.');
        }

        

        return $this->sendResponse($fresh->toArray(), 'Fresher candidate updated successfully.');
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
