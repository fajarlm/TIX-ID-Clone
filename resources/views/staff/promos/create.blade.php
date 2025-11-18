@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        @if (Session::get('error'))
            <div class="alert alert-error">
                {{ Session('error') }}
            </div>
        @endif
        <h5 class="text center">Tambah Promo</h5>

        <form class="" method="POST" action="{{ route('staff.promos.store') }}">
            @csrf
            <div class="mb-3">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="number" name="promo_code" id="promo_code"
                    class="form-control @error('promo_code')is-invaid @enderror" value="{{ old('promo_code') }}">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <select name="type" class="form-select" aria-label="Default select example">
                    <option selected hidden>PILIH</option>
                    <option value="percentage"> %</option>
                    <option value="rupiah">rupiah</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" name="discount" id="discount"
                    class="form-control @error('discount')is-invaid @enderror" value="{{ old('discount') }}">
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Kirim Data</button>
        </form>
    </div>
@endsection
