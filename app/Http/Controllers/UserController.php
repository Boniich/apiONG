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

    public function index()
    {
        try {
            $users = User::all();

            foreach ($users as $key => $value) {
                $users[$key]->roles->makeHidden($this->roleDataHidding);
            }

            return okResponse200($users, "User retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

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
            $newUser->latitude = $request->latitude;
            $newUser->longitude = $request->longitude;
            $newUser->address = $request->address;
            $newUser->profile_image = $request->image;

            $newUser->save();

            $newUser->assignRole($request->role_id);

            $newUser->roles->makeHidden($this->roleDataHidding);


            return okResponse200($newUser, "User created succesffully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role_id' => 'integer|min:1',
                'latitude' => 'integer|min:0',
                'longitude' => 'integer|min:0',
                'address' => 'string',
                'profile_image' => 'image',
            ]);

            $newUser = User::findOrFail($id);

            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($request->password);
            $newUser->roles()->sync($request->role_id);
            $newUser->latitude = $request->latitude;
            $newUser->longitude = $request->longitude;
            $newUser->address = $request->address;
            $newUser->profile_image = $request->image;

            $newUser->update();

            return okResponse200($newUser, "User created succesffully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function detele($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->roles->makeHidden($this->roleDataHidding);

            deleteLoadedImage($user->profile_image);
            $user->delete();
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
