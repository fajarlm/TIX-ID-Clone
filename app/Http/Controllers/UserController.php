<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
// use Illuminate\Container\Attributes\Auth as lok;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIN('role', ['admin', 'staff'])->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $role = 'staff';

            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|unique:users|email:dns',
                'password' => 'required|min:6',

            ], [
                // Custom pesan validation 
                'name.required' => 'Nama harus diisi',
                'name.min' => 'Nama minimal 3 karakter',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email sudah terdaftar',
                'email.email' => 'email harus diisi dengan data valid',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
            ]);

            $userCreated = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $role
            ]);
        } else {
            $role = 'user';
            $request->validate([
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                'email' => 'required|unique:users|email:dns',
                'password' => 'required|min:6',
            ], [
                'first_name.required' => 'Nama harus diisi',
                'first_name.min' => 'Nama minimal 3 karakter',
                'last_name.required' => 'Nama harus diisi',
                'last_name.min' => 'Nama minimal 3 karakter',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email sudah terdaftar',
                'email.email' => 'email harus diisi dengan data valid',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
            ]);

            $userCreated = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $role
            ]);
        }

        if (Auth::check() && Auth::user()->role === 'admin') {
            if ($userCreated) {
                return redirect()->route('admin.users.index')->with('ok', 'Berhasil membuat akun');
            } else {
                return redirect()->back()->with('error', 'Gagal membuat akun, silahkan coba lagi');
            }
        } else {

            if ($userCreated) {
                //jadi saat user berhasil membuat akun, dia akan diarahkan ke halaman login
                //funsgi with() digunakan untuk mengirimkan pesan ke halaman yang dituju
                //redirect() digunakan untuk mengarahkan ke halaman login
                return redirect()->route('login')->with('ok', 'berhasil membuat akun');
            } else {
                //jika gagal membuat akun, akan diarahkan kembali ke halaman sign up
                //back() digunakan untuk mengarahkan kembali ke halaman sebelumnya
                return redirect()->back()->with('error', 'Gagal membuat akun, silahkan coba lagi');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'min:6|nullable',
        ], [
            'name.required' => 'Nam Bioskop harus diisi',
            'email.required' => 'Lokasi Bioskop harus diisi',
            'email.min' => 'Lokasi Bioskop minimal 10 karakter',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $updateData = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($updateData) {
            return redirect()->route('admin.users.index')->with('success', 'Data berhasil diupdate');
        } else {

            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id)->delete();
        if ($user) {
            return redirect()->route('admin.users.index')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi'
        ]);
        // mengambil data yang akan di cek kecococokannya : email-pw ,username-pw

        $data = $request->only(['email', 'password']);

        // auth -> class authenctication pada laravel yang menyinpam data session yang berhubungan dengan pengguna
        // attemp -> penegecekan data , jika sesuai maka data pengguna akan disimpan pada session auth

        if (Auth::attempt($data)) {
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai admin');
            }elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.promos.index')->with('login', 'Berhasil login sebagai user');
            }else{
                return redirect()->route('home')->with('success', 'Berhasil login');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal login, silahkan coba lagi');
        }
    }

    public function logout()
    {
        //mengahpus sesi login
        Auth::logout();
        return redirect()->route('home')->with('success', 'Berhasil logout');
    }

    public function export() 
    {
        $filename = 'users.xlsx';
        return Excel::download(new UserExport, $filename);
    }
    public function trash(){
        $users = User::onlyTrashed()->get();
        return view('admin.users.trash', compact('users'));
    }
    public function restore($id){
        $user = User::onlyTrashed()->where('id', $id)->restore();
        if ($user) {
            return redirect()->route('admin.users.index')->with('success', 'Data berhasil dikembalikan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }
    public function deletePermanen($id){
        $user = User::onlyTrashed()->where('id', $id)->forceDelete();
        if ($user) {
            return redirect()->route('admin.users.trash')->with('success', 'Data berhasil dihapus permanen');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function dataForDataTable(){
        $users = User::query();
        return DataTables::of($users)
        ->addIndexColumn() //memberikan nomor urut
        // addcolumn : menambahkan data selain dari tables movie, digunkan untuk button aksi data yang perlu di modifikasi

        ->addColumn('button',function($data){
            $btnEdit = '<a href="'. route('admin.users.edit', $data['id']) .'" class="btn btn-warning">edit</a>';
            $btnDelete = '<form action="'.route('admin.users.delete', $data['id']) .'" method="POST">'.
                        csrf_field().
                        method_field('DELETE').'
                        <button class="btn btn-danger" type="submit">delete</button>
                    </form>';
           
            return '<div class="d-flex justify-content-center  align-items-center gap-3">'.$btnEdit.$btnDelete.'</div>
            ';
        })
        ->rawColumns(['button'])
        ->make(true);// mengembalikan data qyery to json
    }
}
