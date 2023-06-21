<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    private string $notFoundMsg = "Organization not found";

    public function index()
    {
        try {
            $organization = Organization::all();

            return response()->json(successResponse($organization, "Organization retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"), 400);
        }
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'name' => 'string',
                'logo' => 'image',
                'short_description' => 'string',
                'long_description' => 'string',
                'welcome_text' => 'string',
                'address' => 'string',
                'phone' => 'string',
                'cell_phone' => 'string',
                'facebook_url' => 'string',
                'linkedin_url' => 'string',
                'instagram_url' => 'string',
                'twitter_url' => 'string',
            ]);

            $id = 1;
            $organization = Organization::findOrFail($id);

            if ($request->has('name')) {
                $organization->name = $request->name;
            }

            if ($request->has('logo')) {
                $organization->logo = updateLoadedImage($organization->logo, $request->logo);
            }

            if ($request->has('short_description')) {
                $organization->short_description = $request->short_description;
            }

            if ($request->has('long_description')) {
                $organization->long_description = $request->long_description;
            }

            if ($request->has('welcome_text')) {
                $organization->welcome_text = $request->welcome_text;
            }

            if ($request->has('address')) {
                $organization->address = $request->address;
            }

            if ($request->has('phone')) {
                $organization->phone = $request->phone;
            }

            if ($request->has('cell_phone')) {
                $organization->cell_phone = $request->cell_phone;
            }

            if ($request->has('facebook_url')) {
                $organization->facebook_url = $request->facebook_url;
            }

            if ($request->has('linkedin_url')) {
                $organization->linkedin_url = $request->linkedin_url;
            }

            if ($request->has('instagram_url')) {
                $organization->instagram_url = $request->instagram_url;
            }

            if ($request->has('twitter_url')) {
                $organization->twitter_url = $request->twitter_url;
            }

            $organization->update();

            return response()->json(successResponse($organization, 'Organization updated successfully'));
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return response()->json(errorResponse("Bad Request"), 400);
        }
    }
}
