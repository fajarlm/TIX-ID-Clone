@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto my-5">
        <form action="{{ route('admin.movies.update', $movie['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            @if (Session::get('error'))
                <small class="alert alert-error">{{ Session('error') }}</small>
            @endif
            <div class="row mb-3">
                <div class="col-6">
                    <label for="title" class="form-label">Judul Film</label>
                    <input type="text" name="title" title="title" id="title" class="form-control @error('title')is-invaid @enderror"
                        value="{{ $movie['title'] }}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="duration" class="form-label">Durasi Film</label>
                    <input type="time" name="duration" id="duration"
                        class="form-control @error('duration')is-invaid @enderror" value="{{ $movie['duration'] }}">
                    @error('duration')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">

                    <label for="genre" class="form-label">Genre Film</label>
                    <input type="text" name="genre" id="genre"
                        class="form-control @error('genre')is-invaid @enderror" value="{{ $movie['genre'] }}">
                    @error('genre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="director" class="form-label">Director</label>
                    <input type="text" name="director" id="director"
                        class="form-control @error('director')is-invaid @enderror" value="{{ $movie['director'] }}">
                    @error('director')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_rating" class="form-label">Usia Minimal</label>
                    <input type="number" name="age_rating" id="age_rating"
                        class="form-control @error('age_rating')is-invaid @enderror" value="{{ $movie['age_rating'] }}">
                    @error('age_rating')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror       
                </div>
                <div class="col-6">
                    <label for="poster" class="form-label">Poster Film</label>
                    <img src="{{ asset('storage/' . $movie['poster']) }}" alt="" width="250" class="d-block mx-auto">
                    <input type="file" name="poster" id="poster"
                        class="form-control @error('poster')is-invaid @enderror" >
                    @error('poster')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Sinopsis</label>
                <textarea name="description" id="description" rows="5" class="form-control"
                    @error('description') is-invalid @enderror>{{ $movie['description'] }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Kirim Data</button>
        </form>
    </div>
@endsection
