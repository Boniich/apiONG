<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private string $notFoundMsg = "Project not found";


    public function index()
    {
        try {
            $projects = Project::all();

            return okResponse200($projects, "Projects retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);

            return okResponse200($project, "Project retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image',
                'due_date' => 'required|string'
            ]);

            $newProject = new Project;

            $newProject->title = $request->title;
            $newProject->description = $request->description;
            $newProject->image = upLoadImage($request->image);
            $newProject->due_date = $request->due_date;

            $newProject->save();

            return okResponse200($newProject, "Project created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image',
                'due_date' => 'required|string'
            ]);

            $project = Project::findOrFail($id);

            $project->title = $request->title;
            $project->description = $request->description;
            $project->image = updateLoadedImage($project->image, $request->image);
            $project->due_date = $request->due_date;

            $project->update();

            return okResponse200($project, "Project updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);

            deleteLoadedImage($project->image);
            $project->delete();

            return okResponse200($project, "Project deleted successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
