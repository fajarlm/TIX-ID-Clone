@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h3 class="my-3">Data Sampah : Cinema</h3>
        <div class="d-flex justify-content-end">

            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-primary mb-3">Kembali</a>
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
                    <th scope="col">Name</th>
                    <th scope="col">Location</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cinemas as $key => $cinema)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $cinema->name }}</td>        
                        <td>{{ $cinema->location }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('admin.cinemas.restore', $cinema->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                            <form action="{{ route('admin.cinemas.delete-permanent', $cinema->id) }}" method="post">
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