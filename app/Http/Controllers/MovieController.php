<?php

namespace App\Http\Controllers;

use App\Exports\MovieExport;
use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required|numeric',
            'poster' => 'required|image|mimes:svg,jpg,jpeg,png,webp',
            'description' => 'required',
        ], [
            'title.required' => 'Judul harus diisi',
            'duration.required' => 'Durasi harus diisi',
            'genre.required' => 'Genre harus diisi',
            'director.required' => 'Sutradara harus diisi',
            'age_rating.required' => 'Rating harus diisi',
            'poster.required' => 'Poster harus diisi',
            'description.required' => 'Deskripsi harus diisi',
        ]);
        //$request->file('poster') untuk mengmbilkan gambar dari request
        $gambar = $request->file('poster');
        //buat nama baru ,nama aca untuk membedakan tiap file, akan menjadi : sjahdk-poster.jpg
        //$gambar->getClientOriginalExtension() untuk mendapatkan ekstensi gambar
        $namaGambar = Str::random(5) . "-Poster." . $gambar->getClientOriginalExtension();
        //storeAs untuk menyimpan gambar. forma storeAs(lokasi penyimpanan, nama gambar,visibily)
        //hasil berupa path dan gambar akan disimpan di folder /poster dan menambahkan /poster di depan
        $path = $gambar->storeAs("/poster", $namaGambar, "public");


        $movie = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1,
        ]);

        if ($movie) {
            return redirect()->route('admin.movies.index')->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movies.edit', compact('movie'));
    }

    public function patch($id)
    {
        $movie = Movie::find($id);

        // if ($movie['actived'] == 1) {
        //     $movie->update(['actived' => 0]);
        // } 
        $movie['actived'] = 0;
        $movie->save();
        if (!$movie) {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
        return redirect()->route('admin.movies.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required||numeric',
            'poster' => 'image|mimes:svg,jpg,jpeg,png,webp',
            'description' => 'required',
        ], [
            'title.required' => 'Judul harus diisi',
            'duration.required' => 'Durasi harus diisi',
            'genre.required' => 'Genre harus diisi',
            'director.required' => 'Sutradara harus diisi',
            'age_rating.required' => 'Rating harus diisi',
            'poster.mimes' => 'Poster harus bertipe svg,jpg,jpeg,png,webp',
            'description.required' => 'Deskripsi harus diisi',
        ]);

        $movie = Movie::find($id);
        if ($request->file('poster')) {
            $fileSebelumnya = storage_path('app/public/' . $movie->poster);
            //megecek apakah file ada atau tidak
            if (file_exists($fileSebelumnya)) {
                //unlink () ; hapus
                unlink($fileSebelumnya);
            }
            $gambar = $request->file('poster');
            //buat nama baru ,nama aca untuk membedakan tiap file, akan menjadi : sjahdk-poster.jpg
            //$gambar->getClientOriginalExtension() untuk mendapatkan ekstensi gambar
            $namaGambar = Str::random(5) . "-Poster." . $gambar->getClientOriginalExtension();
            //storeAs untuk menyimpan gambar. forma storeAs(lokasi penyimpanan, nama gambar,visibily)
            //hasil berupa path dan gambar akan disimpan di folder /poster dan menambahkan /poster di depan
            $path = $gambar->storeAs("/poster", $namaGambar, "public");
        }



        $updateMovie = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path ?? $movie['poster'],
            'description' => $request->description,
        ]);

        if ($updateMovie) {
            return redirect()->route('admin.movies.index')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $movie = Movie::find($id);
        $hasSchedule = Schedule::where('movie_id', $movie->id)->exists();

        if ($hasSchedule) {
            return redirect()->back()->with('error', 'movie ini tidak bisa dihapus karena masih memiliki jadwal!');
        }

        $movie->delete();
        if (!$movie) {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        } else {

            return redirect()->route('admin.movies.index')->with('success', 'Data berhasil dihapus');
        }
    }

    public function home()
    {
        //dalam where bisa menggunakan operator =, !=, >, <, >=, <=
        //asc untuk mengurutkan dari kecil ke besar
        //desc untuk mengurutkan dari besar ke kecil
        //get untuk mengambil data banyak
        //first untuk mengambil data pertama
        //limit untuk membatasi jumlah data

        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->limit(4)->get();

        return view('home', compact('movies'));
    }

    public function homeAllMovie(Request $request)
    {
        $title = $request->search_movie;
        if ($title != "") {
            // operator LIke :untuk mencari data yang miripp/mengadung kata tertnetu 
            // % digunakan untuk mengaktifkan LIke 
            // $kata : mencari kata belakang
            // kata% : mencari kata depan 
            // %kata% : mencari kata di depan,tengah,belaknag
            $movies = Movie::where('title', 'like', "%$title%")->where('actived', 1)->orderBy('created_at', 'DESC')->get();
        } else {
            $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();
        }
        return view('movies', compact('movies'));
    }

    public function export()
    {
        $fileName = 'movies.xlsx';
        return Excel::download(new MovieExport, $fileName);
    }

    // public function movieSchedule($movie_id)
    // {           
    //     // $schedules = Schedule::all() ;
    //     // $cinemas = Cinema::all();
    //     $movie = Movie::where('id',$movie_id)->with(['schedules','schedules.cinema'])->first();
    //     dd($movie); 
    //     return view('schedules.detail-flim', compact('movie'));
    // }
    public function movieSchedule($movie_id, Request $request)
    {
        // request :mengambil data request
        $sortPrice = $request['sort-price'];
        if ($sortPrice) {
            //karena mau mengurutkan berdasarkan price yang ada di schedule, maka sorting ( orderBy ) di simpan di relasi wirh schedules
            $movie = Movie::where('id', $movie_id)->with(['schedules' => function ($query) use ($sortPrice) {
                // query mewakili modoel schdule
                // 'schdule' => function ($query) {...} : melakukan filter/menjalankan elquent model didalam relasi
                $query->orderBy('price', $sortPrice);
            }, 'schedules.cinema'])->first();
        } else {
            // mengambil relasi didalam relasi
            // realsi cinmea ada di schdule -> scheduels.cinema
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        }

        $sortAlfabet = $request['sort-Alfabet'];
        if ($sortAlfabet == 'ASC') {
            // ambil colection, collection : hasil dari get_first_all
            // movie->schedule ,menagacu ke data relasi schdules
            // sortby:mengumpukan colletion (ASC), orderBY:mengutukan query param
          
            $movie->schedules = $movie->schedules->sortBy(function ($schedule){
                return $schedule->cinema->name;
            })->values();
        }else {
            $movie->schedules = $movie->schedules->sortByDesc(function ($schedule){
                return $schedule->cinema->name;
            })->values();
        }
            
        $searchCinema = $request['search-cinema'];
        if ($searchCinema) {
            $movie = $movie->Schedule->where('cinema_id', $searchCinema)->values();
        }


        $listCinema = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        //mengambil relasi dalam relasi
        //relaso cinema ada di schedule -> schedules.cinema (.)
        // $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        // dd($movie);
        //first() : Karena 1 data film, diambil satu
        return view('schedules.detail-film', compact('movie','listCinema'));
    }
    public function trash()
    {
        $movies = Movie::onlyTrashed()->get();
        return view('admin.movies.trash', compact('movies'));
    }
    public function restore($id)
    {
        $movie = Movie::withTrashed()->find($id)->restore();
        if ($movie) {
            return redirect()->route('admin.movies.index')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }
    public function deletePermanen($id)
    {
        $movie = Movie::withTrashed()->find($id)->forceDelete();

        if ($movie) {
            return redirect()->route('admin.movies.trash')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }

    public function forceDelete($id)
    {
        $movie = Movie::withTrashed()->find($id)->forceDelete();
        if ($movie) {
            return redirect()->route('admin.movies.trash')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Gagal! , silahkan coba lagi');
        }
    }
    public function dataForDataTable(){
        $movies = Movie::query();
        return DataTables::of($movies)
        ->addIndexColumn() //memberikan nomor urut
        // addcolumn : menambahkan data selain dari tables movie, digunkan untuk button aksi data yang perlu di modifikasi
        ->addColumn('imgPoster',function($data){
            $urlImage = asset('storage')."/".$data->poster;
            // menambahkan data baru bernaa imgposter dengan hasil tah imh yang link na sudah tersambung ke storage "" untuk kontent ke storgae
            return '<img src="'.$urlImage.'" width="100px" height="100px">';
        })

        ->addColumn('activedBadge',function($data){

            if ($data->actived == 1) {
                return '<span class="badge badge-success">Aktif</span>';
            }else{
                return '<span class="badge badge-danger">Tidak Aktif</span>';
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
            $btnDetail = '<button type="button" class="btn btn-info" onclick=\'showModal('.json_encode($data).' )\'>detail</button>';
            $btnEdit = '<a href="'. route('admin.movies.edit', $data['id']) .'" class="btn btn-warning">edit</a>';
            $btnDelete = '<form action="'.route('admin.movies.delete', $data['id']) .'" method="POST">'.
                        csrf_field().
                        method_field('DELETE').'
                        <button class="btn btn-danger" type="submit">delete</button>
                    </form>';
            $btnNonAktif = '';
            if ($data['actived'] == 1) {
                $btnNonAktif = '<form action="'.route('admin.movies.patch', $data['id']) .'" method="POST">'.
                        csrf_field().
                        method_field('PATCH').'
                        <button class="btn btn-light" type="submit">Non Aktif</button>
                    </form>';
            }
            return '<div class="d-flex justify-content-center  align-items-center gap-3">'.$btnDetail.$btnEdit.$btnDelete.$btnNonAktif.'</div>
            ';
        })
        ->rawColumns(['imgPoster','activedBadge','button','actived'])
        ->make(true);// mengembalikan data qyery to json
    }

    public function chart(){
        $filmActive = Movie::where('actived', 1)->count();
        $filmNonActive = Movie::where('actived', 0)->count();

        $label = ['Film Aktif','Film Tidak Aktif'];
        $data = [$filmActive,$filmNonActive];

         return response()->json([
            'label' => $label,
            'data' => $data
        ]);

    }
}
