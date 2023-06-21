<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    private string $notFoundMsg = "Slide not found";

    public function index()
    {
        try {
            $slides = Slide::all();

            return okResponse200($slides, "Slides retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $slide = Slide::findOrfail($id);

            return okResponse200($slide, "Slider retrived successfully");
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
                'description' => 'required|string',
                'image' => 'required|image',
                'order' => 'required|integer',
                'user_id' => 'integer'
            ]);

            $newSlide = new Slide;

            $newSlide->name = $request->name;
            $newSlide->description = $request->description;
            $newSlide->image = upLoadImage($request->image);
            $newSlide->order = $request->order;

            if ($request->has('user_id')) {
                if (is_null(User::find($request->user_id))) {
                    return notFoundData404("User not found");
                }

                $newSlide->user_id = $request->user_id;
            }

            $newSlide->save();

            return okResponse200($newSlide, "Slide created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404("ID of USER not found");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'string',
                'description' => 'string',
                'image' => 'image',
                'order' => 'integer',
                'user_id' => 'integer'
            ]);

            $slide = Slide::findOrFail($id);


            if ($request->has('name')) {
                $slide->name = $request->name;
            }

            if ($request->has('description')) {
                $slide->description = $request->description;
            }

            if ($request->has('image')) {
                $slide->image = updateLoadedImage($slide->image, $request->image);
            }

            if ($request->has('order')) {
                $slide->order = $request->order;
            }

            if ($request->has('user_id')) {

                if (is_null(User::find($request->user_id))) {
                    return notFoundData404("User not found");
                }

                $slide->user_id = $request->user_id;
            }


            $slide->update();

            return okResponse200($slide, "Slide updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
    public function delete($id)
    {
        try {
            $slide = Slide::findOrFail($id);

            deleteLoadedImage($slide->image);
            $slide->delete();

            return okResponse200($slide, "Slide deleted successfully");
        } catch (ModelNotFoundException) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
