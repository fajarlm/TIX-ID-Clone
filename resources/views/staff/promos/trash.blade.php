@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h3 class="my-3">Data Sampah : Promo</h3>
        <div class="d-flex justify-content-end">

            <a href="{{ route('staff.promos.index') }}" class="btn btn-primary mb-3">Kembali</a>
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
                    <th scope="col">Promo Code</th>
                    <th scope="col">Diskon</th>
                    <th scope="col">Tipe</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($promos as $key => $promo)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $promo->promo_code }}</td>
                        <td>{{ $promo->discount }}</td>
                        <td>{{ $promo->type }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('staff.promos.restore', $promo->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                            <form action="{{ route('staff.promos.delete-permanent', $promo->id) }}" method="post">
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