<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private string $notFoundMsg = "Project not found";


    /**
     * Display a list of projects.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/projects",
     *     tags={"Projects"},
     *     summary="Display a listing of projects.",
     *     @OA\Response(
     *         response=200,
     *         description="Projects retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "title": "Project 1",
     *                          "description": "Description of project 1",
     *                          "image": "image-projects-seeder.png",
     *                          "due_date": "2023",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error ocurred"
     *     )
     * ) 
     */

    public function index()
    {
        try {
            $projects = Project::all();

            return okResponse200($projects, "Projects retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display a project by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     tags={"Projects"},
     *     summary="Display a project.",
     *     @OA\Parameter(
     *          description="id of project",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Project retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "title": "Project 1",
     *                          "description": "Description of project 1",
     *                          "image": "image-projects-seeder.png",
     *                          "due_date": "2023",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found"
     *     )
     * ) 
     */

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

    /**
     * Create a new project.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/projects",
     *      summary="Create a new project",
     *      tags={"Projects"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"title","description","due_date"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="title", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="due_date", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Project created successfully"  
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="bad request"  
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="An error has occurred"
     *      )
     * )
     */

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

    /**
     * Update a project
     * @OA\Put(
     *      path="/api/projects/{id}",
     *      summary="Update a project",
     *      tags={"Projects"},
     *      @OA\Parameter(
     *          description="id of project",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="title", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="due_date", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Project updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Project not found"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="bad request"  
     *      ),
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'string',
                'description' => 'string',
                'image' => 'image',
                'due_date' => 'string'
            ]);

            $project = Project::findOrFail($id);

            if ($request->has('title')) {
                $project->title = $request->title;
            }

            if ($request->has('title')) {
                $project->description = $request->description;
            }

            if ($request->has('image')) {
                $project->image = updateLoadedImage($project->image, $request->image);
            }

            if ($request->has('due_date')) {
                $project->due_date = $request->due_date;
            }

            $project->update();

            return okResponse200($project, "Project updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete a project
     * @OA\Delete(
     *      path="/api/projects/{id}",
     *      summary="Delete a project",
     *      tags={"Projects"},
     * 
     *       @OA\Parameter(
     *          description="id of project",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Project deleted succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Project not found"  
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="An Error ocurred"
     *      )
     * )
     */

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
