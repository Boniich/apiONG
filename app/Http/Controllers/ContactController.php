<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    private string $notFoundMsg = "Contact not found";

    /**
     * Display a listing of Contacts.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/contacts",
     *     tags={"Contacts"},
     *     summary="Display a listing of contacts.",
     *     @OA\Response(
     *         response=200,
     *         description="Contacts retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Pedro",
     *                          "email": "pedro@gmail.com",
     *                          "phone": "000000",
     *                          "message": "Hello",
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
            $contacts = Contact::all();

            return okResponse200($contacts, "Contacts retrived successfully");
        } catch (\Throwable $th) {
            return anErrorOcurred();
        }
    }


    /**
     * Display an contact by id.
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/contacts/{id}",
     *     tags={"Contacts"},
     *     summary="Display a contact.",
     *     @OA\Parameter(
     *          description="id of contact",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact retrived succesffully",
     *         content={
     *                  @OA\MediaType(
     *                      mediaType="application/json",
     *                      example={
     *                          "id": 1,
     *                          "name": "Pedro",
     *                          "email": "pedro@gmail.com",
     *                          "phone": "000000",
     *                          "message": "Hello",
     *                          "created_at": "2023-06-17T18:25:27.000000Z",
     *                          "updated_at": "2023-06-17T18:25:27.000000Z"
     *                      })},
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Contact not found"
     *     )
     * ) 
     */

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

    /**
     * Create a new contact.
     * @OA\Post(
     *      path="/api/contacts",
     *      summary="Create a new contact",
     *      tags={"Contacts"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","email","phone","message"},
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="email", type="string", format="string" ),
     *          @OA\Property(property="phone", type="string", format="string"),
     *          @OA\Property(property="message", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Contact created successfully"  
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

    /**
     * Update a contact
     * @OA\Put(
     *      path="/api/contacts/{id}",
     *      summary="Update an contact",
     *      tags={"Contacts"},
     *      @OA\Parameter(
     *          description="id of contact",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", format="string"),
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="email", type="string", format="string" ),
     *          @OA\Property(property="phone", type="string", format="string"),
     *          @OA\Property(property="message", type="string", format="string"),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="Contact updated successfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Contact not found"
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

    /**
     * Delete a contact
     * @OA\Delete(
     *      path="/api/contacts/{id}",
     *      summary="Delete a contact",
     *      tags={"Contacts"},
     * 
     *       @OA\Parameter(
     *          description="id of contact",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Contact deleted succesfully"  
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Contact not found"  
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
