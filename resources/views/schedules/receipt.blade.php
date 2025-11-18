@extends('templates.app')
@section('content')
    <div class="my-5 p-4 w-50 mx-auto card d-block">
        <div class="card-body">
            <div class="d-flex-jutify-between mb-4">
                <a href="{{ route('tickets.export-pdf',$ticket->id) }}" class="btn btn-secondary">Unduh(.pdf)</a>
            </div>
            @foreach ($ticket['rows_of_seats'] as $kursi)
                <div class="w-100">
                    <p class="text-center"><b>{{ $ticket->schedule->cinema->name }}</b></p>
                    <hr>
                    <b>{{ $ticket['schedule']['movie']['title'] }}</b>
                    <p>Tanggal : {{ \Carbon\Carbon::parse($ticket['ticketPayment']['booked_date'])->format('d F , Y') }}</p>
                    <p>Waktu :{{ \Carbon\Carbon::parse($ticket['hour'])->format('H:i') }}</p>
                    <p>Kursi :{{ $kursi }}</p>
                    <p>Harga :{{ number_format($ticket->schedule->price, 0, ',', '.') }}</p>

                </div>
            @endforeach
        </div>
    </div>
@endsection
