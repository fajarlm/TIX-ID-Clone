@extends('templates.app')
@push('styles')
@endpush

@section('content')
    <form action="{{ route('staff.schedules.update', $schedule['id']) }}" method="POST" class="container my-5">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="cinema_id" class="form-label">Cinema</label>
            <input id="cinema_id" class="form-control" type="text" name="cinema_id" value="{{ $schedule['cinema']['name'] ?? 'kosong' }}"
                disabled>
           
        </div>
        <div class="mb-3">
            <label for="movie_id" class="form-label">Movie</label>
            <input id="movie_id" class="form-control" type="text" name="movie_id"
                value="{{ $schedule['movie']['title'] }}" disabled>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input id="price" class="form-control @error('price') is-invalid @enderror" type="number" name="price"
                value="{{ $schedule['price'] }}">
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="hours" class="form-label">Jam Tayang</label>
            @foreach ($schedule['hours'] as $index => $hours)
                <div class="d-flex align-items center hour-item ">
                    <input id="hours" class="form-control my-2" type="time" name="hours[]"
                        value="{{ $hours }}">
                    @if ($index > 0)
                        <i class="fa-solid fa-circle-xmark text-danger ms-2 my-3" style="font-size: 1.5rem;cursor: pointer;"
                            onclick="this.closest('.hour-item').remove()"></i>
                    @endif
                </div>
            @endforeach
            <div id="additionalInput"></div>
            <span class="text-primary my-3" style="cursor: pointer" onclick="addInput()">+ Tambah Input Jam</span>
            @if ($errors->has('hours.*'))
                <small class="text-danger">{{ $errors->first('hours.*') }}</small>
            @endif
        </div>
        <button class="btn btn-primary" type="submit">Kirim</button>
    </form>
@endsection

{{-- @push('script')
    <script>
        function addInput() {
            //    let content = `<input type="time" name="hours" id="hours" class="form-control mb-3">`;
            let content = `<div class="d-flex align-items center">
                             <input type="time" name="hours" id="hours"  class="form-control my-2">
                             <i class="fa-solid fa-circle-xmark text-danger ms-2 my-3" style="font-size: 1.5rem;"></i>
                           </div>`;
            let wrap = document.querySelector('#additionalInput');
            // wrap.classList.toggle('mb-3');

            wrap.innerHTML += content;

        }
    </script>
@endpush --}}
@push('script')
    <script>
        function addInput() {
            let wrap = document.querySelector('#additionalInput');
            //    let content = `<input type="time" name="hours" id="hours" class="form-control mb-3">`;
            let content = `<div class="d-flex align-items center hour-addtional">
                             <input type="time" name="hours[]" id="hours"  class="form-control my-2">
                             <i class="fa-solid fa-circle-xmark text-danger ms-2 my-3" onclick="this.closest('.hour-addtional').remove()" style="font-size: 1.5rem;"></i>
                           </div>`;
            // wrap.classList.toggle('mb-3');

            wrap.innerHTML += content;
        }
    </script>
@endpush
