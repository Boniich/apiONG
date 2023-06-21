<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private string $notFoundMsg = "News not found";

    public function index()
    {
        try {
            $news = News::all();

            return okResponse200($news, "News retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $news = News::findOrfail($id);

            return okResponse200($news, "News retrived successfully");
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
                'content' => 'required|string',
                'image' => 'required|image',
                'user_id' => 'integer',
                'category_id' => 'integer'
            ]);

            $newNews = new News();

            $newNews->name = $request->name;
            $newNews->slug = $request->slug;
            $newNews->content = $request->content;
            $newNews->image = upLoadImage($request->image);


            if ($request->has('user_id')) {

                if (is_null(User::find($request->user_id))) {
                    return notFoundData404("User not found");
                }

                $newNews->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {

                if (is_null(Category::find($request->category_id))) {
                    return notFoundData404("Category not found");
                }
                $newNews->category_id = $request->category_id;
            }

            $newNews->save();

            return okResponse200($newNews, "News created successfully");
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
                'content' => 'string',
                'image' => 'image',
                'user_id' => 'integer',
                'category_id' => 'integer'
            ]);

            $news = News::findOrFail($id);


            if ($request->has('name')) {
                $news->name = $request->name;
            }

            if ($request->has('slug')) {
                $news->slug = $request->slug;
            }


            if ($request->has('content')) {
                $news->content = $request->content;
            }

            if ($request->has('image')) {
                $news->image = updateLoadedImage($news->image, $request->image);
            }

            if ($request->has('user_id')) {
                $user = User::find($request->user_id);

                if (is_null($user)) {
                    return notFoundData404("User not found");
                }

                $news->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                $category = Category::find($request->category_id);

                if (is_null($category)) {
                    return notFoundData404("Category not found");
                }

                $news->category_id = $request->category_id;
            }


            $news->update();

            return okResponse200($news, "News updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
    public function delete($id)
    {
        try {
            $news = News::findOrFail($id);

            deleteLoadedImage($news->image);
            $news->delete();

            return okResponse200($news, "News deleted successfully");
        } catch (ModelNotFoundException) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }
}
