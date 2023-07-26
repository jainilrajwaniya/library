<?php

return [
    'front_signup_validation' => [
        'name' => 'required|max:150',
        'email' => 'required|email|max:150|unique:users,email,',
        'password' => 'required|max:50|min:8',
    ],
    'front_signin_validation' => [
        'email' => 'required|email',
        'password' => 'required',
    ],
    'save_book' => [
        'title' => 'required',
        'author' => 'required',
        'isbn' => 'required',
        'copies' => 'required|numeric',
        'published_at' => 'required|date',
    ],
    'edit_book' => [
        'id' => 'required|exists:books,id',
        'title' => 'required',
        'author' => 'required',
        'isbn' => 'required',
        'copies' => 'required|numeric',
        'published_at' => 'required|date',
    ],
    'checkout' => [
        'user_id' => 'required|exists:users,id',
        'book_id' => 'required|exists:books,id',
    ],
    'checkout_return' => [
        'id' => 'required|exists:checkouts,id',
    ],
];
