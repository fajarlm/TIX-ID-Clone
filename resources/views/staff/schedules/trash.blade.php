@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h3 class="my-3">Data Sampah : Jadwal Tayangan</h3>
        <div class="d-flex justify-content-end">

            <a href="{{ route('staff.schedules.index') }}" class="btn btn-primary mb-3">Kembali</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session('success') }}
            </div>            
        @endif
        <table class="table table-bordered  ">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Cinema</th>
                    <th scope="col">Film</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $key=>$schedule)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $schedule->cinema->name ?? 'Kosong wek😜' }}</td>
                        <td>{{ $schedule->movie->title ?? 'Kosong week😜' }}</td>
                        <td>{{ $schedule->price ?? 'Kosong week😜' }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('staff.schedules.restore', $schedule->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                            <form action="{{ route('staff.schedules.delete-permanent', $schedule->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Permanent</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data Kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>  

@endsection
@push('script')
    
@endpush