<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* Events */
Route::post('events/store', 'EventController@store')->name('events.store');
Route::post('events/destroy/{id}', 'EventController@destroy')->name('events.destroy');
Route::get('events/eventlist', 'EventController@getEventList')->name('events.eventlist');
Route::get('events/calendarevents', 'EventController@getCalendarEvents')->name('events.calendarevents');
// Route::get('events/', 'EventController@index')->name('events.index'); // Unused

/* Projects */
Route::post('projects/store', 'ProjectController@store')->name('projects.store');
Route::post('projects/destroy/{id}', 'ProjectController@destroy')->name('projects.destroy');
Route::get('projects/projectlist', 'ProjectController@getProjectList')->name('projects.projectlist');
Route::get('projects/projectselector', 'ProjectController@getProjectSelector')->name('projects.projectselector');


Route::get('system/ajax/companies/', 'AjaxController@autoCompleteCompanies')->name('ajax.companies');

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
    Route::apiResource('tickets', 'API\TicketController');
});