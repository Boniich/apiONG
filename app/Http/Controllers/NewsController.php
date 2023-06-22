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

    /**
     * Display a listing of News.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/news",
     *     tags={"News"},
     *     summary="Display a listing of news.",
     *     @OA\Response(
     *         response=200,
     *         description="News retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "news 1",
     *                          "slug": "slug of news 1",
     *                          "content": "this is the content of news 1",
     *                          "image": "14564943.png",
     *                          "user_id": 2,
     *                          "category_id": 4,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error ocurred"
     *     )
     * ) 
     */

    public function index(Request $request)
    {
        try {

            $limit = 5;

            if ($request->has('limit')) {
                $limit = $request->limit;
            }

            if ($request->has('search') && $request->has('category')) {
                $searchTerm = $request->input('search');
                $categoryId = $request->input('category');
                $news = News::where('name', 'LIKE', '%' . $searchTerm . '%')->where('category_id', $categoryId)->limit($limit)->get();
            } else if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $news = News::where('name', 'LIKE', '%' . $searchTerm . '%')->limit($limit)->get();
            } else if ($request->has('category')) {
                $categoryId = $request->input('category');
                $news = News::where('category_id', $categoryId)->limit($limit)->get();
            } else {

                $news = News::limit($limit)->get();
            }





            return okResponse200($news, "News retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display a news by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/news/{id}",
     *     tags={"News"},
     *     summary="Display a news.",
     *     @OA\Parameter(
     *          description="id of news",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="News retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "news 1",
     *                          "slug": "slug of news 1",
     *                          "content": "this is the content of news 1",
     *                          "image": "14564943.png",
     *                          "user_id": 2,
     *                          "category_id": 4,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found"
     *     )
     * ) 
     */


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

    /**
     * Create a new news.
     * Note: This endpoint does not work in swagger cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/news",
     *      summary="Create a new news",
     *      tags={"News"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","slug","content","image"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="slug", type="string", format="string" ),
     *          @OA\Property(property="content", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="user_id", type="integer", format="string"),
     *          @OA\Property(property="category_id", type="integer", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Comment created successfully"  
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="bad request"  
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="An error has occurred"
     *      )
     * )
     */

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

    /**
     * Update a news
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/news/{id}",
     *      summary="Update a news",
     *      tags={"News"},
     *      @OA\Parameter(
     *          description="id of news",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="slug", type="string", format="string" ),
     *          @OA\Property(property="content", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string"),
     *          @OA\Property(property="user_id", type="integer", format="string"),
     *          @OA\Property(property="category_id", type="integer", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="News updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="News not found"
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

    /**
     * Delete a news
     * @OA\Delete(
     *      path="/api/news/{id}",
     *      summary="Delete a news",
     *      tags={"News"},
     * 
     *       @OA\Parameter(
     *          description="id of news",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="News delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="News not found"  
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="An Error ocurred"
     *      )
     * )
     */

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
