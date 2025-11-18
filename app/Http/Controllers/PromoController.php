<?php

namespace App\Http\Controllers;

use App\Exports\PromoExport;
use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->discount > 100) {
            return redirect()->back()->with('error', 'Diskon tidak boleh lebih dari 100%');
        }elseif ($request->discount < 0) {
            return redirect()->back()->with('error', 'Diskon tidak boleh kurang dari 0%');
        }
        $request->validate([
            'promo_code' => 'required|numeric|min:3',
            'discount' => 'required|numeric',
            'type' => 'required',

        ], [
            'promo_code.required' => 'Kode Promo harus diisi',
            'promo_code.numeric' => 'Kode Promo harus berupa angka',
            'promo_code.min' => 'Kode Promo minimal 3 karakter',
            'discount.required' => 'Diskon harus diisi',
            'discount.numeric' => 'Diskon harus berupa angka',
            'type.required' => 'Tipe harus diisi',
        ]);

        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'actived' => 1,
        ]);
        if ($createData) {
            return redirect()->route('staff.promos.index')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo, $id)
    {
        $promo = Promo::find($id);
        return view('staff.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo,$id)
    {
        $request->validate([
            'promo_code' => 'required|numeric|min:3',
            'discount' => 'required|numeric',
            'type' => 'required',

        ], [
            'promo_code.required' => 'Kode Promo harus diisi',
            'promo_code.numeric' => 'Kode Promo harus berupa angka',
            'promo_code.min' => 'Kode Promo minimal 3 karakter',
            'discount.required' => 'Diskon harus diisi',
            'discount.numeric' => 'Diskon harus berupa angka',
            'type.required' => 'Tipe harus diisi',
        ]);

        $updateData = Promo::where('id', $id)->update([
           'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'actived' => 1,
        ]);

        if ($updateData) {
            return redirect()->route('staff.promos.index')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function patch($id)
    {
        $promo = Promo::find($id);
        $promo['actived'] = 0 ;
        $promo->save();
        if ($promo) {
            return redirect()->route('staff.promos.index')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function destroy($id)
    {
        $promo = Promo::find($id)->delete();
        if ($promo) {
            return redirect()->route('staff.promos.index')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function export(){
        $fileName = 'promo.xlsx';
        return Excel::download(new PromoExport, $fileName);
    }

    public function trash(){
        $promos = Promo::onlyTrashed()->get();
        return view('staff.promos.trash', compact('promos'));
    }

    public function restore($id){
        $promo = Promo::onlyTrashed()->where('id', $id)->restore();
        if ($promo) {
            return redirect()->route('staff.promos.index')->with('success', 'Data berhasil dikembalikan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function deletePermanen($id){
        $promo = Promo::onlyTrashed()->where('id', $id)->forceDelete();
        if ($promo) {
            return redirect()->route('staff.promos.trash')->with('success', 'Data berhasil dihapus permanen');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function dataForDataTable(){
        $promos = Promo::query();
        return DataTables::of($promos)
        ->addIndexColumn() //memberikan nomor urut
        // addcolumn : menambahkan data selain dari tables movie, digunkan untuk button aksi data yang perlu di modifikasi

        ->addColumn('activedBadge',function($data){
            if ($data->actived == 1) {
                return '<span class="badge badge-success">Aktif</span>';
            }
        })
        ->addColumn('actived',function($data){
            if ($data->actived == 1) {
                return '<span class="badge badge-success">Aktif</span>';
            }else{
                return '<span class="badge badge-danger">Tidak Aktif</span>';
            }
        })

        ->addColumn('button',function($data){
            $btnEdit = '<a href="'. route('staff.promos.edit', $data['id']) .'" class="btn btn-warning">edit</a>';
            $btnDelete = '<form action="'.route('staff.promos.delete', $data['id']) .'" method="POST">'.
                        csrf_field().
                        method_field('DELETE').'
                        <button class="btn btn-danger" type="submit">delete</button>
                    </form>';
            $btnNonAktif = '';
            if ($data['actived'] == 1) {
                $btnNonAktif = '<form action="'.route('staff.promos.patch', $data['id']) .'" method="POST">'.
                        csrf_field().
                        method_field('PATCH').'
                        <button class="btn btn-light" type="submit">Non Aktif</button>
                    </form>';
            }
            return '<div class="d-flex justify-content-center  align-items-center gap-3">'.$btnEdit.$btnDelete.$btnNonAktif.'</div>
            ';
        })
        ->rawColumns(['activedBadge','button','actived'])
        ->make(true);// mengembalikan data qyery to json
    }

}
