<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

    private string $notFoundMsg = "Testimonial not found";

    public function index()
    {
        try {
            $testimonial = Testimonial::all();

            return okResponse200($testimonial, "Testimonials retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $testimonial = Testimonial::findOrfail($id);

            return okResponse200($testimonial, "Testimonial retrived successfully");
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
                'image' => 'required|image',
                'description' => 'required|string'
            ]);

            $newTestimonial = new Testimonial;

            $newTestimonial->name = $request->name;
            $newTestimonial->image = upLoadImage($request->image);
            $newTestimonial->description = $request->description;

            $newTestimonial->save();

            return okResponse200($newTestimonial, "Testimonial created successfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'image' => 'required|image',
                'description' => 'required|string'
            ]);

            $testimonial = Testimonial::findOrFail($id);

            $testimonial->name = $request->name;
            $testimonial->image = updateLoadedImage($testimonial->image, $request->image);
            $testimonial->description = $request->description;

            $testimonial->update();

            return okResponse200($testimonial, "Testimonial updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404("Testimonial not found");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
    public function delete($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);

            deleteLoadedImage($testimonial->image);
            $testimonial->delete();

            return okResponse200($testimonial, "Testimonial deleted successfully");
        } catch (ModelNotFoundException) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
