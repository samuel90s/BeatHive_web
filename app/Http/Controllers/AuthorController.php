<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     */
    public function index()
    {
        $authors = User::where('role', User::ROLE_AUTHOR)
                    ->latest()
                    ->paginate(10);

        return view('author.index', compact('authors')); // <= WAJIB
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        return view('author.create');
    }

    /**
     * Store a newly created author (admin-only).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = User::ROLE_AUTHOR;

        User::create($data);

        return redirect()->route('author.index')->with('success', 'Author created successfully.');
    }

    /**
     * Show author details.
     */
    public function show(User $author)
    {
        return view('author.show', compact('author'));
    }

    /**
     * Edit author.
     */
    public function edit(User $author)
    {
        return view('author.edit', compact('author'));
    }

    /**
     * Update author.
     */
    public function update(Request $request, User $author)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $author->id,
            'email'    => 'required|email|max:255|unique:users,email,' . $author->id,
        ]);

        $author->update($data);

        return redirect()->route('author.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Delete author.
     */
    public function destroy(User $author)
    {
        $author->delete();

        return redirect()->route('author.index')->with('success', 'Author deleted successfully.');
    }
}
