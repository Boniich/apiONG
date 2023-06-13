<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        try {
            $data = Member::all();

            return response()->json(successResponse($data, "Members retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"));
        }
    }

    public function show($id)
    {
        try {
            $member = Member::find($id);

            if (is_null($member)) {
                return response()->json(errorResponse("Member not found"), 404);
            }

            return response()->json(successResponse($member, "Member retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"));
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image',
                'facebook_url' => 'required|string',
                'linkedin_url' => 'required|string',
            ]);

            $newMember = new Member;

            $newMember->full_name = $request->full_name;
            $newMember->description = $request->description;
            $newMember->image = upLoadImage($request->image);
            $newMember->facebook_url = $request->facebook_url;
            $newMember->linkedin_url = $request->linkedin_url;

            $newMember->save();

            return response()->json(successResponse($newMember, "Member created successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("Bad Request"), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'full_name' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image',
                'facebook_url' => 'required|string',
                'linkedin_url' => 'required|string',
            ]);

            $member = Member::find($id);

            if (is_null($member)) {
                return response()->json(errorResponse("Member not found"), 404);
            }

            $member->full_name = $request->full_name;
            $member->description = $request->description;
            $member->image = updateLoadedImage($member->image, $request->image);
            $member->facebook_url = $request->facebook_url;
            $member->linkedin_url = $request->linkedin_url;

            $member->update();


            return response()->json(successResponse($member, "Member updated successfully"));
        } catch (\Throwable $th) {

            return response()->json(errorResponse("Bad Request"), 400);
        }
    }

    public function delete($id)
    {
        try {
            $member = Member::find($id);

            if (is_null($member)) {
                return response()->json(errorResponse("Member not found"), 404);
            }

            deleteLoadedImage($member->image);
            $member->delete();

            return response()->json(successResponse($member, "Member deleted successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An error has occurred"));
        }
    }
}
