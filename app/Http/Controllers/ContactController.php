<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    private string $notFoundMsg = "Contact not found";

    public function index()
    {
        try {
            $contacts = Contact::all();

            return okResponse200($contacts, "contacts retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }

    public function show($id)
    {
        try {
            $contact = Contact::find($id);

            if (is_null($contact)) {
                return notFoundData404($this->notFoundMsg);
            }

            return okResponse200($contact, "Contact retrived successfully");
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
                'name' => 'required|string',
                'email' => 'required|string|email',
                'phone' => 'required|string',
                'message' => 'required|string|max:400',
            ]);

            $contact = Contact::find($id);

            if (is_null($contact)) {
                return notFoundData404($this->notFoundMsg);
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
                return notFoundData404($this->notFoundMsg);
            }

            return okResponse200($contact, "Contact deleted successfully");
        } catch (\Throwable $th) {

            return badRequestResponse400();
        }
    }
}
