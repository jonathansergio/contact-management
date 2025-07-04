<?php

use App\Models\Contact;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Contacts/Index', [
        'contacts' => Contact::paginate(10),
    ]);
});
