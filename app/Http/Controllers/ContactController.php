<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function index(): JsonResponse
    {
        $contacts = Contact::paginate(10);

        return response()->json($contacts);
    }

    public function store(ContactRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);
        $contact = Contact::create($validated);

        return response()->json($contact, 201);
    }

    public function update(ContactRequest $request, $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $validated = $request->validated();
        $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);
        $contact->update($validated);

        return response()->json($contact);
    }

    public function destroy($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Contact deleted']);
    }
}
