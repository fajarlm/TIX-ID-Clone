@extends('templates.app')

@section('content')
    <div class="container my-5 card p-5">
        <div class="card-body">
            <h5 class="text-center">Selesaikan Pembayaran</h5>
            <img src="{{ asset('storage/' . $ticket['ticketPayment']['barcode']) }}" alt="" class="d-block mx-auto mb-5">
            <div class="w-50 d-block mx-auto">
                <div class="d-flex justify-content-between">
                    {{-- @php
                        dd($ticket)
                    @endphp --}}
                    <p>{{ $ticket->quantity }} Ticket</p>
                    <p><b>{{ implode(',', $ticket->rows_of_seats) }}</b></p>
                    {{-- <p><b>Rp. {{ number_format($ticket->schedule->price, 0, ',', '.') }} X {{ $ticket->quantity }}</b></p> --}}
                </div>
                <div class="d-flex justify-content-between">
                    <p>Harga</p>
                    <p><b>Rp. {{ number_format($ticket->schedule->price, 0, ',', '.') }} X {{ $ticket->quantity }}</b></p>
                </div>
                <div class="d-flex justify-content-between">
                    <p>Biaya Layanan</p>
                    <p><b>Rp. 4.0000 X {{ $ticket->quantity }}</b></p>
                </div>
                <div class="d-flex justify-content-between">
                    <p>Promo</p>
                    @if ($ticket->promo_id !== null)
                    <p><b>{{ $ticket->promo->type == 'percent' ? $ticket->promo->discount . '%' : 'Rp.' . number_format($ticket->promo->discount,0,',','.')}}</b></p>
                        
                    @else
                        <p><b>-</b></p>
                    @endif
                </div>
                <hr>
                <div class="d-flex justify-content-end mb-2">
                    @php
                    $price = $ticket->total_price + $ticket->service_fee    
                @endphp
                <b>Rp. {{ number_format($price,0,',','.') }}</b>
                </div>
                    <form action="{{ route('tickets.payment.proof',$ticket['id']) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Sudah DiBayar</button>
                    </form>
            </div>
        </div>
    </div>
@endsection
