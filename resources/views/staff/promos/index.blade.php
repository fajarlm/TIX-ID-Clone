@extends('templates.app')

@section('content')
    <div class="container mt-5 " style="height: 100vh">
        @if (Session::get('login'))
            <div class="alert alert-success">
                {{ Session::get('login') }},
                <b>Selamat Datang {{ Auth::user()->name }}</b>
            </div>
        @endif

        <div class="d-flex justify-content-end gap-3 mb-3">
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success">tambah data</a>
            <a href="{{ route('staff.promos.export') }}" class="btn btn-light">Export Data</a>
            <a href="{{ route('staff.promos.trash') }}" class="btn btn-primary">Trash Data</a>

        </div>
        <table id="promoTable" class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Promo</th>
                    <th>Tipe</th>
                    <th>Total Promo/Diskon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

              
            </tbody>
        </table>

    </div>
@endsection
@push('script')
    <script>
         $(function() {
            $('#promoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.promos.dataTables') }}",
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
                        data: 'promo_code',
                        name: 'promo_code',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actived',
                        name: 'actived',
                        orderable: false,
                        searchable: false
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
