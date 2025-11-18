@extends('templates.app')
@section('content')
<div class="container p-4 card my-5">
    <div class="card-body">
        <h4 class="text-center mb-4">Ringkasan Order</h4>
        <div class="d-flex gap-3">
            <div class="">
                <img src="{{ asset('storage/'.$ticket['schedule']['movie']['poster']) }}" width="250" height="250" alt="">
            </div>
            <table>
                <tr>
                    <td class="text-secondary "><b>Genre :</b></td>
                    <td>{{ $ticket['schedule']['movie']['genre'] }}</td>
                </tr>
                <tr>
                    <td class="text-secondary "><b>Durasi :</b></td>
                    <td>{{ $ticket['schedule']['movie']['duration'] }}</td>
                </tr>
                <tr>
                    <td class="text-secondary "><b>Sutradara :</b></td>
                    <td>{{ $ticket['schedule']['movie']['director'] }}</td>
                </tr>
                <tr>
                    <td class="text-secondary "><b>Rating Usia :</b></td>
                    <td class="badge badge-danger">{{ $ticket['schedule']['movie']['age_rating'] }}</td>
                </tr>
            </table>
        </div>
        <h4 class="text-secondary my-3">Nomor Pesanan : {{ $ticket['id'] }}</h4>
        <hr>
        <b>Detail Transaksi</b>
        <table class="mt-2">
            <tr>
                <td>Tiket</td>
                <td style="padding: 0 30px"></td>
                <td><b>{{ implode(',' , $ticket->rows_of_seats)}}</span></b> </td>
            </tr>
            <tr>
                <td>Kursi Regular</td>
                <td style="padding: 0 30px"></td>
                <td><b>{{ $ticket['schedule']['price'] }} <span class="text-secondary">{{ $ticket['quantity'] }}</span></b> </td>
            </tr>
        </table>
        <hr>
        <p>Pilih Promo</p>
        <select name="promo_id" id="promo_id" class="form-select">
            <option value="" selected disabled hidden>pilih</option>
            @foreach ($promos as $promo )
                <option value="{{ $promo->id }}">{{ $promo['promo_code'] }} - {{ $promo['type'] == 'percent' ? $promo['discount']."%" : number_format($promo['discount'],0,',','.') }}</option>
            @endforeach
        </select>
        <div class="mt-3 rounded-4 text-white w-100 text-center p-3" style="cursor: pointer;background:#112464" onclick="createBarcode('{{ $ticket['id'] }}')">Bayar Sekarang</div>
    </div>
</div>
@endsection

@push('script')
    <script>
        function createBarcode(ticketId){
            let promo = $('#promo_id').val();
            $.ajax({
                url : `/tickets/${ticketId}/barcode`,
                method : "POST",
                data : {
                    _token : "{{ csrf_token() }}",
                    ticket_id : ticketId,
                    promo_id : promo,
                },
                success: function(res){
                    window.location.href = `/tickets/${ticketId}/payment`;
                },
                error : function(res){
                    console.log(res);
                    alert("error saat membuat barcode");
                }
            });
        }
    </script>
@endpush