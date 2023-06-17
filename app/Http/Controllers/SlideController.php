<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    private string $notFoundMsg = "Slider not found";

    public function index()
    {
        try {
            $sliders = Slide::all();

            return okResponse200($sliders, "Sliders retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $slider = Slide::findOrfail($id);

            return okResponse200($slider, "Slider retrived successfully");
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

            $newSlider = new Slide;

            $newSlider->name = $request->name;
            $newSlider->description = $request->description;
            $newSlider->image = upLoadImage($request->image);
            $newSlider->order = $request->order;

            if ($request->has('user_id')) {
                User::findOrFail($request->user_id);

                $newSlider->user_id = $request->user_id;
            }

            $newSlider->save();

            return okResponse200($newSlider, "Slider created successfully");
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

            $slider = Slide::findOrFail($id);


            if ($request->has($request->name)) {
                $slider->name = $request->name;
            }

            if ($request->has($request->description)) {
                $slider->description = $request->description;
            }

            if ($request->has($request->image)) {
                $slider->image = updateLoadedImage($slider->image, $request->image);
            }

            if ($request->has($request->order)) {
                $slider->order = $request->order;
            }

            if ($request->has('user_id')) {
                User::findOrFail($request->user_id);

                $slider->user_id = $request->user_id;
            }


            $slider->update();

            return okResponse200($slider, "Slider updated successfully");
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
