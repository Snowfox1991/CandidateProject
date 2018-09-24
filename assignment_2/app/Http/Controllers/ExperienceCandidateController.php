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
        //cai nay minh lay du lieu á ,
        // co can dung den no' dau 
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

 		$validator = Validator::make($input, [
 			'firstName' => 'required',
            'lastName' => 'required',
            'birthdate' => 'required|date|date_format:Y-m-d|before:today',
            'address' => 'required',
            'phone_number' => 'required|max:11',
            'email' => 'required|string|email',
            // 'candidate_type_id' => 'required|integer|between:1,3',
 			'yearOfExp' => 'required|integer|between:0,100',
 			'proSkill' => 'required'

 		]);
        //nó là 1 user e mới tạo
        //db thì set nó là số 2 dù e đang tạo experience
        //e đang tạo experience - e set Experience là type 1
        //trong DB e là số 1
        //nó tạo thành công trong khi candidate_type_id của e là của thằng khác
        // ddể nó là 3 mà nó sẽ tạo là 3, có vấn đề gì ở đay 
        //field dữ liệu của nó sai logic á a 
        // k thấy sai chỗ nào cả điền là số mấy thì nó create số đó chứ 
        //ý e là e đang tạo Experience Candidate thì type của nó là 1
        ///nếu e nhập số khác tức là e nhập của type khác, nó báo sai
        //sai logic thông tin á a 
        // Đây là cố tình nhập sai còn gì nữa. Mà 
        //dạ e đang nhờ a coi thử có cách nào khóa cái type_id lại k 
        //giống như nếu trên web chọn sai thì nó sẽ báo lỗi, bắt chọn đúng á a 
        // biết đúng sai là gì rồi , thì bắt nó nhập làm gì nữa, mình set luôn cho nó đi có phải nhanh không 
        //dạ ý e là mình set luôn á a, e muốn set luôn trong quá trình nó create 
        //Điều kiện thế nào để là 1
        //chỉ cần tạo mới thằng Experience thì set đúng type của nó lúc chọn á a 
        // thằng exp phải lấy type 
 		if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }
        $input['candidate_type_id'] = 1;
        // nhu vay la xong, neu mac dinh
        //v e cmt luôn trên cái validator hay xóa luôn a 

        $can = Candidates::create($input);

        $experience = $can->experience()->save(new Experiences($input));
        //dump($experience);
        

 		return response()->json([
            'message' => 'Successfully created an Experience Candidates!'
        ], 201);
 	}

 	public function show($can_id){

    	$exp = Experiences::with('candidate')->find($can_id);

        if (is_null($exp)) {
            return $this->sendError('Candidates not found.');
        }
        
       // $exp = Experiences::with('candidate')->get();
        return $this->sendResponse($exp->toArray(), 'Experience candidates retrieved successfully.');

    }

    public function edit($can_id){
    	$exp = Candidates::find($can_id);
    }

    public function update(Request $request , $can_id){
        $exp = Experiences::with('candidate')->find($can_id);

        $exp->yearOfExp = $request->yearOfExp;
        $exp ->proSkill = $request->proSkill;

        $exp->save();

        
        if (is_null($exp)) {
            return $this->sendError('Candidates not found.');
        }

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
