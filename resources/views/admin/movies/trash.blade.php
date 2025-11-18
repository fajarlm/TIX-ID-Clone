@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h3 class="my-3">Data Sampah : Film</h3>
        <div class="d-flex justify-content-end">

            <a href="{{ route('admin.movies.index') }}" class="btn btn-primary mb-3">Kembali</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session('success') }}
            </div>            
        @endif
        <table class="table table-bordered  ">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Judul Film</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Sutradara</th>
                    <th scope="col">Durasi</th>
                    <th scope="col">Umur</th>
                    <th scope="col">Poster</th>
                    <th scope="col">aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movies as $key => $movie)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $movie->title }}</td>
                        <td>{{ $movie->genre }}</td>
                        <td>{{ $movie->director }}</td>
                        <td>{{ $movie->duration }}</td>
                        <td>{{ $movie->age_rating }}</td>
                        <td><img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" width="100" height="120"></td>
                        <td class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('admin.movies.restore', $movie->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                            <form action="{{ route('admin.movies.delete-permanent', $movie->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Permanent</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data Kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>  

@endsection
@push('script')
    
@endpush