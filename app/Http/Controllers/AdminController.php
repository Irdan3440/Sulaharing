<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ConsultationResult;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalMahasiswa = User::mahasiswa()->count();
        $totalPakar = User::pakar()->count();
        $totalConsultations = ConsultationResult::count();

        // Per-faculty stats
        $fakultasStats = User::mahasiswa()
            ->selectRaw("fakultas, COUNT(*) as total")
            ->whereNotNull('fakultas')
            ->groupBy('fakultas')
            ->pluck('total', 'fakultas');

        // Classification distribution
        $classStats = ConsultationResult::selectRaw("classification, COUNT(*) as total")
            ->groupBy('classification')
            ->pluck('total', 'classification');

        // Recent users
        $recentUsers = User::latest()->take(5)->get();

        return view('pages.admin.dashboard', compact(
            'totalUsers', 'totalMahasiswa', 'totalPakar', 'totalConsultations',
            'fakultasStats', 'classStats', 'recentUsers'
        ));
    }

    // === User management ===
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('fakultas')) $query->where('fakultas', $request->fakultas);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('nim', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }
        $users = $query->latest()->paginate(15);
        return view('pages.admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|max:255', 'email' => 'required|email|unique:users',
            'password' => 'required|min:8', 'role' => 'required|in:mahasiswa,pakar,admin',
            'nim' => 'nullable', 'fakultas' => 'nullable',
        ]);
        $v['password'] = Hash::make($v['password']);
        User::create($v);
        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function userUpdate(Request $request, User $user)
    {
        $v = $request->validate([
            'name' => 'required|max:255', 'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:mahasiswa,pakar,admin', 'nim' => 'nullable', 'fakultas' => 'nullable',
            'is_active' => 'boolean',
        ]);
        if ($request->filled('password')) $v['password'] = Hash::make($request->password);
        $user->update($v);
        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function userDestroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // === Reports ===
    public function reports(Request $request)
    {
        $query = ConsultationResult::with('user', 'disease');
        if ($request->filled('fakultas')) {
            $query->whereHas('user', fn($q) => $q->where('fakultas', $request->fakultas));
        }
        if ($request->filled('classification')) {
            $query->where('classification', $request->classification);
        }
        if ($request->filled('from')) $query->where('consulted_at', '>=', $request->from);
        if ($request->filled('to')) $query->where('consulted_at', '<=', $request->to);

        $consultations = $query->latest('consulted_at')->paginate(20);

        $fakultasList = User::mahasiswa()->whereNotNull('fakultas')->distinct()->pluck('fakultas');

        return view('pages.admin.reports', compact('consultations', 'fakultasList'));
    }
}
