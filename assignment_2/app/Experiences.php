<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
class Experiences extends Eloquent
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

	protected $primaryKey = 'can_id';

    protected $fillable = [
    	'can_id','yearOfExp', 'proSkill', 
    ];

    public function candidate(){
    	return $this->belongsTo('App\Candidates', 'can_id');
    }
}
