<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private string $notFoundMsg = "Activity not found";

    public function index()
    {
        try {
            $activities = Activity::all();

            return okResponse200($activities, "Activities retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $activity = Activity::findOrfail($id);

            return okResponse200($activity, "Activity retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

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
                User::findOrFail($request->user_id);

                $newActivity->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                Category::findOrFail($request->category_id);

                $newActivity->user_id = $request->category_id;
            }

            $newActivity->save();

            return okResponse200($newActivity, "Activity created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404("ID of USER or CATEGORY not found");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

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


            if ($request->has($request->name)) {
                $activity->name = $request->name;
            }

            if ($request->has($request->slug)) {
                $activity->slug = $request->slug;
            }


            if ($request->has($request->description)) {
                $activity->description = $request->description;
            }

            if ($request->has($request->image)) {
                $activity->image = updateLoadedImage($activity->image, $request->image);
            }

            if ($request->has('user_id')) {
                $user = User::findOrFail($request->user_id);

                if (is_null($user)) {
                    return notFoundData404("User not found");
                }

                $activity->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                $category = Category::findOrFail($request->category_id);

                if (is_null($category)) {
                    return notFoundData404("Category not found");
                }

                $activity->user_id = $request->category_id;
            }


            $activity->update();

            return okResponse200($activity, "Activity updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
    public function delete($id)
    {
        try {
            $activity = Activity::findOrFail($id);

            deleteLoadedImage($activity->image);
            $activity->delete();

            return okResponse200($activity, "Activity deleted successfully");
        } catch (ModelNotFoundException) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
