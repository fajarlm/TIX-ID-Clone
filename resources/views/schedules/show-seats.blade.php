@extends('templates.app')
@section('content')
    <div class="container card my-5 p-4">
        <div class="card-body">
            <div class="card-title">{{ $schedule['cinema']['name'] }}</div>
            <b>{{ \Carbon\Carbon::now()->format('d M,Y') }} || {{ $hour }}</b>
        </div>

        <div class="d-flex justify-content-center">
            <div class="row w-50">
                <div class="col-4">
                    <div class="ms-3 " style="width: 45px;height:45px;background:#112546;"></div>
                    <p>Kursi Kosong</p>
                </div>

                <div class="col-4">
                    <div class="ms-3 bg-secondary" style="width: 45px;height:45px;"></div>
                    <p>Kursi Terjual</p>
                </div>

                <div class="col-4">
                    <div class="ms-3 bg-info" style="width: 45px;height:45px;"></div>
                    <p>Kursi Dipilih</p>
                </div>

            </div>
        </div>

        @php
            $row = range('A', 'H');
            $col = range(1, 18);
        @endphp

        @foreach ($row as $baris)
            <div class="d-flex justify-content-center my-1">
                {{-- looping untuk membuat kursi di satu baris --}}
                @foreach ($col as $kursi)
                    @if ($kursi == 7)
                        <div style="width: 35px"></div>
                    @endif
                    @php
                        $seat = $baris . "-" . $kursi;
                    @endphp

                    @if (in_array($seat, $seatsFormat))
                        <div class="p-2 mx-1 bg-secondary" style=" border-radius:10px;width:45px;height:45px;">
                            <span class="d-flex justify-content-center pt-1"
                                style="font-size: 12px;color:white;">{{ $baris . $kursi }}</span>
                        </div>
                    @else
                        {{-- munculkan A1 dan dst --}}
                        <div onclick="selectedSeat('{{ $schedule->price }}','{{ $baris }}','{{ $kursi }}',this)"
                            class="p-2 mx-1" style="background: #211312; border-radius:10px;width:45px;height:45px;">
                            <span class="d-flex justify-content-center pt-1"
                                style="font-size: 12px;color:white;">{{ $baris . $kursi }}</span>
                        </div>
                        @endif
                    @endforeach
            </div>
        @endforeach

    </div>
    <div class="w-100 p-2 text-center bg-light fixed-bottom" id="wrapBtn">
        <b class="text-center p-3">LAYAR BIOSKOP</b>
        <div class="row" style="border:1px solid #d1d1d1;">
            <div class="col-6 text-center" style="border:1px solid #d1d1d1;">
                <p>Total Harga</p>
                <h5 id="total">Rp.-- </h5>
            </div>
            <div class="col-6 text-center" style="border:1px solid #d1d1d1;">
                <p>Kursi Dipilih</p>
                <h5 id="select"></h5>
            </div>
        </div>
        {{-- menyimpan value yang dierplukan ringaksan pesanan --}}
        <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" name="user_id">
        <input type="hidden" value="{{ $hour }}" id="hour" name="hour">
        <input type="hidden" value="{{ $schedule->id }}" id="schedule_id" name="schedule_id">
        <div class="w-100 text-center" id="btnOrder" style="color:black;font-weight:bold;cursor: pointer;">RINGKASAN
            PESANAN</div>
    </div>
@endsection
@push('script')
    <script>
        let seats = [];
        let totalPriceData = 0;

        function selectedSeat(price, row, col, el) {
            let seatItem = row + "-" + col;
            let indexSeats = seats.indexOf(seatItem);

            if (indexSeats == -1) {
                seats.push(seatItem);
                el.classList.add('bg-info');
            } else {
                seats.splice(indexSeats, 1);
                el.classList.remove('bg-info');
            }

            let selectedSeats = document.getElementById('select');
            let total = document.getElementById('total');

            let totalPrice = price * (seats.length);
            totalPriceData = totalPrice;
            total.innerText = "Rp." + totalPrice;

            selectedSeats.innerText = seats.join(",");

            // jika seats nya lebih dari 1 atau sama aktifkan order dan tambah funsgi onlick data ticket
            let btnOrder = document.getElementById('btnOrder');

            if (indexSeats == -1) {
                btnOrder.style.background = '#112646';
                btnOrder.style.color = 'white';
                btnOrder.classList.add('p-1');
                btnOrder.style.cursor = 'pointer';
                btnOrder.onclick = createTicketData;

            } else {
                btnOrder.style.background = '';
                btnOrder.style.color = 'black';
                btnOrder.classList.remove('p-1');
                btnOrder.style.cursor = '';
                btnOrder.onclick = null;
            }
        }

        function createTicketData() {
            $.ajax({
                // routing untuk akses data 
                url: "{{ route('tickets.store') }}",
                method: "POST",
                data: {
                    // Csrf
                    _token: "{{ csrf_token() }}",
                    user_id: $('#user_id').val(),
                    schedule_id: $('#schedule_id').val(),
                    rows_of_seats: seats,
                    quantity: seats.length,
                    total_price: totalPriceData,
                    hour: $('#hour').val(),
                },
                success: function(res) {
                    // console.log(res)
                    let ticketId = res.data.id
                    window.location.href = `/tickets/${ticketId}/order`;
                },
                error: function(res) {
                    console.log(res.responseText);
                    alert("Terjadi kesalahan saat pembuatan Data Tiket" + res.status);
                },
            })
        }
    </script>
@endpush
