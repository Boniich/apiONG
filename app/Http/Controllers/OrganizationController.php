<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

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
                'logo' => 'required|string',
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
            $organization = Organization::find($id);

            if (is_null($organization)) {
                return response()->json(errorResponse("Organization not found"), 404);
            }

            $organization->name = $request->name;
            $organization->logo = $request->logo; //should be an image
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
        } catch (\Throwable $th) {
            return response()->json(errorResponse("Bad Request"), 400);
        }
    }
}
