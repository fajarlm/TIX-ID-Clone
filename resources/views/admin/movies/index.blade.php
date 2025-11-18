@extends('templates.app')
@section('content')
    <div class="container my-5">
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
       
        <div class="d-flex justify-content-between mb-3 gap-3">
            <h5 class="mt-3">Data Film</h5>
            <div class="">
                <a href="{{ route('admin.movies.create') }}" class="btn btn-success ">Tambah Data</a>
                <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary ">Export Data</a>
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-primary">Trash Data</a>

            </div>
        </div>
        <table class="table table-bordered table-responsive table-striped" id="movieTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Poster</th>
                    <th>Judul Film</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
               <tbody>

               </tbody>
        </table>

        {{-- modal --}}
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('#movieTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.movies.dataTables') }}",
                // menemtukan urutan td
                columns: [
                    // data: namadataUtamaColumn, name: NamaDataUtamaColumn orderable: true/false , searchable: true/false
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' ,orderable:false, searchable: false},
                    { data: 'imgPoster', name: 'imgPoster', orderable: false, searchable: false },
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'activedBadge', name: 'activedBadge', orderable: true, searchable: true },
                    { data: 'button', name: 'button' ,orderable: false, searchable: false},
                    // kalau mau ditambbah aksi order -> orderable true ,kalau dicari urutin pake order
                ]
            });
        })
    </script>
    <script>
        function showModal(item) {
            //mengambil image dengan fungsi php
            //mengakses folder public dengan fungsi php asset, digabungkan dengan data yang diterima JS (item)
            let image = `{{ asset('storage/${item.poster}') }}`;
            // backtip {``} : menyimpan string yang berbaris- baris ,ada enternya

            // let age_rating = "";
            // if(item.age_rating <= 13){
            //     age_rating = `<span class="badge badge-info">+${item.age_rating}</span>`
            // }elseif(item.age_rating <= 17){
            //     age_rating = `<span class="badge badge-warning">+${item.age_rating}</span>`
            // }else{
            //     age_rating = `<span class="badge badge-danger">+${item.age_rating}</span>`
            // }

            let content = `
            <img src="${image}" alt="poster ${item.title}" width="100" height="120" class="d-block mx-auto my-2">
            <ul>
                <li>Judul : ${item.title}</li>
                <li>Durasi : ${item.duration}</li>
                <li>Genre : ${item.genre}</li>
                <li>Sutradara : ${item.director}</li>
                <li>Usia Minimal  : ${item.age_rating}</li>
                <li>Sinopsis : ${item.description}</li>
            </ul>
        `;


            let modal = document.getElementById('modalDetailBody');

            modal.innerHTML = content;

            let modaletail = document.querySelector('#modalDetail');

            new bootstrap.Modal(modaletail).show();

            console.log(item);
        }
        // function showModal(item) {
        //     let image = `{{ asset('storage/${item.poster}') }} `;
        //     let content = `
        //         <img src="${image}" width="120" class="d-block mx-auto my-2">
        //         <ul>
        //             <li>Judul : ${item.title} </li>
        //             <li>Durasi : ${item.duration} </li>
        //             <li>Genre : ${item.genre} </li>
        //             <li>Sutradara : ${item.direction} </li>
        //             <li>Usia Minimal : <div class>${item.age_rating}</div> </li>
        //             <li>Sinopsis : ${item.description} </li>
        //         </ul>
        //     `;

        //     let modalDetailBody = document.querySelector("#modalDetailBody");
        //     modalDetailBody.innerHTML = content;

        //     let modalDetail = document.querySelector("#modalDetail");


        //     new bootstrap.Modal(modalDetail).show();
        //     console.log(item);
        // }
    </script>
@endpush
