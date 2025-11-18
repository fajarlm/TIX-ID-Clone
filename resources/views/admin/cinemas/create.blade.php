@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        @if(Session::get('error'))
            <div class="alert alert-error">
                {{ Session('error') }}
            </div>
        @endif
        <h5 class="text center">Tambah Data Bioskop</h5>
       
        <form class="" method="POST" action="{{ route('admin.cinemas.store') }}"> 
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop</label>
                <input type="text" name="name" id="name" class="form-control @error('name')is-invaid @enderror" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi Bioskop</label>
                <textarea type="text" name="location" id="location" rows="5" class="form-control @error('location')is-invaid @enderror" >{{ old('location') }}</textarea>
                @error('location')  
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Kirim Data</button>
        </form>
    </div>
@endsection
