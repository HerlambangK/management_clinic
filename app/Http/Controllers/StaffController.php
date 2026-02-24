<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = User::where('id', '!=', auth()->id())
            ->latest()
            ->paginate(10);

        return view('staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('staff.create');
    }

    public function store(StaffRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit(User $staff): View
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(StaffRequest $request, User $staff): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $staff->update($data);

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff berhasil dihapus.');
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $newPassword = Str::random(8);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password berhasil direset. Password baru: {$newPassword}");
    }
}
