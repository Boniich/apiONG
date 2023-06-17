<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private string $notFoundMsg = "Category not found";

    public function index()
    {
        try {
            $categories = Category::all();

            return okResponse200($categories, "Categories retrived succesfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return okResponse200($category, "Category retrived succesfully");
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
                'description' => 'required|string',
                'image' => 'image'
            ]);

            $newCategory = new Category;

            $newCategory->name = $request->name;
            $newCategory->description = $request->description;

            if ($request->has('image')) {
                $newCategory->image = $request->image;
            }

            $newCategory->save();

            return okResponse200($newCategory, "Category created succesfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $request->validate([
                'name' => 'string',
                'description' => 'string',
                'image' => 'image'
            ]);

            $newCategory = Category::findOrFail($id);

            if ($request->has('name')) {
                $newCategory->name = $request->name;
            }

            if ($request->has('description')) {
                $newCategory->description = $request->description;
            }

            if ($request->has('image')) {
                $newCategory->image = $request->image;
            }

            $newCategory->update();
            return okResponse200($category, "Categories updated succesfully");
        } catch (ModelNotFoundException $ex) {
            notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);

            deleteLoadedImage($category->image);
            $category->delete();

            return okResponse200($category, "category delete succesfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
