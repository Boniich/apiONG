<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function index()
    {
        try {
            $contacts = Contact::all();

            return response()->json(successResponse($contacts, "contacts retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error Ocurred"));
        }
    }

    public function show($id)
    {
        try {
            $contact = Contact::find($id);

            if (is_null($contact)) {
                return response()->json(errorResponse("Contact not found"), 404);
            }

            return response()->json(successResponse($contact, "Contact retrived successfully"));
        } catch (\Throwable $th) {
            return response()->json(errorResponse("An Error occurred"));
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email',
                'phone' => 'required|string',
                'message' => 'required|string|max:400',
            ]);

            $newContact = new Contact;

            $newContact->name = $request->name;
            $newContact->email = $request->email;
            $newContact->phone = $request->phone;
            $newContact->message = $request->message;

            $newContact->save();

            return response()->json(successResponse($newContact, "Contact created successfully"));
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email',
                'phone' => 'required|string',
                'message' => 'required|string|max:400',
            ]);

            $contact = Contact::find($id);

            if (is_null($contact)) {
                return response()->json(errorResponse("Contact not found"), 404);
            }

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->message = $request->message;

            $contact->update();

            return okResponse200($contact, "Contact updated successfully");
        } catch (\Throwable $th) {

            return badRequestResponse400();
        }
    }

    public function delete($id)
    {
        try {
            $contact = Contact::find($id);

            if (is_null($contact)) {
                return notFoundData404("Contact not found");
            }

            return okResponse200($contact, "Contact deleted successfully");
        } catch (\Throwable $th) {

            return badRequestResponse400();
        }
    }
}
