<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    private string $notFoundMsg = "Contact not found";

    public function index()
    {
        try {
            $contacts = Contact::all();

            return okResponse200($contacts, "Contacts retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $contact = Contact::findOrFail($id);

            return okResponse200($contact, "Contact retrived successfully");
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

            return okResponse200($newContact, "Contact created successfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'string',
                'email' => 'string|email',
                'phone' => 'string',
                'message' => 'string|max:400',
            ]);

            $contact = Contact::findOrFail($id);

            if ($request->has('name')) {
                $contact->name = $request->name;
            }

            if ($request->has('email')) {

                $contact->email = $request->email;
            }

            if ($request->has('phone')) {
                $contact->phone = $request->phone;
            }

            if ($request->has('message')) {
                $contact->message = $request->message;
            }

            $contact->update();

            return okResponse200($contact, "Contact updated successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {

            return badRequestResponse400();
        }
    }

    public function delete($id)
    {
        try {
            $contact = Contact::findOrFail($id);

            $contact->delete();

            return okResponse200($contact, "Contact deleted successfully");
        } catch (ModelNotFoundException $ex) {
            return notFoundData404($this->notFoundMsg);
        } catch (\Throwable $th) {

            return badRequestResponse400();
        }
    }
}
