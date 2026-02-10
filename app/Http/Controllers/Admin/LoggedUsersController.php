<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoggedUsersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = User::query();
        
        // Exclude administrators - only show regular users
        $query->whereDoesntHave('roles', function($q) {
            $q->where('name', 'administrator');
        });
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Get total count of regular users only (excluding admins)
        $totalUsers = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'administrator');
        })->count();
        
        // Paginate results
        $users = $query->latest()->paginate(10);
        
        return view('pages.apps.logged-users.index', compact('users', 'totalUsers', 'search'));
    }
}
