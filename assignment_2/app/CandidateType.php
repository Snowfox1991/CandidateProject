<?php

namespace App;
use App\Candidates;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
class CandidateType extends Eloquent
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
// vo li nhir sao khoong ra ta 
    //e thâý kỳ lạ v á 
    //ủa thằng này khai báo bắt buộc phải 1 primary key và 1 foreign key hả a 
    //vụ này e mới biết
    public function candidate() {
    	//xem nos cos vaof ddaya khong 
    	//dump(1);
    	//nó vào đúng, e làm đúng như nãy a ghi á
    	//nên e thấy lạ là return object null
    	return $this->hasMany(Candidates::class, 'candidate_type_id', 'type_id');
    }
}
