<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

    private string $notFoundMsg = "Testimonial not found";

    /**
     * Display a listing of testimonials.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/testimonials",
     *     tags={"Testimonials"},
     *     summary="Display a listing of testimonials.",
     *     @OA\Response(
     *         response=200,
     *         description="Testimonials retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Testimonio 1",
     *                          "image": "image-testimonial-seeder.png",
     *                          "description": "Descripcion del testimonio 1",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z"
     *       })},
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
            $testimonial = Testimonial::all();

            return okResponse200($testimonial, "Testimonials retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display a testimonial by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/testimonials/{id}",
     *     tags={"Testimonials"},
     *     summary="Display an testimonial.",
     *     @OA\Parameter(
     *          description="id of testimonial",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Testimonial retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Testimonio 1",
     *                          "image": "image-testimonial-seeder.png",
     *                          "description": "Descripcion del testimonio 1",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z"
     *       })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Testimonial not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $testimonial = Testimonial::findOrfail($id);

            return okResponse200($testimonial, "Testimonial retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }


    /**
     * Create a new testimonial.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/testimonials",
     *      summary="Create a new testimonial",
     *      tags={"Testimonials"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","description", "image"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="description", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Testimonial created successfully"  
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
                'image' => 'required|image',
                'description' => 'required|string'
            ]);

            $newTestimonial = new Testimonial;

            $newTestimonial->name = $request->name;
            $newTestimonial->image = upLoadImage($request->image);
            $newTestimonial->description = $request->description;

            $newTestimonial->save();

            return okResponse200($newTestimonial, "Testimonial created successfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Update a testimonial
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/testimonials/{id}",
     *      summary="Update a testimonial",
     *      tags={"Testimonials"},
     *      @OA\Parameter(
     *          description="id of testimonial",
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
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="description", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Testimonial updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Testimonial not found"
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
                'image' => 'image',
                'description' => 'string'
            ]);

            $testimonial = Testimonial::findOrFail($id);

            if ($request->has('name')) {
                $testimonial->name = $request->name;
            }

            if ($request->has('image')) {
                $testimonial->image = updateLoadedImage($testimonial->image, $request->image);
            }

            if ($request->has('description')) {
                $testimonial->description = $request->description;
            }

            $testimonial->update();

            return okResponse200($testimonial, "Testimonial updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404("Testimonial not found");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete a testimonial
     * @OA\Delete(
     *      path="/api/testimonials/{id}",
     *      summary="Delete a testimonial",
     *      tags={"Testimonials"},
     * 
     *       @OA\Parameter(
     *          description="id of testimonial",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Testimonial delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Testimonial not found"  
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
            $testimonial = Testimonial::findOrFail($id);

            deleteLoadedImage($testimonial->image);
            $testimonial->delete();

            return okResponse200($testimonial, "Testimonial deleted successfully");
        } catch (ModelNotFoundException) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
