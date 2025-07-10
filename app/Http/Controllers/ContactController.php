<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function __construct(protected ContactService $service) {}

    public function index(): Response
    {
        $contacts = $this->service->listPaginated();

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->back()->with('success', 'Contato criado com sucesso.');
    }

    public function update(ContactRequest $request, $id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        $this->service->update($contact, $request->validated());

        return redirect()->back()->with('success', 'Contato atualizado com sucesso.');
    }

    public function destroy($id): RedirectResponse
    {
        $contact = Contact::findOrFail($id);
        $this->service->delete($contact);

        return redirect()->back()->with('success', 'Contato removido.');
    }
}
