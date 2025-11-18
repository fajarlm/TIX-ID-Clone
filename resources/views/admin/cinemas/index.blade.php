@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session('success') }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ Session('error') }}
            </div>
        @endif

        <div class="d-flex justify-content-end gap-3 mb-3">
            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">Tambah Data</a>
            <a href="{{ route('admin.cinemas.export') }}" class="btn btn-light">Export Data</a>
            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-primary">Trash Data</a>
        </div>
        <table class="table table-responsive table-bordered table-striped" id="cinemaTable">
            <thead>

                <tr>
                    <th>#</th>
                    <th>Nama Bioskop</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@push('script')
    <script>
         $(function() {
            $('#cinemaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.cinemas.dataTables') }}",
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
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'location',
                        name: 'location',
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
    