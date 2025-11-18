@extends('templates.app')

@section('content')
<div class="container my-5">

    <div class="card shadow-sm border-0 p-4">
        <div class="card-body">

            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="home-tab" data-bs-toggle="tab" 
                        data-bs-target="#home-tab-pane" type="button" role="tab"> Tiket Aktif</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="profile-tab" data-bs-toggle="tab" 
                        data-bs-target="#profile-tab-pane" type="button" role="tab"> Kadaluarsa</button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="myTabContent">

                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" tabindex="0">

                    <h5 class="mb-4 fw-bold">
                        Tiket Aktif — {{ Auth::user()->name }}
                    </h5>

                    @forelse ($ticketActive as $ticket)
                        @foreach ($ticket['rows_of_seats'] as $kursi)

                            <a href="{{ route('tickets.show',$ticket->id) }}" class="card border-0 shadow-sm mb-4 rounded-4">
                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="fw-bold mb-0">
                                            {{ $ticket['schedule']['cinema']['name'] }}
                                        </h5>
                                        <span class="badge bg-success">Active</span>
                                    </div>

                                    <hr>

                                    <h6 class="fw-semibold text-primary mb-3">
                                        <i class="bi bi-film"></i> 
                                        {{ $ticket['schedule']['movie']['title'] }}
                                    </h6>

                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1">
                                                <strong>Tanggal:</strong><br>
                                                {{ \Carbon\Carbon::parse($ticket['ticketPayment']['booked_date'])->format('d F Y') }}
                                            </p>

                                            <p class="mb-1">
                                                <strong>Waktu:</strong><br>
                                                {{ \Carbon\Carbon::parse($ticket['hour'])->format('H:i') }}
                                            </p>
                                        </div>

                                        <div class="col-6">
                                            <p class="mb-1">
                                                <strong>Kursi:</strong><br>
                                                <span class="badge bg-dark fs-6 px-3 py-2">{{ $kursi }}</span>
                                            </p>

                                            <p class="mb-0">
                                                <strong>Harga:</strong><br>
                                                <span class="text-success">
                                                    Rp {{ number_format($ticket['schedule']['price'], 0, ',', '.') }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </a>

                        @endforeach
                    @empty
                        <p class="text-muted">Belum ada tiket aktif.</p>
                    @endforelse

                </div>


                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" tabindex="0">

                    <h5 class="mb-4 fw-bold">
                        Tiket Kadaluarsa — {{ Auth::user()->name }}
                    </h5>

                    @forelse ($ticketNonActive as $ticket)
                        @foreach ($ticket['rows_of_seats'] as $kursi)

                            <a href="{{ route('tickets.show',$ticket->id) }}" class="card border-0 shadow-sm mb-4 rounded-4 ">
                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="fw-bold text-danger mb-0">
                                            {{ $ticket['schedule']['cinema']['name'] }}
                                        </h5>
                                        <span class="badge bg-danger">Expired</span>
                                    </div>

                                    <hr>

                                    <h6 class="fw-semibold text-secondary mb-3">
                                        <i class="bi bi-film"></i> 
                                        {{ $ticket['schedule']['movie']['title'] }}
                                    </h6>

                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1">
                                                <strong>Tanggal:</strong><br>
                                                {{ \Carbon\Carbon::parse($ticket['ticketPayment']['booked_date'])->format('d F , Y') }}
                                            </p>

                                            <p class="mb-1">
                                                <strong>Waktu:</strong><br>
                                                {{ \Carbon\Carbon::parse($ticket['hour'])->format('H:i') }}
                                            </p>
                                        </div>

                                        <div class="col-6">
                                            <p class="mb-1">
                                                <strong>Kursi:</strong><br>
                                                <span class="badge bg-secondary fs-6 px-3 py-2">{{ $kursi }}</span>
                                            </p>

                                            <p class="mb-0">
                                                <strong>Harga:</strong><br>
                                                Rp {{ number_format($ticket['schedule']['price'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </a>

                        @endforeach
                    @empty
                        <p class="text-muted">Tidak ada tiket kadaluarsa.</p>
                    @endforelse

                </div>

            </div>
        </div>
    </div>

</div>
@endsection
