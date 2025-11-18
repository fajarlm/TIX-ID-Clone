<?php

namespace App\Http\Controllers;

use App\Exports\CinemaExport;
use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //mengambil semua data bioskop
        $cinemas = Cinema::all();

        //mengirim data ke halaman index menggunakan compact
        return view('admin.cinemas.index', compact('cinemas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinemas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nam Bioskop harus diisi',
            'location.required' => 'Lokasi Bioskop harus diisi',
            'location.min' => 'Lokasi Bioskop minimal 10 karakter',
        ]);

        $createData = Cinema::create([
            'name' => $request->name,
            'location' => $request->location
        ]);
        if ($createData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cinema = Cinema::find($id);
        return view('admin.cinemas.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cinema $cinema, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nam Bioskop harus diisi',
            'location.required' => 'Lokasi Bioskop harus diisi',
            'location.min' => 'Lokasi Bioskop minimal 10 karakter',
        ]);

        $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location
        ]);

        if ($updateData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data berhasil diupdate');
        } else {

            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function export()
    {
        $fileName = 'cinemas.xlsx';
        return Excel::download(new CinemaExport, $fileName);
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy($id)
        {
            $cinema = Cinema::find($id);
            $hasSchedule = Schedule::where('cinema_id', $cinema->id)->exists();

            if ($hasSchedule) {
                return redirect()->back()->with('error', 'Cinema ini tidak bisa dihapus karena masih memiliki jadwal!');
            }

            $cinema->delete();
            if (!$cinema) {
                return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
            } else {
                return redirect()->route('admin.cinemas.index')->with('success', 'Data berhasil dihapus');
            }
        }
    public function trash()
    {
        $cinemas = Cinema::onlyTrashed()->get();
        return view('admin.cinemas.trash', compact('cinemas'));
    }
    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->where('id', $id)->restore();
        if ($cinema) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data berhasil dikembalikan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function deletePermanen($id)
    {
        $cinema = Cinema::onlyTrashed()->where('id', $id)->forceDelete();
        if ($cinema) {
            return redirect()->route('admin.cinemas.trash')->with('success', 'Data berhasil dihapus permanen');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }
    public function dataForDataTable(){
        $cinemas = Cinema::query();
        return DataTables::of($cinemas)
        ->addIndexColumn() //memberikan nomor urut
        // addcolumn : menambahkan data selain dari tables movie, digunkan untuk button aksi data yang perlu di modifikasi
        ->addColumn('button',function($data){
            $btnEdit = '<a href="'. route('admin.cinemas.edit', $data['id']) .'" class="btn btn-warning">edit</a>';
            $btnDelete = '<form action="'.route('admin.cinemas.delete', $data['id']) .'" method="POST">'.
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

    public function listCinema(){
        $cinemas = Cinema::whereHas('schedules',function($querySche){
            $querySche->whereHas('movie',function($queryMov){
                $queryMov->where('actived',1);
            });
        })->get();
        return view('schedules.cinema-list',compact('cinemas'));
    }

    public function cinemaSchedule($cinema_id){
        // whereHas('namaRelasi',function($q){...}:argumen 1 (nama relasi)wajib argumen 2 (func untuk filter pada relasi),optional)
        $schedules = Schedule::where('cinema_id',$cinema_id)->with('movie')->whereHas('movie',function($q){
            $q->where('actived',1);
        })->get();
        return view('schedules.cinema-schedule',compact('schedules'));
    }
}
