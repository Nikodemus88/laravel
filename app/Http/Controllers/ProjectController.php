<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Carbon\Carbon;
use Auth;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id) {

        $project = Project::find($id)
        ->with('events')
        ->get();

        return $project;
    }

    public function store(Request $request)	{
        
        $project = new Project;

        $input = $request->input();

        // Single fields
        $project->user_id = (int)$input['user_id'];
        $project->title = $input['title'];
        $project->description = $input['description'];
        $project->url = $input['url'];
        $project->img_url = $input['img_url'];
        $project->color = $input['color'];

        $project->save();

    }

    public function update(Request $request, $id){

        $input = $request->input();

        $project = Project::find($id);

        $project->user_id = (int)$input['user_id'];
        $project->title = $input['title'];
        $project->description = $input['description'];
        $project->url = $input['url'];
        $project->img_url = $input['img_url'];
        $project->color = $input['color'];

        $project->save();
    }

    public function destroy($id){

        $project = Project::find($id);
        
        $project->delete();
    }

    public function getProjectList(Request $request){

        $projects = Project::select(
            'id', 
            'title', 
            'description', 
            'url', 
            'img_url',
            'user_id',
            'color')
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
