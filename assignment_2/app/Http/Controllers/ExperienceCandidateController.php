<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;
use App\Experiences;
use App\Candidates;
use Validator;
class ExperienceCandidateController extends APIBaseController
{
 	public function index(){
 		//$exp = Experiences::all();
 		//$can = Candidates::all();
//viet 2 dong nay lam gi the
        //cai nay minh lay du lieu á , co can dung den no' dau 
 		$exp = DB::table('experiences')
 		->join('candidates', 'candidates.candidateID', '=', 'experiences.can_id')
 		// ->where(['candidates.candidateID'=> 'experiences.can_id'])
 		->select('candidates.candidateID','candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','experiences.yearOfExp', 'experiences.proSkill')
 		->get();

 		return $this->sendResponse($exp->toArray(), 'Experiences list retrieved successfully.');
 	}   

 	public function create(){

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
            'candidate_type_id' => 'required|integer|between:0,2',
 			'yearOfExp' => 'required|integer|between:0,100',
 			'proSkill' => 'required'

 		]);

 		if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

 		// $can = new Candidates([
 		// 	'firstName' => $request->firstName,
 		// 	'lastName' => $request->lastName,
 		// 	'birthdate' => $request->birthdate,
 		// 	'address' => $request->address,
 		// 	'phone_number' => $request->phone_number,
 		// 	'email' -> $request->email,
 		// ]);
 		//$exp = new Experiences([
 			//'yearOfExp' => ,
 			//'proSkill' => $request->proSkill,
 		//]);
       // dd($input);
        ////$exp->save();
        //dump($input);
        $can = Candidates::create($input);
        //loi ngay phia tren roi
        //candidate_type_id
        $can->experience()->save(new Experiences($input));
        // phai theo thu tu the nay moi dc
        //v là e có cần phải thêm trường $fillable của thằng Candidate k a 
        //fillable cuar thawng nao thi thang ay them thoi, k them cua thang khac 
        //ủa v sao mà nó biết mình tạo cái experience thì phải có candidate_type là 1 v a 
        // cai can type do minh phai tu truyen vao thoi 
        //can_id bị null giờ có cách nào 
        // duoc roi do 
       // $exp->candidates->create();
        // cái này phải candi truoc roi moi tao dc exxp / k tao dc nhu the nay 
        //cái candidate e tạo dc rồi 
        //e đang muốn tìm cách cho nó kiểm tra nếu đúng là exp thì nó sẽ vào thằng này 

        // biến can này ở đâu ra 
        //mình đang muốn thử cái input experience lồng ghép vào luôn 

 		// $exp->candidate()->associate($can);
 		//lồng kiểu này nó có tính k ạ 

       // $exp = Experiences::create($input);
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

    	$exp = Experiences::find($can_id);
        
        // $exp = DB::table('experience')
        // ->join('candidates', 'candidates.candidateID', '=', 'experiences.can_id')
        // ->select('candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','experiences.yearOfExp', 'experiences.proSkill')
        // ->find($can_id);
        if (is_null($exp)) {

            return $this->sendError('Candidates not found.');
        }
        $exp = Experiences::with('candidate')->get();
        return $this->sendResponse($exp->toArray(), 'Candidates retrieved successfully.');

    }

    public function edit($can_id){
    	$exp = Candidates::find($can_id);
    }

    public function update(Request $request , $can_id){
        
    	$input = $request->all();

        $validator = Validator::make($input, [
			'yearOfExp' => 'required|integer|between:0,100',
 			'proSkill' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        // $exp = Experiences::find($can_id);
        // if (is_null($exp)) {
        //     return $this->sendError('Candidates not found.');
        // }

        $exp->yearOfExp = $input['yearOfExp'];
        $exp->proSkill = $input['proSkill'];
        
        $exp->save();
 
        
   //      $exp = DB::table('experiences')
 		// ->join('candidates', 'candidates.candidateID', '=', 'experiences.can_id')
 		// ->select('candidates.firstName', 'candidates.lastName', 'candidates.birthdate', 'candidates.address', 'candidates.phone_number', 'candidates.email','experiences.yearOfExp', 'experiences.proSkill')
 		// ->get();
        

        return $this->sendResponse($exp->toArray(), 'Experienced candidate updated successfully.');
    }

    public function destroy($can_id){
        
    	$exp = Experiences::find($can_id);

        if (is_null($exp)) {
            return $this->sendError('Experience candidate not found.');
        }



        $exp->candidate()->delete();
        $exp->delete();
        return $this->sendResponse($can_id, 'Tag deleted successfully.');
    }

}
