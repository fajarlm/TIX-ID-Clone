@extends('templates.app')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-end mt-3 gap-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAdd">Tambah Data</button>
            <a href="{{ route('staff.schedules.export') }}" class="btn btn-light">Export Data(.Excel)</a>
            <a class="btn btn-primary" href="{{ route('staff.schedules.trash') }}">Trash Data</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session('success') }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-error">
                {{ Session('error') }}
            </div>
        @endif
        <h3 class="my-3">Data Jadwal Tayangan</h3>
        <table class="table table-bordered table-striped table-responsive" id="scheduleTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bisokop</th>
                    <th>Judul Film</th>
                    <th>Harga</th>
                    <th>Jam Tayang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        {{-- modal  create --}}
        <div class="modal fade" id="ModalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('staff.schedules.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cinema_id" class="col-form-label">Cinema</label>
                                <select name="cinema_id" id="cinema_i"
                                    class="form-select @error('cinema_id')
                                    is-invalid
                                @enderror">
                                    <option value="" selected hidden>Pilih Bioskop</option>
                                    @foreach ($cinemas as $cinema)
                                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="movie_id" class="col-form-label">Film</label>
                                <select name="movie_id" id="movie_id"
                                    class="form-select @error('movie_id')
                                    is-invalid
                                @enderror">
                                    <option value="" selected hidden>Pilih Film</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class    ="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="col-form-label">Harga:</label>
                                <input type="number" name="price" id="price"
                                    class="form-control
                                    @error('price')
                                    is-invalid
                                @enderror">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{-- <label for="hours" class="col-form-label">Jam Tayang:</label>
                                @if ($errors->has('hours.*'))
                                    <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                                @endif
                                <div id="additionalInput"></div>
                                <input type="time" name="hours" id="hours" class="form-control
                                    <small class='text-danger'>{{ $errors->first('hours.*') }}</small>
                                    @if ($errors->has('hours.*')) @endif>
                                <span class="text-primary my-3" style="cursor: pointer" onclick="addInput()">+ Tambah input
                                    jam</span> --}}
                                <label for=hours class="form-label">Jam Tayang :</label>
                                @if ($errors->has('hours.*'))
                                    {{-- Ambill ket err pada item pertama --}}
                                    <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                                @endif
                                <div class="d-flex gap-2">
                                    <input type="time" name="hours[]" id="hours"
                                        class="form-control @if ($errors->has('hours.*')) {{-- Ambill ket err pada item pertama --}}
                                    <small class='text-danger'>{{ $errors->first('hours.*') }}</small> @endif">
                                    <i class="fa-solid fa-circle-xmark text-danger ms-2 my-1"
                                        style="font-size: 1.5rem;cursor: pointer;"
                                        onclick="this.closest('.hour-item').remove()"></i>


                                </div>
                                <div id="additionalInput"></div>
                                <span class="text-primary my-3" style="cursor: pointer" onclick="addInput()">+ Tambah Input
                                    Jam</span>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        function addInput() {
            //    let content = `<input type="time" name="hours" id="hours" class="form-control mb-3">`;
            let content = `<div class="d-flex align-items center hour-addtional">
                             <input type="time" name="hours[]" id="hours"  class="form-control my-2">
                             <i class="fa-solid fa-circle-xmark text-danger ms-2 my-1" onclick="this.closest('.hour-addtional').remove()" style="font-size: 1.5rem;"></i>
                           </div>`;
            let wrap = document.querySelector('#additionalInput');
            //    wrap.classList.toggle('mb-3');

            wrap.innerHTML += content

        }
    </script>
    {{-- pengkondisian php cek erro , jika terjadi error apapun : $errors->any() --}}
    @if ($errors->any())
        <script>
            //    panggil modal
            let modalAdd = document.getElementById('ModalAdd');
            // mmunculka modal lgai dengan js?
            new bootstrap.Modal(modalAdd).show();
        </script>
    @endif

    <script>
        $(function() {
            $('#scheduleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.schedules.dataTables') }}",
                // menemtukan urutan td
                columns: [
                    // data: namadataUtamaColumn, name: NamaDataUtamaColumn orderable: true/false , searchable: true/false
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cinemaName',
                        name: 'cinemaName',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'movieName',
                        name: 'movieName',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'hours',
                        name: 'hours',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'button',
                        name: 'button',
                        orderable: false,
                        searchable: false
                    },
                    // kalau mau ditambbah aksi order -> orderable true ,kalau dicari urutin pake order
                ]
            });
        })
    </script>

@endpush
