<?php           

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Candidates;
use App\CandidateType;
use Validator;
use DB;
use App\Http\Controllers\APIBaseController as APIBaseController;
class CandidateController extends APIBaseController
{
    public function index(){
    	$can = Candidates::all();
    	$can_type = CandidateType::all();

    	$can = DB::table('candidates')
    	->join('candidate_types', 'candidate_types.type_id', '=', 'candidates.candidate_type_id')
    	->select('candidates.candidateID','candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','candidate_types.type_name')
    	->get();
    	return $this->sendResponse($can->toArray(), 'Candidates retrieved successfully.');
    }

    

    public function show($candidateID){
    	// $can = Candidates::all();
        $can = Candidates::find($candidateID);
        // $can = DB::table('candidates')
        //     ->where('candidateID', $candidateID)
        //     ->get();

    	if(is_null($can)){
    		return $this->sendError('Candidates not found.');
    	}

    	return $this->sendResponse($can->toArray(), 'Candidates retrieved successfully.');
    }

    

    public function edit($candidateID){
    	$can = Candidates::find($candidateID);

        
    }
    //nó đang lỗi ngay thằng update với show nếu vẫn để là $id// dang create ma ham luc nay viet dau nhi 
    public function update(Request $request, $candidateID){
    	$input = $request->all();
        $can = Candidates::all();
    	$validator = Validator::make($input, [
 			// 'firstName' => 'required',
 			// 'lastName' => 'required',
 			// 'birthdate' => 'required|date|date_format:Y-m-d|before:today',
 			'address' => 'required',
 			'phone_number' => 'required|max:11',
 			'email' => 'required|string|email',
 			

 		]);

 		if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       
        }

         $can = Candidates::find($candidateID);
        // DB::table('candidates')
        // ->where('candidateID', $candidateID)
        // ->get();
        // $can = Candidates::where("candidateID", $request->input('candidateID'))->first();

        if (is_null($can)) {
            return $this->sendError('Candidates not found.');
        }

        // $can->firsName = $input['firstName'];
        // $can->lastName = $input['lastName'];
        // $can->birthdate = $input['birthdate'];
		$can->address = $input['address'];
		$can->phone_number = $input['phone_number'];
		$can->email = $input['email'];

        $can = DB::table('candidates')
        ->join('candidate_types', 'candidate_types.type_id', '=', 'candidates.candidate_type_id')
        ->select('candidates.candidateID','candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','candidate_types.type_name')
        ->get();

		$can->save();
		return $this->sendResponse($can->toArray(), 'Candidate updated successfully.');
    }

    


    public function searchCandidate(){
        $firstName = request('firstName');
        $lastName = request('lastName');
        $events = DB::table('candidates')
        ->join('candidate_types','candidate_types.type_id', '=', 'candidates.candidateID')
        ->whereBetween('events.start',[$start, $end])
        ->select('events.id','events.name', 'events.description', 'events.start', 'events.end', 'event_types.color', 'events.location', 'events.is_Active')
        ->get();
        
     return $this->sendResponse($events->toArray(), 'Events found successfully.');   
    }
}
