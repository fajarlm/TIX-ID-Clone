@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        <nav class="border rounded p-2 mb-3 shadow-sm">
            <a href="" class="text-body-tertiary">Pengguna /</a>
            <a href="{{ route('admin.users.index') }}" class="text-body-tertiary">data /</a>
            <a href="" class="text-body-tertiary">edit</a>
        </nav>
        <form class="border rounded p-4 shadow-sm" method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @if (Session::get('error'))
                <div class="alert alert-warning">
                    {{ Session('error') }}
                </div>
            @endif
            <h5 class="text center">Edit Data Bioskop</h5>
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Petugas</label>
                <input type="text" name="name" id="name" class="form-control @error('name')is-invaid @enderror"
                    value="{{ $user->name }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="text" name="email" id="email" rows="5"
                    class="form-control @error('email')is-invaid @enderror" value={{ $user->email }}>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" name="password" id="password" rows="5"
                    class="form-control @error('password')is-invaid @enderror">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-success" type="submit">Edit Data</button>
        </form>
    </div>
@endsection
