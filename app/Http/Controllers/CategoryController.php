<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private string $notFoundMsg = "Category not found";

    /**
     * Show a list of categories
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Display a listing of categories.",
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Category 1",
     *                          "description": "Description of activity 1",
     *                          "image": "image.png",
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
            $categories = Category::all();

            return okResponse200($categories, "Categories retrived succesfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Show an category by id
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Display an category",
     *     @OA\Parameter(
     *          description="id of category",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Category retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Category 1",
     *                          "description": "Description of activity 1",
     *                          "image": "image.png",
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return okResponse200($category, "Category retrived succesfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Create a new category
     * Note: This endpoint does not work if you add the field "image" in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/categories",
     *      summary="Create a new category",
     *      tags={"Categories"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","description"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Category created successfully"  
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
                'image' => 'image'
            ]);

            $newCategory = new Category;

            $newCategory->name = $request->name;
            $newCategory->description = $request->description;

            if ($request->has('image')) {
                $newCategory->image = upLoadImage($request->image);
            }

            $newCategory->save();

            return okResponse200($newCategory, "Category created succesfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }


    /**
     * Update a category
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/categories/{id}",
     *      summary="Update an activity",
     *      tags={"Categories"},
     *      @OA\Parameter(
     *          description="id of category",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","description"},
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Category updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found"
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
                'description' => 'string',
                'image' => 'image'
            ]);

            $category = Category::findOrFail($id);

            if ($request->has('name')) {
                $category->name = $request->name;
            }

            if ($request->has('description')) {
                $category->description = $request->description;
            }

            if ($request->has('image')) {
                $category->image = updateLoadedImage($category->image, $request->image);
            }

            $category->update();
            return okResponse200($category, "Category updated succesfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete a category
     * @OA\Delete(
     *      path="/api/categories/{id}",
     *      summary="Delete a category",
     *      tags={"Categories"},
     * 
     *       @OA\Parameter(
     *          description="id of category",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Category delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found"  
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
            $category = Category::findOrFail($id);

            deleteLoadedImage($category->image);
            $category->delete();

            return okResponse200($category, "category deleted succesfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
