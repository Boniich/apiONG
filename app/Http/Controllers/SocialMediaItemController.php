<?php

namespace App\Http\Controllers;

use App\Models\SocialMediaItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SocialMediaItemController extends Controller
{

    private string $notFoundMsg = "Social media not found";

    /**
     * Display a listing of social media items.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/socialmediaitems",
     *     tags={"SocialMediaItems"},
     *     summary="Display a listing of social media items.",
     *     @OA\Response(
     *         response=200,
     *         description="Social media items retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 2,
     *                          "name": "social 1",
     *                          "image": "1687382380.jpg",
     *                          "url": "url",
     *                          "created_at": "2023-06-21T21:17:01.000000Z",
     *                          "updated_at": "2023-06-21T21:19:40.000000Z"
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
            $socialMediaItems = SocialMediaItem::all();

            return okResponse200($socialMediaItems, "Social media items retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display a social media item by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/socialmediaitems/{id}",
     *     tags={"SocialMediaItems"},
     *     summary="Display a social media item.",
     *     @OA\Parameter(
     *          description="id of social media item",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Social media item retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 2,
     *                          "name": "social 1",
     *                          "image": "1687382380.jpg",
     *                          "url": "url",
     *                          "created_at": "2023-06-21T21:17:01.000000Z",
     *                          "updated_at": "2023-06-21T21:19:40.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social Media item not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $socialMediaItem = SocialMediaItem::findOrFail($id);

            return okResponse200($socialMediaItem, "Social Media Item retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Create a new social media item.
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/socialmediaitems",
     *      summary="Create a new social media item",
     *      tags={"SocialMediaItems"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name", "image","url"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="url", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Social media item created successfully"  
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"  
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
                'url' => 'required|string'
            ]);

            $newSocialMediaItem = new SocialMediaItem;

            $newSocialMediaItem->name = $request->name;
            $newSocialMediaItem->image = upLoadImage($request->image);
            $newSocialMediaItem->url = $request->url;

            $newSocialMediaItem->save();

            return okResponse200($newSocialMediaItem, "Social Media item created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Update a social media item
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/socialmediaitems/{id}",
     *      summary="Update a social media item",
     *      tags={"SocialMediaItems"},
     *      @OA\Parameter(
     *          description="id of social media item",
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
     *          @OA\Property(property="url", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Social media item updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Social media item not found"
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
                'url' => 'string'
            ]);

            $socialMediaItem = SocialMediaItem::findOrFail($id);

            if ($request->has('name')) {
                $socialMediaItem->name = $request->name;
            }

            if ($request->has('image')) {
                $socialMediaItem->image = updateLoadedImage($socialMediaItem->image, $request->image);
            }

            if ($request->has('url')) {
                $socialMediaItem->url = $request->url;
            }

            $socialMediaItem->update();

            return okResponse200($socialMediaItem, "Social Media item updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete a social media item
     * @OA\Delete(
     *      path="/api/socialmediaitems/{id}",
     *      summary="Delete a social media item",
     *      tags={"SocialMediaItems"},
     * 
     *       @OA\Parameter(
     *          description="id of social media item",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Social media item delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Social media item not found"  
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
            $socialMediaItem = SocialMediaItem::findOrFail($id);

            deleteLoadedImage($socialMediaItem->image);
            $socialMediaItem->delete();

            return okResponse200($socialMediaItem, "Social Media Item delete successfully");
        } catch (ModelNotFoundException $ex) {

            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
