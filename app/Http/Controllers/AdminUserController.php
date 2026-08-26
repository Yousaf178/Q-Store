<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all registered users.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $role = $request->role;

        $users = User::withCount('orders')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($role && in_array($role, ['user', 'admin']), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $regularUserCount = User::where('role', 'user')->count();

        return view('admin.users.index', compact('users', 'search', 'role', 'totalUsers', 'adminCount', 'regularUserCount'));
    }
}
