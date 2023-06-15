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
                'name' => 'required|string',
                'logo' => 'required|image',
                'short_description' => 'required|string',
                'long_description' => 'required|string',
                'welcome_text' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'cell_phone' => 'required|string',
                'facebook_url' => 'required|string',
                'linkedin_url' => 'required|string',
                'instagram_url' => 'required|string',
                'twitter_url' => 'required|string',
            ]);

            $id = 1;
            $organization = Organization::findOrFail($id);

            $organization->name = $request->name;
            $organization->logo = updateLoadedImage($organization->logo, $request->logo);
            $organization->short_description = $request->name;
            $organization->long_description = $request->name;
            $organization->welcome_text = $request->name;
            $organization->address = $request->name;
            $organization->phone = $request->name;
            $organization->cell_phone = $request->name;
            $organization->facebook_url = $request->name;
            $organization->linkedin_url = $request->name;
            $organization->instagram_url = $request->name;
            $organization->twitter_url = $request->name;

            $organization->update();

            return response()->json(successResponse($organization, 'Organization updated successfully'));
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {
            return response()->json(errorResponse("Bad Request"), 400);
        }
    }
}
