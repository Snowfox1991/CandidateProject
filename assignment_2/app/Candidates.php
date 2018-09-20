<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
class Candidates extends Eloquent
{
	const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'candidateID';

    protected $fillable = [
    	'firstName', 'lastName', 'birthdate', 'address', 'phone_number', 'email', 'candidate_type_id',
    ];

    public function candidate_type(){
    	return $this->belongsTo('App\CandidateType', 'candidate_type_id');
    }
// sao lai belongTo, thang exprerience chua khoa chinh cua thang candidate ma. phai la hasOne chu
    //cái này e đang còn hơi mơ hồ mấy cái hasOne, hasMany,..lỗi e 

   public function experience() {
        return $this->hasOne('App\Experiences', 'can_id');
    }

    // public function experience() {
    // 	return $this->belongsTo('App\Experiences');
    // }

    public function fresher(){
    	return $this->hasOne('App\Freshers', 'can_id');
    }

    public function intern(){
    	return $this->hasOne('App\Interns', 'can_id');
    }
}
