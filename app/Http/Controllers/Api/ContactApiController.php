<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactApiController extends Controller
{
    public function __construct(protected ContactService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listPaginated());
    }

    public function store(ContactRequest $request): JsonResponse
    {
        $contact = $this->service->create($request->validated());

        return response()->json($contact, 201);
    }

    public function update(ContactRequest $request, $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $this->service->update($contact, $request->validated());

        return response()->json($contact);
    }

    public function destroy($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $this->service->delete($contact);

        return response()->json(['message' => 'Contato removido']);
    }
}
