<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="ONG API by Boniich",
 *      description="This API is a copy of Alkemy ONG API used in my React acceleration.",
 *      @OA\Contact(
 *          email="boniichDev@gmail.com"
 *      )
 * )
 */



class ActivityController extends Controller
{
    private string $notFoundMsg = "Activity not found";

    /**
     * Display a listing of activities.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/activities",
     *     tags={"Activities"},
     *     summary="Display a listing of activities.",
     *     @OA\Response(
     *         response=200,
     *         description="Activities retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Activity 1",
     *                          "slug": "slug of activity1",
     *                          "description": "Description of activity 1",
     *                          "image": "image-activity-seeder.png",
     *                          "user_id": null,
     *                          "category_id": null,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
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
            $activities = Activity::all();

            return okResponse200($activities, "Activities retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display an activities by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/activities/{id}",
     *     tags={"Activities"},
     *     summary="Display an activity.",
     *     @OA\Parameter(
     *          description="id of activity",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Activity retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Activity 1",
     *                          "slug": "slug of activity1",
     *                          "description": "Description of activity 1",
     *                          "image": "image-activity-seeder.png",
     *                          "user_id": null,
     *                          "category_id": null,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *       })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Activity not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $activity = Activity::findOrfail($id);

            return okResponse200($activity, "Activity retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Create a new activity.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/activities",
     *      summary="Create a new activity",
     *      tags={"Activities"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"id","name","slug","description", "image","user_id","category_id"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="slug", type="string", format="string" ),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="user_id", type="integer", format="string"),
     *          @OA\Property(property="category_id", type="integer", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Activity created successfully"  
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
                'slug' => 'string',
                'description' => 'required|string',
                'image' => 'required|image',
                'user_id' => 'integer',
                'category_id' => 'integer'
            ]);

            $newActivity = new Activity;

            $newActivity->name = $request->name;
            $newActivity->slug = $request->slug;
            $newActivity->description = $request->description;
            $newActivity->image = upLoadImage($request->image);


            if ($request->has('user_id')) {
                if (is_null(User::find($request->user_id))) {
                    return notFoundData404("User not found");
                }

                $newActivity->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                if (Category::findOrFail($request->category_id)) {
                    return notFoundData404("Category not found");
                }

                $newActivity->category_id = $request->category_id;
            }

            $newActivity->save();

            return okResponse200($newActivity, "Activity created successfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Update an activity
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/activities/{id}",
     *      summary="Update an activity",
     *      tags={"Activities"},
     *      @OA\Parameter(
     *          description="id of activity",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"id","name","slug","description", "image","user_id","category_id"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="slug", type="string", format="string" ),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="user_id", type="integer", format="integer"),
     *          @OA\Property(property="category_id", type="integer", format="integer"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Activity updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Activity not found"
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
                'name' => 'string',
                'slug' => 'string',
                'description' => 'string',
                'image' => 'image',
                'user_id' => 'integer',
                'category_id' => 'integer'
            ]);

            $activity = Activity::findOrFail($id);


            if ($request->has('name')) {
                $activity->name = $request->name;
            }

            if ($request->has('slug')) {
                $activity->slug = $request->slug;
            }

            if ($request->has('description')) {
                $activity->description = $request->description;
            }

            if ($request->has('image')) {
                $activity->image = updateLoadedImage($activity->image, $request->image);
            }

            if ($request->has('user_id')) {
                $user = User::find($request->user_id);

                if (is_null($user)) {
                    return notFoundData404("User not found");
                }

                $activity->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                $category = Category::find($request->category_id);

                if (is_null($category)) {
                    return notFoundData404("Category not found");
                }

                $activity->category_id = $request->category_id;
            }


            $activity->update();

            return okResponse200($activity, "Activity updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete an activity
     * @OA\Delete(
     *      path="/api/activities/{id}",
     *      summary="Delete an activity",
     *      tags={"Activities"},
     * 
     *       @OA\Parameter(
     *          description="id of activity",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Activity delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Activity not found"  
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
            $activity = Activity::findOrFail($id);

            deleteLoadedImage($activity->image);
            $activity->delete();

            return okResponse200($activity, "Activity deleted successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
