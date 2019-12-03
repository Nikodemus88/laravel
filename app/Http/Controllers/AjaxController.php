<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Event;
use App\Models\Auth\User;
use Carbon\Carbon;
use RRule\RRule;
use RRule\RSet;
use DB;

class AjaxController extends Controller
{
    public function autoCompleteCompanies(Request $request){

        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = Company::where('name','like','%'.$search.'%')->get();
            return response()->json($data);
        }       
    }
}
