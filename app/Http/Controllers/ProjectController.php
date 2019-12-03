<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Carbon\Carbon;
use Auth;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)	{
        
        $project = new Project;

        $input = $request->input();

        // Single fields
        $project->user_id = (int)$input['user_id'];
        $project->title = $input['title'];
        $project->description = $input['description'];
        $project->url = $input['url'];
        $project->img_url = $input['img_url'];

        $project->save();

    }

    public function update(Request $request, $eventid){
		
        //
    }

    public function destroy($projectid){

        $project = Project::find($projectid);
        
        $project->delete();
    }

    public function getProjectList(Request $request){
        
        $userid = Auth::id();

        $projects = Project::select(
            'id', 
            'title', 
            'description', 
            'url', 
            'img_url', 
            'company_id', 
            'user_id')
            ->with('events')
            ->get();
        
        foreach($projects as $project){
            foreach($project->events as $event){
                $event['next'] = $event->getNextOccurrence();
            }
        }

        return $projects;
    }

    public function getProjectSelector(Request $request){

        $projects = Project::select(
            'id AS value', 
            'title AS text')
            ->orderBy('text')
            ->get()
            ->toArray();

        return $projects;
    }
}
