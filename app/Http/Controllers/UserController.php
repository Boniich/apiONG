<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private array $roleDataHidding = ['created_at', 'updated_at', 'pivot', 'guard_name'];

    private string  $notFoundMsg = "User not found";

    /**
     * Display a listing of users.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/users/",
     *     tags={"Users"},
     *     summary="Display a listing of users.",
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
     *         description="Users retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Prof. Javier Klocko",
     *                          "email": "kuvalis.benedict@gmail.com",
     *                          "email_verified_at": "2023-06-17T18:25:26.000000Z",
     *                          "latitude": 0,
     *                          "longitude": 0,
     *                          "address": "1353 Dexter Motorway Suite 928",
     *                          "profile_image": "user image",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z",
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
                $users = User::where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('email', 'LIKE', $searchTerm)->limit($limit)->get();
            } else {
                $users = User::limit($limit)->get();
            }

            foreach ($users as $key => $value) {
                $users[$key]->roles->makeHidden($this->roleDataHidding);
            }

            return okResponse200($users, "Users retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display a user by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Display a user.",
     *     @OA\Parameter(
     *          description="id of user",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="User retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Prof. Javier Klocko",
     *                          "email": "kuvalis.benedict@gmail.com",
     *                          "email_verified_at": "2023-06-17T18:25:26.000000Z",
     *                          "latitude": 0,
     *                          "longitude": 0,
     *                          "address": "1353 Dexter Motorway Suite 928",
     *                          "profile_image": "user image",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z",
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->roles->makeHidden($this->roleDataHidding);

            return okResponse200($user, "User retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Create a new user.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/users/",
     *      summary="Create a new user",
     *      tags={"Users"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","email", "password","role_id"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="email", type="string", format="string" ),
     *          @OA\Property(property="password", type="string", format="string" ),
     *          @OA\Property(property="latitude", type="integer", format="string" ),
     *          @OA\Property(property="longitude", type="integer", format="string" ),
     *          @OA\Property(property="address", type="string", format="string" ),
     *          @OA\Property(property="role_id", type="integer", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="User created successfully"  
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
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'latitude' => 'integer|min:0',
                'longitude' => 'integer|min:0',
                'address' => 'string',
                'profile_image' => 'image',
                'role_id' => 'required|integer|min:1',
            ]);

            $newUser = new User;

            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($request->password);

            if ($request->has('latitude')) {
                $newUser->latitude = $request->latitude;
            }

            if ($request->has('longitude')) {
                $newUser->longitude = $request->longitude;
            }

            if ($request->has('address')) {
                $newUser->address = $request->address;
            }

            if ($request->has('profile_image')) {
                $newUser->profile_image = upLoadImage($request->profile_image);
            }

            $newUser->save();

            $newUser->assignRole($request->role_id);

            $newUser->roles->makeHidden($this->roleDataHidding);


            return okResponse200($newUser, "User created succesfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Update a user
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/users/{id}",
     *      summary="Update a user",
     *      tags={"Users"},
     *      @OA\Parameter(
     *          description="id of user",
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
     *          @OA\Property(property="email", type="string", format="string" ),
     *          @OA\Property(property="password", type="string", format="string" ),
     *          @OA\Property(property="latitude", type="integer", format="string" ),
     *          @OA\Property(property="longitude", type="integer", format="string" ),
     *          @OA\Property(property="address", type="string", format="string" ),
     *          @OA\Property(property="role_id", type="integer", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="User updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
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
                'email' => 'email|unique:users',
                'password' => '',
                'latitude' => 'integer|min:0',
                'longitude' => 'integer|min:0',
                'address' => 'string',
                'profile_image' => 'image',
                'role_id' => 'integer|min:1',
            ]);


            $user = User::findOrFail($id);

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->has('latitude')) {
                $user->latitude = $request->latitude;
            }

            if ($request->has('longitude')) {
                $user->longitude = $request->longitude;
            }

            if ($request->has('address')) {
                $user->address = $request->address;
            }

            if ($request->has('image')) {
                $user->profile_image = updateLoadedImage($user->profile_image, $request->image);
            }

            if ($request->has('role_id')) {
                $user->roles()->sync($request->role_id);
            }

            $user->update();
            $user->roles->makeHidden($this->roleDataHidding);

            return okResponse200($user, "User created succesfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    /**
     * Delete a user
     * @OA\Delete(
     *      path="/api/users/{id}",
     *      summary="Delete a user",
     *      tags={"Users"},
     * 
     *       @OA\Parameter(
     *          description="id of user",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"  
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
            $user = User::findOrFail($id);

            $user->roles->makeHidden($this->roleDataHidding);

            deleteLoadedImage($user->profile_image);
            $user->delete();

            return okResponse200($user, "User deleted successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
