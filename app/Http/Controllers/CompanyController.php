<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function store(Request $request)	{

        $company = new Company;
        
        $validator = Validator::make($request->all(), $company->rules);

        if($validator->fails()) {

			$messages = $validator->messages();

            $alert = array();

            $alert['class'] = 'danger';

            $alert['html'] = '';

            foreach ($messages->all() as $message){

                $alert['html'] .= $message . ' ';

            }

            $alert = array(
                'html' => $alert['html'],
                'class' => $alert['class']
            );

            return back()
                ->with([
                    'alert' => $alert
                ])
                ->withErrors($validator)
                ->withInput();

        } else {

            $company->save();
            
            // Check if event has periodic sequence
            $plannedMessage = 'Bedrijf aangemaakt';

            $alert = array(

                'html' => $plannedMessage,

                'class' => 'success'

            );

            return back()

                ->with([

                    'alert' => $alert

                ]);


        }
    }
}
