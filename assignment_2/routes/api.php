<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('experience', 'ExperienceCandidateController');
Route::resource('fresher', 'FresherCandidateController');
Route::resource('intern', 'InternCandidateController');

Route::get('candidate', 'CandidateController@index');

Route::get('candidate-type', 'CandidateTypeController@index');

Route::get('candidates-search', 'CandidateTypeController@searchCandidateType');
