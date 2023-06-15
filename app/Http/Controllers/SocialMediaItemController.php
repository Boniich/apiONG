<?php

namespace App\Http\Controllers;

use App\Models\SocialMediaItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SocialMediaItemController extends Controller
{

    private string $notFoundMsg = "Social media not found";

    public function index()
    {
        try {
            $socialMediaItems = SocialMediaItem::all();

            return okResponse200($socialMediaItems, "Social media items retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $socialMediaItem = SocialMediaItem::findOrFail($id);

            return okResponse200($socialMediaItem, "Social Media Item retrived successfully");
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
                'image' => 'required|image',
                'url' => 'required|string'
            ]);

            $newSocialMediaItem = new SocialMediaItem;

            $newSocialMediaItem->name = $request->name;
            $newSocialMediaItem->image = upLoadImage($request->image);
            $newSocialMediaItem->url = $request->url;

            $newSocialMediaItem->save();

            return okResponse200($newSocialMediaItem, "Social Media item created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
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
                'url' => 'required|string'
            ]);

            $socialMediaItem = SocialMediaItem::findOrFail($id);

            $socialMediaItem->name = $request->name;
            $socialMediaItem->image = updateLoadedImage($socialMediaItem->image, $request->image);
            $socialMediaItem->url = $request->url;

            $socialMediaItem->update();

            return okResponse200($socialMediaItem, "Social Media item created successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function delete($id)
    {
        try {
            $socialMediaItem = SocialMediaItem::findOrFail($id);

            deleteLoadedImage($socialMediaItem->image);
            $socialMediaItem->delete();

            return okResponse200($socialMediaItem, "Social Media Item delete successfully");
        } catch (ModelNotFoundException $ex) {

            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }
}
