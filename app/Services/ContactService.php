<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactService
{
    public function listPaginated(): LengthAwarePaginator
    {
        return Contact::paginate(10);
    }

    public function create(array $data): Contact
    {
        $data['phone'] = preg_replace('/\D/', '', $data['phone']);

        return Contact::create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $data['phone'] = preg_replace('/\D/', '', $data['phone']);
        $contact->update($data);

        return $contact;
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
}
