<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    private string $notFoundMsg = "Organization not found";


    /**
     * Display a Organization
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/organization",
     *     tags={"Organization"},
     *     summary="Display a organization.",
     *     @OA\Response(
     *         response=200,
     *         description="Organization retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "new org",
     *                          "logo": "image.png",
     *                          "short_description": "short description",
     *                          "long_description": "long description",
     *                          "welcome_text": "welcome text",
     *                          "address": "Calle falsa 123",
     *                          "phone": "00000",
     *                          "cell_phone": "110000",
     *                          "facebook_url": "face",
     *                          "linkedin_url": "linkedin",
     *                          "instagram_url": "insta",
     *                          "twitter_url": "twitter",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-21T18:44:12.000000Z"
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
            $organization = Organization::all();

            return response()->json(successResponse($organization, "Organization retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"), 400);
        }
    }

    /**
     * Update a organization data
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/organization",
     *      summary="Update organization",
     *      tags={"Organization"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="log", type="string", format="string" ),
     *          @OA\Property(property="short_description", type="string", format="string"),
     *          @OA\Property(property="long_description", type="string", format="string"),
     *          @OA\Property(property="welcome_text", type="string", format="string"),
     *          @OA\Property(property="address", type="string", format="string"),
     *          @OA\Property(property="phone", type="string", format="string"),
     *          @OA\Property(property="cell_phone", type="string", format="string"),
     *          @OA\Property(property="facebook_url", type="string", format="string"),
     *          @OA\Property(property="instagram_url", type="string", format="string"),
     *          @OA\Property(property="twitter_url", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Organization updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="bad request"  
     *      ),
     * )
     */

    public function update(Request $request)
    {
        try {

            $request->validate([
                'name' => 'string',
                'logo' => 'image',
                'short_description' => 'string',
                'long_description' => 'string',
                'welcome_text' => 'string',
                'address' => 'string',
                'phone' => 'string',
                'cell_phone' => 'string',
                'facebook_url' => 'string',
                'linkedin_url' => 'string',
                'instagram_url' => 'string',
                'twitter_url' => 'string',
            ]);

            $id = 1;
            $organization = Organization::findOrFail($id);

            if ($request->has('name')) {
                $organization->name = $request->name;
            }

            if ($request->has('logo')) {
                $organization->logo = updateLoadedImage($organization->logo, $request->logo);
            }

            if ($request->has('short_description')) {
                $organization->short_description = $request->short_description;
            }

            if ($request->has('long_description')) {
                $organization->long_description = $request->long_description;
            }

            if ($request->has('welcome_text')) {
                $organization->welcome_text = $request->welcome_text;
            }

            if ($request->has('address')) {
                $organization->address = $request->address;
            }

            if ($request->has('phone')) {
                $organization->phone = $request->phone;
            }

            if ($request->has('cell_phone')) {
                $organization->cell_phone = $request->cell_phone;
            }

            if ($request->has('facebook_url')) {
                $organization->facebook_url = $request->facebook_url;
            }

            if ($request->has('linkedin_url')) {
                $organization->linkedin_url = $request->linkedin_url;
            }

            if ($request->has('instagram_url')) {
                $organization->instagram_url = $request->instagram_url;
            }

            if ($request->has('twitter_url')) {
                $organization->twitter_url = $request->twitter_url;
            }

            $organization->update();

            return response()->json(successResponse($organization, 'Organization updated successfully'));
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return response()->json(errorResponse("Bad Request"), 400);
        }
    }
}
