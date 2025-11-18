@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-end gap-3 mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">tambah data</a>
            <a href="{{ route('admin.users.export') }}" class="btn btn-light">Export Data</a>
            <a href="{{ route('admin.users.trash') }}" class="btn btn-primary">Trash Data</a>

        </div>
        <table class="table table-responsive table-bordered table-striped" id="userTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama </th>
                    <th>Email</th>
                    <th>Role</th>
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
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.dataTables') }}",
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
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'role',
                        name: 'role',
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
