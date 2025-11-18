@extends('templates.app')
@section('content')
    <div class="container pt-5">
        <div class="w-75 d-block m-auto d-flex gap-5 justify-content-center">
    <div>
        <img src="{{ asset('storage/' . $movie['poster']) }}"
             alt="Film Poster" 
             style="width: 300px; height: 400px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
    </div>
    <div class="">
        <h5>{{ $movie['title'] }}</h5>
        <table>
            <tr>
                <td><b class="text-secondary">Genre</b></td>
                <td class="px-3"></td>
                <td>{{  $movie['genre'] }}</td>
            </tr>
            <tr>
                <td><b class="text-secondary">Durasi</b></td>
                <td class="px-3"></td>
                <td>{{ $movie['duration'] }}</td>
            </tr>
            <tr>
                <td><b class="text-secondary">Sutradara</b></td>
                <td class="px-3"></td>
                <td>{{ $movie['director'] }}</td>
            </tr>
            <tr>
                <td><b class="text-secondary">Rating Usia</b></td>
                <td class="px-3"></td>
                <td><span class="badge badge-success">{{ $movie['age_rating'] }}</span></td>
            </tr>
        </table>
    </div>
</div>
        <div class="w-100 row mt-5">
            <div class="col-6 pe-5">
                <div class="d-flex flex-column justify-content-end align-items-end">
                    <div class="d-flex align-item-center">
                        <h3 class="text-warning me-2">9.2</h3>
                        <i class="fa-solid fa-star text-warning"></i>
                        <i class="fa-solid fa-star text-warning"></i>
                        <i class="fa-solid fa-star text-warning"></i>
                    </div>
                    <small>4.414 Vote</small>
                </div>
            </div>

            <div class="col-6 ps-5" style="border-left: 2px solid rgb(105, 105, 105)  ">
                <div class="d-flex align-item-center">
                    <div class="fas fa-heart text-danger me-2"></div>
                    <b>Masukkan Watchlist</b>
                </div>
                <small>9.000</small>
            </div>
        </div>


        <div class="d-flex w-100 bg-light mt-3">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Bioskop
                </button>
                <ul class="dropdown-menu">
                     @foreach ($listCinema['schedules'] as $schedule )
                        <li><a class="dropdown-item" href="?seacrh-cinema{{ $schedule['cinema']['name'] }}">{{ $schedule['cinema']['name'] }}</a></li>    
                    @endforeach
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Sortir
                </button>
                @php
                    // ambil nila tanda tanya di url requwar()->get('nama-parmas')
                    if(request()->get('sort-price') ){
                        // kalau url ada ?sort price wire
                        // jika nilai di url asc maka tipeny akan diubah menjadi desc
                        if(request()->get('sort-price') == 'ASC'){
                            $typePrice = 'DESC';
                        }else{
                            $typePrice = 'ASC';
                        }
                    }else{
                        $typePrice = 'ASC';
                    }
                    if(request()->get('sort-Alfabet') ){
                        // kalau url ada ?sort Alfabet wire
                        // jika nilai di url asc maka tipeny akan diubah menjadi desc
                        if(request()->get('sort-Alfabet') == 'ASC'){
                            $typeAlfabet = 'DESC';
                        }else{
                            $typeAlfabet = 'ASC';
                        }
                    }else{
                        $typeAlfabet = 'ASC';
                    }
                @endphp
                <ul class="dropdown-menu">
                    {{-- href tanda tanya digunakan untuk ,mengirimkan query param melalui http method get aaau href ,biasaya di gunakan untuk seacr,sort,limit --}}
                    <li><a href="?sort-price={{ $typePrice }}" class="dropdown-item">Harga</a></li>
                    <li><a href="?sort-alfabet={{ $typeAlfabet }}" class="dropdown-item">Alfabet</a></li>
                </ul>
            </div>
        </div>
        <div class="mb-5">
            @foreach ($movie['schedules'] as $schedule )
                
            <div class="w-100 my-3">
                <div class="d-flex justify-content-between">
                <div class="">
                    <i class="fa-solid fa-building"><b>{{ $schedule['cinema']['name'] }}</b></i>
                    <br>
                    <small class="mt-3">{{ $schedule['cinema']['location'] }}</small>
                </div>
                <div class="">
                    <b>Rp. {{ number_format($schedule['price'],'0',',','.') }}</b>
                </div>
               </div>
                <br>
                <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                <div class="d-flex gap-3 ps-3 my-2">
                   @foreach ($schedule['hours'] as $index => $hour )
                   {{-- this buat ngirim html ke js untuk di manipulasi  --}}
                       <span class="btn btn-ouline-secondary" style="cursor: pointer;" onclick="selectedHour('{{ $schedule->id }}','{{ $index }}',this)">{{ $hour }}</span>
                   @endforeach
                    
                </div>
            </div>
            <hr>
            @endforeach
        </div>
        
    </div>
    {{-- style="background: rgba(120, 120, 248, 0.425)" --}}
    <div class="w-100 p-2 text-center bg-light"  id="wrapBtn">
        {{-- disable button = kalau di  a href (javascript:void(0)) --}}
        <a href="javascript:void(0)" id="btnTicket"><i class="fa-solid fa-ticket" ></i> BELI TIKET</a>
    </div>

@endsection
@push('script')
    <script>
        let elementBefore = null;
        function selectedHour(scheduleId,hourId,el){

            if(elementBefore){
                elementBefore.style.background ="";
                elementBefore.style.color ="";
                elementBefore.style.borderColor=""
            }
            // mmemberi warna baru ke element
            el.style.background = "#112546";
            el.style.color = "white";
            el.style.borderColor = "#112546";
            // update element sebelumnya pake eleemnt baru 
            elementBefore = el;
            
            let wrapBtn = document.querySelector("#wrapBtn");
            let btnTicket = document.querySelector("#btnTicket");
            
            wrapBtn.classList.remove("bg-light");
            // wrapBtn.classlist.add("bg-primary");
            // wrapBtn.style.background = "";
            wrapBtn.style.background = "#112546";
            
            let url = "{{ route('schedule.show_seats',['scheduleId' => ':scheduleId','hourId'=>':hourId']) }}".replace(':scheduleId', scheduleId).replace(':hourId', hourId);

            btnTicket.href = url;
            btnTicket.style.color = "white";

        }

    </script>
@endpush