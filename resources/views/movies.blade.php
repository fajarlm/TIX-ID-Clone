@extends('templates.app')
@section('content')
    <div class="container my-5">
        <h3 class="mb-3">Seluruh Film Sedang Tayang</h3>
        <form action="" method="get">
            <div class="row">
                <div class="col-10">
                    <input type="text" name="search_movie" id="" placeholder="Cari Judul Flim" class="form-control">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-center gap-4 my-3 flex-wrap ">
            @foreach ($movies as $key => $movie)
                <div class="card" style="width: 15rem; margin:5px">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" class="card-img-top"
                        style="min-height: 310px; object-fit: cover;" alt="$movie['title']">
                    <div class="card-body" style="padding: 0 !important">
                        <h2 class="card-title">{{ $movie['title'] }}</h2>
                        <p class="card-text text-center bg-primary py-2"><a
                                href="{{ route('schedule.detail', $movie['id']) }}" class="text-warning">Beli Tiket</a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
