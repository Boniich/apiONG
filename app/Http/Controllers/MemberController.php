<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    private string $notFoundMsg = "Member not found";


    /**
     * Display a listing of members.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/members",
     *     tags={"Members"},
     *     summary="Display a listing of members.",
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
     *         description="Members retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "full_name": "Pedro",
     *                          "description": "Pedro is important member",
     *                          "image": "image.png",
     *                          "facebook_url": "facebook",
     *                          "linkedin_url": "linkedin",
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

    public function index(Request $request)
    {
        try {
            $limit = 5;

            if ($request->has('limit')) {
                $limit = $request->limit;
            }

            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $data = Member::where('full_name', 'LIKE', '%' . $searchTerm . '%')->limit($limit)->get();
            } else {
                $data = Member::limit($limit)->get();
            }

            return response()->json(successResponse($data, "Members retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"));
        }
    }


    /**
     * Display a member by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/members/{id}",
     *     tags={"Members"},
     *     summary="Display a member.",
     *     @OA\Parameter(
     *          description="id of member",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Member retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "full_name": "Pedro",
     *                          "description": "Pedro is important member",
     *                          "image": "image.png",
     *                          "facebook_url": "facebook",
     *                          "linkedin_url": "linkedin",
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Member not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $member = Member::findOrFail($id);

            return response()->json(successResponse($member, "Member retrived successfully"));
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"));
        }
    }

    /**
     * Create a new member.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/members",
     *      summary="Create a new member",
     *      tags={"Members"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"full_name", "description", "image", "facebook_url", "linkedin_url"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="full_name", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="facebook_url", type="string", format="string"),
     *          @OA\Property(property="linkedin_url", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Member created successfully"  
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
                'full_name' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image',
                'facebook_url' => 'required|string',
                'linkedin_url' => 'required|string',
            ]);

            $newMember = new Member;

            $newMember->full_name = $request->full_name;
            $newMember->description = $request->description;
            $newMember->image = upLoadImage($request->image);
            $newMember->facebook_url = $request->facebook_url;
            $newMember->linkedin_url = $request->linkedin_url;

            $newMember->save();

            return response()->json(successResponse($newMember, "Member created successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("Bad Request"), 400);
        }
    }

    /**
     * Update a member
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/members/{id}",
     *      summary="Update an member",
     *      tags={"Members"},
     *      @OA\Parameter(
     *          description="id of member",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="full_name", type="string", format="string"),
     *          @OA\Property(property="description", type="string", format="string" ),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="facebook_url", type="string", format="string"),
     *          @OA\Property(property="linkedin_url", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Member updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Member not found"
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
                'full_name' => 'string',
                'description' => 'string',
                'image' => 'image',
                'facebook_url' => 'string',
                'linkedin_url' => 'string',
            ]);

            $member = Member::findOrFail($id);


            if ($request->has('full_name')) {
                $member->full_name = $request->full_name;
            }

            if ($request->has('description')) {
                $member->description = $request->description;
            }

            if ($request->has('image')) {
                $member->image = updateLoadedImage($member->image, $request->image);
            }

            if ($request->has('facebook_url')) {
                $member->facebook_url = $request->facebook_url;
            }

            if ($request->has('linkedin_url')) {
                $member->linkedin_url = $request->linkedin_url;
            }

            $member->update();


            return response()->json(successResponse($member, "Member updated successfully"));
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {

            return response()->json(errorResponse("Bad Request"), 400);
        }
    }

    /**
     * Delete a member
     * @OA\Delete(
     *      path="/api/members/{id}",
     *      summary="Delete a member",
     *      tags={"Members"},
     * 
     *       @OA\Parameter(
     *          description="id of member",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Member delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Member not found"  
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
            $member = Member::findOrFail($id);

            deleteLoadedImage($member->image);
            $member->delete();

            return response()->json(successResponse($member, "Member deleted successfully"));
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An error has occurred"));
        }
    }
}
