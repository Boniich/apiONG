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
                User::findOrFail($request->user_id);

                $newNews->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                Category::findOrFail($request->category_id);

                $newNews->user_id = $request->category_id;
            }

            $newNews->save();

            return okResponse200($newNews, "Activity created successfully");
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
                'content' => 'string',
                'image' => 'image',
                'user_id' => 'integer',
                'category_id' => 'integer'
            ]);

            $news = News::findOrFail($id);


            if ($request->has($request->name)) {
                $news->name = $request->name;
            }

            if ($request->has($request->slug)) {
                $news->slug = $request->slug;
            }


            if ($request->has($request->content)) {
                $news->content = $request->content;
            }

            if ($request->has($request->image)) {
                $news->image = updateLoadedImage($news->image, $request->image);
            }

            if ($request->has('user_id')) {
                $user = User::findOrFail($request->user_id);

                if (is_null($user)) {
                    return notFoundData404("User not found");
                }

                $news->user_id = $request->user_id;
            }

            if ($request->has('category_id')) {
                $category = Category::findOrFail($request->category_id);

                if (is_null($category)) {
                    return notFoundData404("Category not found");
                }

                $news->user_id = $request->category_id;
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
