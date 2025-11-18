<?php

namespace App\Http\Controllers;

use App\Exports\ScheduleExport;
use App\Models\Schedule;
use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        $cinemas = Cinema::all();
        $schedules = Schedule::all();
        // $schedules = Schedule::with(['movie','cinema'])->get();
        return view('staff.schedules.index', compact('movies', 'cinemas', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i',
        ], [
            'cinema_id.required' => 'Cinema harus diisi',
            'movie_id.required' => 'Movie harus diisi',
            'price.required' => 'Price harus diisi',
            'price.numeric' => 'Price harus berupa angka',
            'hours.*.required' => 'Hours harus diisi',
            'hours.*.date_format' => 'Hours harus berupa waktu',
        ]);

        //pengecekan terlebih berdasarkan cinema_id dan movie_id
        $hours = Schedule::where('cinema_id', $request->cinema_id)->where('movie_id', $request->movie_id)->value('hours');
        // jika data belm ada hours nyaa kaan null
        // jika data sudah ada, maka akan mengembalikan data hours yang sudah ada
        $hoursBefore = $hours ?? [];
        // gabungkan data hours yang sudah ada dengan data hours yang baru diinput
        $mergeHours = array_merge($hoursBefore, $request->hours);
        // hilangkan jam yang duplikat dari array di db
        $newHours = array_unique($mergeHours);

        // update or create() :jika cinema_id dan move_id sudah ada, maka data akan di update jika belum ada, maka data akan di buat baru

        $createData = Schedule::updateOrCreate([
            'cinema_id' => $request->cinema_id,
            'movie_id' => $request->movie_id,
        ], [
            'price' => $request->price,
            'hours' => $newHours,
        ]);
        if ($createData) {
            return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Jadwal gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i'
        ]   , [
            'price.required' => 'Price harus diisi',
            'price.numeric' => 'Price harus berupa angka',
            'hours.*.required' => 'Hours harus diisi',
            'hours.*.date_format' => 'Hours harus berupa waktu',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => array_unique($request->hours)
        ]);

        if ($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Jadwal berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Jadwal Gagal diTambahkan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        $schedule->delete();
        if ($schedule) {
            return redirect()->back()->with('success', 'Jadwal berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Jadwal gagal dihapus');
        }
    }

    public function export(){
        $filename = 'schedules.xlsx';
        return Excel::download(new ScheduleExport, $filename);
    }
    public function trash()
    {
        // onlytrash memfilter data yang telah di hapous, yang delete at tidak null,hanya untuk memfilter untuk mengambil nya tetap haru menggunaan get atau sebagaiinya
        $schedules = Schedule::onlyTrashed()->with(['movie', 'cinema'])->get();
        return view('staff.schedules.trash', compact('schedules'));
    }
    public function restore($id)
    {
        $schedule = Schedule::withTrashed()->find($id);
        $schedule->restore();
        if ($schedule) {
            return redirect()->route('staff.schedules.index')->with('success', 'Jadwal berhasil dikembalikan');
        } else {
            return redirect()->back()->with('error', 'Jadwal gagal dikembalikan');
        }
    }
    public function deletePermanen($id)
    {
        $schedule = Schedule::withTrashed()->find($id);
        $schedule->forceDelete();
        if ($schedule) {
            return redirect()->route('staff.schedules.trash')->with('success', 'Jadwal berhasil dihapus permanen');
        } else {
            return redirect()->back()->with('error', 'Jadwal gagal dihapus permanen');
        }
    }
    public function dataForDataTable(){
        $schedules = Schedule::with(['cinema', 'movie'])->get();
        return DataTables::of($schedules)
        ->addIndexColumn() //memberikan nomor urut
        // addcolumn : menambahkan data selain dari tables movie, digunkan untuk button aksi data yang perlu di modifikasi
        ->addColumn('cinemaName',function($data){
            return $data->cinema->name;
        })
        ->addColumn('movieName',function($data){
            return $data->movie->title;
        })
        ->addColumn('hours',function($data){
            $content ="<ul>";   
            foreach ($data->hours as $hour) {
                $content .= "
                    <li>$hour</li>
                ";    
            }
            $content .= "</ul>";
            return $content;
            
        })
        ->addColumn('button',function($data){
            $btnEdit = '<a href="'. route('staff.schedules.edit', $data['id']) .'" class="btn btn-warning">edit</a>';
            $btnDelete = '  <form action="'.route('staff.schedules.delete', $data['id']) .'" method="POST">'.
                                csrf_field().
                                method_field('DELETE').'
                                <button class="btn btn-danger" type="submit">delete</button>
                            </form>';

            return '<div class="d-flex justify-content-center  align-items-center gap-3">'.$btnEdit.$btnDelete.'</div>
            ';
        })
        ->rawColumns(['hours','movieName','cinemaName','button'])
        ->make(true);// mengembalikan data qyery to json
    }
}

