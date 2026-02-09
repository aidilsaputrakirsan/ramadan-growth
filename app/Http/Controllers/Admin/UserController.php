<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\DailyLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display list of all users for admin management.
     */
    public function index()
    {
        $users = \App\Models\User::query()
            ->withCount(['dailyLogs as perfect_days_count' => function ($q) {
                $q->where('is_perfect_day', true);
            }])
            ->orderBy('name')
            ->get();

        return \Inertia\Inertia::render('Admin/Users', [
            'users' => $users->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'is_admin' => $u->is_admin ?? false,
                'perfect_days_count' => $u->perfect_days_count,
                'created_at' => $u->created_at->format('d M Y'),
            ]),
        ]);
    }

    /**
     * Update user details (name, email).
     */
    public function update(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Reset user password to default.
     */
    public function resetPassword(\App\Models\User $user)
    {
        $user->update([
            'password' => bcrypt('password123'),
        ]);

        return back()->with('success', 'Password reset to "password123".');
    }

    /**
     * Delete a user.
     */
    public function destroy(\App\Models\User $user)
    {
        if ($user->is_admin) {
            return back()->withErrors(['error' => 'Cannot delete admin user.']);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Export all daily logs to Excel.
     */
    public function export()
    {
        return Excel::download(new DailyLogsExport, 'ramadan_growth_logs_' . now()->format('Y-m-d_His') . '.xlsx');
    }
}
