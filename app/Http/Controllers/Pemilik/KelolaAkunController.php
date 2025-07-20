<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User di-import dengan benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rule; // Import Rule untuk validasi unique
use Exception; // Import Exception class
use Illuminate\Support\Facades\Log; // Import Log facade

class KelolaAkunController extends Controller
{
    /**
     * Menampilkan daftar akun pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('username', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
        }

        $users = $query->paginate(10); // Mengambil semua user dengan paginasi

        return view('pemilik.kelola_akun.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat akun baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pemilik.kelola_akun.create');
    }

    /**
     * Menyimpan akun baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pemilik',
            'member_token' => 'nullable|string|max:255',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'member_token' => $request->member_token,
        ]);

        return redirect()->route('pemilik.kelola-akun.index')->with('success', 'Akun berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit akun pengguna.
     *
     * @param  \App\Models\User  $user // Pastikan parameter ini bernama $user
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $user) // Route Model Binding akan otomatis menemukan user berdasarkan ID
    {
        // $user = User::findOrFail($user);
        // Jika Route Model Binding gagal menemukan user (misal: ID tidak ada),
        // Laravel secara otomatis akan melempar 404.
        // Jadi, tidak perlu pengecekan if ($user) di sini.
        return view('pemilik.kelola_akun.edit', compact('user'));
    }

    /**
     * Memperbarui akun pengguna di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user // Pastikan parameter ini bernama $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Abaikan ID user saat ini
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Abaikan ID user saat ini
            ],
            'role' => 'required|in:admin,pemilik',
            'member_token' => 'nullable|string|max:255',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'member_token' => $request->member_token,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('pemilik.kelola-akun.index')->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Menghapus akun pengguna dari database.
     *
     * @param  \App\Models\User  $user // Pastikan parameter ini bernama $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            // Cek apakah user yang akan dihapus adalah user yang sedang login
            if (auth()->user()->id === $user->id) {
                return redirect()->route('pemilik.kelola-akun.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            }

            $user->delete();
            // Redirect dengan pesan sukses
            return redirect()->route('pemilik.kelola-akun.index')->with('success', 'Akun berhasil dihapus!');
        } catch (Exception $e) {
            // Tangani error jika penghapusan gagal
            // Log error ini untuk debugging lebih lanjut
            Log::error('Error deleting user: ' . $e->getMessage(), ['user_id' => $user->id]);

            // Periksa jika error disebabkan oleh Foreign Key Constraint
            if (str_contains($e->getMessage(), 'Cannot delete or update a parent row: a foreign key constraint fails')) {
                return redirect()->route('pemilik.kelola-akun.index')->with('error', 'Gagal menghapus akun: Akun ini memiliki data terkait di sistem (misal: stok masuk, stok keluar). Hapus data terkait terlebih dahulu.');
            }

            return redirect()->route('pemilik.kelola-akun.index')->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}
