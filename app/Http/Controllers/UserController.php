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

    public function index(Request $request)
    {
        try {
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $users = User::where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('email', 'LIKE', $searchTerm)->limit(10)->get();
            } else {
                $users = User::limit(10)->get();
            }

            foreach ($users as $key => $value) {
                $users[$key]->roles->makeHidden($this->roleDataHidding);
            }

            return okResponse200($users, "Users retrived successfully");
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
