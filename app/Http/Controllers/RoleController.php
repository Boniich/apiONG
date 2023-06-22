<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    private string $notFoundMsg = "Role not found";


    public function __construct()
    {
        $this->middleware('can:roles.update')->only('update');
    }

    /**
     * Display a listing of roles.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"Roles"},
     *     summary="Display a listing of roles.",
     *     @OA\Response(
     *         response=200,
     *         description="Roles retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Admin",
     *                          "guard_name": "web",
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
            $roles = Role::all();

            return okResponse200($roles, "Roles retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display an contact by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Display a rol",
     *     @OA\Parameter(
     *          description="id of rol",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Admin",
     *                          "guard_name": "web",
     *                          "created_at": "2023-06-17T18:25:26.000000Z",
     *                          "updated_at": "2023-06-17T18:25:26.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Contact not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);

            return okResponse200($role, "Role retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Update a role
     * @OA\Put(
     *      path="/api/roles/{id}",
     *      summary="Update a role",
     *      tags={"Roles"},
     *      @OA\Parameter(
     *          description="id of role",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name"},
     *          @OA\Property(property="name", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Role updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Role not found"
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
                'name' => 'required|string',
            ]);

            $role = Role::findOrFail($id);

            $role->name = $request->name;

            $role->update();
            return okResponse200($role, "Role updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
