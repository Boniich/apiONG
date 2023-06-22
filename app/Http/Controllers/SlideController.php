<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    private string $notFoundMsg = "Slide not found";


    /**
     * Display a listing of Slides.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/slides",
     *     tags={"Slides"},
     *     summary="Display a listing of slides.",
     *     @OA\Parameter(
     *          description="Search a term",
     *          in="query",
     *          name="search",
     *          required=false,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Parameter(
     *          description="Limit of entries retrived",
     *          in="query",
     *          name="limit",
     *          required=false,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Slides retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "slide name",
     *                          "description": "this is a slide",
     *                          "image": "1687376560.jpg",
     *                          "order": 5,
     *                          "user_id": 5,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-21T19:42:40.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error ocurred"
     *     )
     * ) 
     */

    public function index(Request $request)
    {
        try {
            $limit = 5;

            if ($request->has('limit')) {
                $limit = $request->limit;
            }

            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $slides = Slide::where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('description', 'LIKE', $searchTerm)->limit($limit)->get();
            } else {
                $slides = Slide::limit($limit)->get();
            }

            return okResponse200($slides, "Slides retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display a slide by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/slides/{id}",
     *     tags={"Slides"},
     *     summary="Display a slide.",
     *     @OA\Parameter(
     *          description="id of slide",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Slide retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "slide name",
     *                          "description": "this is a slide",
     *                          "image": "1687376560.jpg",
     *                          "order": 5,
     *                          "user_id": 5,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-21T19:42:40.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Slide not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $slide = Slide::findOrfail($id);

            return okResponse200($slide, "Slider retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Create a new slide.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/slides",
     *      summary="Create a new slide",
     *      tags={"Slides"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","description","image","order"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="order", type="integer", format="string"),
     *          @OA\Property(property="user_id", type="integer", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Slide created successfully"  
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
                'name' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image',
                'order' => 'required|integer',
                'user_id' => 'integer'
            ]);

            $newSlide = new Slide;

            $newSlide->name = $request->name;
            $newSlide->description = $request->description;
            $newSlide->image = upLoadImage($request->image);
            $newSlide->order = $request->order;

            if ($request->has('user_id')) {
                if (is_null(User::find($request->user_id))) {
                    return notFoundData404("User not found");
                }

                $newSlide->user_id = $request->user_id;
            }

            $newSlide->save();

            return okResponse200($newSlide, "Slide created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404("ID of USER not found");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Update a slide
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/slides/{id}",
     *      summary="Update a slide",
     *      tags={"Slides"},
     *      @OA\Parameter(
     *          description="id of slide",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="order", type="integer", format="string"),
     *          @OA\Property(property="user_id", type="integer", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Slide updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Slide not found"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"  
     *      ),
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'string',
                'description' => 'string',
                'image' => 'image',
                'order' => 'integer',
                'user_id' => 'integer'
            ]);

            $slide = Slide::findOrFail($id);


            if ($request->has('name')) {
                $slide->name = $request->name;
            }

            if ($request->has('description')) {
                $slide->description = $request->description;
            }

            if ($request->has('image')) {
                $slide->image = updateLoadedImage($slide->image, $request->image);
            }

            if ($request->has('order')) {
                $slide->order = $request->order;
            }

            if ($request->has('user_id')) {

                if (is_null(User::find($request->user_id))) {
                    return notFoundData404("User not found");
                }

                $slide->user_id = $request->user_id;
            }


            $slide->update();

            return okResponse200($slide, "Slide updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete a slide
     * @OA\Delete(
     *      path="/api/slides/{id}",
     *      summary="Delete a slide",
     *      tags={"Slides"},
     * 
     *       @OA\Parameter(
     *          description="id of slide",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Slide delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Slide not found"  
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
            $slide = Slide::findOrFail($id);

            deleteLoadedImage($slide->image);
            $slide->delete();

            return okResponse200($slide, "Slide deleted successfully");
        } catch (ModelNotFoundException) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
