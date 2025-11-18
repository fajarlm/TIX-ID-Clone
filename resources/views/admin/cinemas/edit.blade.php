@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        @if(Session::get('error'))
            <div class="alert alert-warning">
                {{ Session('error') }}
            </div>
        @endif
        <h5 class="text center">Edit Data Bioskop</h5>
       
        <form class="" method="POST" action="{{ route('admin.cinemas.update', $cinema->id) }}"> 
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop</label>
                <input type="text" name="name" id="name" class="form-control @error('name')is-invaid @enderror" value="{{ $cinema->name }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">lokasi Bioskop</label>
                <textarea type="text" name="location" id="location" rows="5" class="form-control @error('location')is-invaid @enderror" >{{ $cinema->location }}</textarea>
                @error('location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-success" type="submit">Edit Data</button>
        </form>
    </div>
@endsection
