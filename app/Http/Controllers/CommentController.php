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

    public function index()
    {
        try {
            $comments = Comment::all();

            return okResponse200($comments, "Comments retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

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
                $news = News::findOrFail($request->news_id);

                if (is_null($news)) {
                    return notFoundData404("News not found");
                }

                $newComment->news_id = $request->news_id;
            }

            if ($request->has('user_id')) {
                $user = User::findOrFail($request->user_id);

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
                $news = News::findOrFail($request->news_id);

                if (is_null($news)) {
                    return notFoundData404("News not found");
                }

                $comment->news_id = $request->news_id;
            }


            if ($request->has('user_id')) {
                $user = User::findOrFail($request->user_id);

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
