<?php





namespace App\Http\Controllers;





use App\Http\Requests\ProfileUpdateRequest;


use Illuminate\Http\RedirectResponse;


use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Redirect;


use Illuminate\View\View;





class ProfileController extends Controller


{


    /**


     * Menampilkan halaman utama profil pengguna.


     */


    public function index(Request $request): View


    {


        return view('profile.index', [


            'user' => $request->user(),


        ]);


    }





    /**


     * Menampilkan formulir untuk mengedit profil.


     */


    public function edit(Request $request): View


    {


        return view('profile.edit', [


            'user' => $request->user(),


        ]);


    }





    /**


     * Memperbarui informasi profil pengguna.


     */


    public function update(ProfileUpdateRequest $request): RedirectResponse


    {


        $user = $request->user();


        $validatedData = $request->validated();





        DB::beginTransaction();


        try {


            // Jika email diubah, reset status verifikasi


            if (isset($validatedData['email']) && $validatedData['email'] !== $user->email) {


                $validatedData['email_verified_at'] = null;


            }





            $user->update($validatedData);





            DB::commit();


            // Arahkan kembali ke halaman index profil setelah berhasil menyimpan


            return Redirect::route('profile.index')->with('status', 'profile-updated');





        } catch (\Throwable $e) {


            DB::rollBack();


            throw $e;


        }


    }





    /**


     * Menghapus akun pengguna secara permanen.


     */


    public function destroy(Request $request): RedirectResponse


    {


        // Validasi kata sandi sebelum menghapus


        $request->validate([


            'password' => ['required', 'current_password'],


        ]);





        $user = $request->user();





        DB::beginTransaction();


        try {


            // Logout pengguna saat ini


            Auth::logout();





            // Hapus pengguna dari database


            $user->delete();


            


            DB::commit();





            // Hancurkan sesi dan buat token baru


            $request->session()->invalidate();


            $request->session()->regenerateToken();





            return Redirect::to('/')->with('status', 'account-deleted');





        } catch (\Throwable $e) {


            DB::rollBack();


            // Jika terjadi error, coba login kembali pengguna jika masih memungkinkan


            Auth::login($user);


            throw $e;


        }


    }


}