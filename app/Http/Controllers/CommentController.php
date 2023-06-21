<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private string $notFoundMsg = "Comments not found";

    /**
     * Display a listing of Comments.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/comments",
     *     tags={"Comments"},
     *     summary="Display a listing of comments.",
     *     @OA\Response(
     *         response=200,
     *         description="Comments retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "text": "comment 1",
     *                          "visible": true,
     *                          "image": "image-activity-seeder.png",
     *                          "news_id": 4,
     *                          "user_id": 2,
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

    public function index()
    {
        try {
            $comments = Comment::all();

            return okResponse200($comments, "Comments retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    /**
     * Display an comments by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Display a comment.",
     *     @OA\Parameter(
     *          description="id of comment",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "text": "comment 1",
     *                          "visible": true,
     *                          "image": "image.png",
     *                          "news_id": 4,
     *                          "user_id": 2,
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * ) 
     */

    public function show($id)
    {
        try {
            $comment = Comment::findOrfail($id);

            return okResponse200($comment, "Comment retrived successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }


    /**
     * Create a new comment.
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Post(
     *      path="/api/comments",
     *      summary="Create a new comment",
     *      tags={"Comments"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"text"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="text", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="news_id", type="integer", format="string"),
     *          @OA\Property(property="user_id", type="integer", format="string"),
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
                'text' => 'required|string',
                'image' => 'image',
                'visible' => 'boolean',
                'news_id' => 'integer',
                'user_id' => 'integer',

            ]);

            $newComment = new Comment();
            $visible = true;

            $newComment->text = $request->text;

            if ($request->has('image')) {
                $newComment->image = upLoadImage($request->image);
            }

            if ($request->has('visible')) {
                $newComment->visible = $request->visible;
            } else {
                $newComment->visible = $visible;
            }

            if ($request->has('news_id')) {
                $news = News::find($request->news_id);

                if (is_null($news)) {
                    return notFoundData404("News not found");
                }

                $newComment->news_id = $request->news_id;
            }

            if ($request->has('user_id')) {
                $user = User::find($request->user_id);

                if (is_null($user)) {
                    return notFoundData404("User not found");
                }

                $newComment->user_id = $request->user_id;
            }

            $newComment->save();

            return okResponse200($newComment, "Comment created successfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }



    /**
     * Update a comment
     * Note: This endpoint does not work in swagger if you add the field "image" cause is not possible upload an image here.
     * @OA\Put(
     *      path="/api/comments/{id}",
     *      summary="Update an comment",
     *      tags={"Comments"},
     *      @OA\Parameter(
     *          description="id of comment",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"text"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="text", type="string", format="string"),
     *          @OA\Property(property="image", type="string", format="string" ),
     *          @OA\Property(property="news_id", type="integer", format="string"),
     *          @OA\Property(property="user_id", type="integer", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Comment updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Comment not found"
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
                'text' => 'string',
                'image' => 'image',
                'visible' => 'boolean',
                'news_id' => 'integer',
                'user_id' => 'integer',
            ]);

            $comment = Comment::findOrFail($id);


            if ($request->has('text')) {
                $comment->text = $request->text;
            }

            if ($request->has('image')) {
                $comment->image = updateLoadedImage($comment->image, $request->image);
            }

            if ($request->has('visible')) {
                $comment->visible = $request->visible;
            }

            if ($request->has('news_id')) {
                $news = News::find($request->news_id);

                if (is_null($news)) {
                    return notFoundData404("News not found");
                }

                $comment->news_id = $request->news_id;
            }


            if ($request->has('user_id')) {
                $user = User::find($request->user_id);

                if (is_null($user)) {
                    return notFoundData404("User not found");
                }

                $comment->user_id = $request->user_id;
            }

            $comment->update();

            return okResponse200($comment, "Comment updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }


    /**
     * Delete a comment
     * @OA\Delete(
     *      path="/api/comments/{id}",
     *      summary="Delete an activity",
     *      tags={"Comments"},
     * 
     *       @OA\Parameter(
     *          description="id of comment",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Comment delete succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Comment not found"  
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
            $comment = Comment::findOrFail($id);

            deleteLoadedImage($comment->image);
            $comment->delete();

            return okResponse200($comment, "Comment deleted successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
