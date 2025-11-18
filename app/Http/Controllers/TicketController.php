<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Promo;
use App\Models\Schedule;
use App\Models\Ticket;
use App\Models\TicketPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use function Symfony\Component\Clock\now;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketActive = Ticket::whereHas('ticketPayment',function($q){
            $q->whereDate('booked_date', now()->format('Y-m-d'))->where('paid_date','<>', NULL);
        })->get();
        // dd($ticketActive);
        $ticketNonActive = Ticket::whereHas('ticketPayment',function($q){
            $q->whereDate('booked_date','<',now()->format('Y-m-d'))->whereNotNull('paid_date');
        })->get();

        return view('tickets.index',compact('ticketNonActive','ticketActive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'rows_of_seats' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'hour' => 'required',
        ]);

        $createData = Ticket::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'rows_of_seats' => $request->rows_of_seats,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'actived' => 1,
            'service_fee' => 4000 * $request->quantity,
            'hour' => $request->hour
        ]);

        return response()->json([
            'message' => "berhasil membuat Tiket",
            'data' => $createData
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($ticketId)
    {
        $ticket = Ticket::where('id',$ticketId)->with(['schedule','ticketPayment','schedule.movie','schedule.cinema'])->first();
        return view('schedules.receipt',compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function showSeats($scheduleId, $hourId)
    {
        $schedule = Schedule::where('id', $scheduleId)->with('cinema')->first();
        $hour = $schedule['hours'][$hourId] ?? '';
        $seats = Ticket::whereHas('ticketPayment',function($q){
            $q->whereDate('paid_date',now()->format('Y-m-d'));
        })->whereTime('hour',$hour)->pLuck('rows_of_seats');
        // pLuck mengambil 1 value dan disimpan di array
        // dd($seats);
        $seatsFormat = array_merge(...$seats); 
        // dd($seatsFormat);
        return view('schedules.show-seats', compact('schedule', 'hour','seatsFormat'));
    }

    public function ticketOrder($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule.cinema', 'schedule.movie'])->first();
        // dd($ticket);
        $promos = Promo::where('actived', 1)->get();
        return view('schedules.order', compact('ticket', 'promos'));
    }

    public function createBarcode(Request $request, $ticketId)
    {
        $barcodeKode = 'TICKET-' . $ticketId . rand(1, 10);
        $qrImage = QrCode::format('svg')->size(300)->margin(2)->generate($barcodeKode);
        $fileName = $barcodeKode . '.svg';
        $path = 'barcodes/' . $fileName;

        Storage::disk('public')->put($path, $qrImage);

        $createData = TicketPayment::create([
            'ticket_id' => $ticketId,
            'barcode' => $path,
            'status' => 'process',
            'booked_date' => now()
        ]);

        if ($request->promo_id != Null) {
            $ticket = Ticket::find($ticketId);
            $promo = Promo::find($request->promo_id);
            if ($promo && $promo['type'] == 'percent') {
                $totalPrice = $ticket['total_price'] - ($ticket['total_price'] * $promo['discount'] / 100);
            } else {
                $totalPrice = $ticket['total_price'] - $promo['discount'];
            }
            $ticket->update(['promo_id' => $request->promo_id, 'total_price' => $totalPrice]);
        }
        return response()->json(['massage' => 'berhasil membuat barcode ', 'data' => $createData]);
    }

    public function paymentPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['promo', 'ticketPayment'])->first();
        return view('schedules.payment', compact('ticket'));
    }

    public function proofPayment($ticketId) {
        $updateData = TicketPayment::where('ticket_id',$ticketId)->update([
            'paid_date' => now()
        ]);
        return redirect()->route('tickets.show',$ticketId);
    }

    public function exportPdf($ticketId){
        $ticket = Ticket::where('id',$ticketId)->with(['schedule','ticketPayment','schedule.movie','schedule.cinema'])->first()->toArray();
        view()->share('ticket',$ticket);
        $pdf = Pdf::loadView('schedules.pdf',$ticket);
        $fileName = 'TICKET' . $ticket['id'] . '.pdf';
        return $pdf->download($fileName);
    }

    public function chartData(){
        $month = now()->format('m');
        $tickets = Ticket::whereHas('ticketPayment',function($q)use($month){
            $q->whereMonth('booked_date',$month)->where('paid_date','<>',NULL);
        })->get()->groupBy(function($ticket){
            return \Carbon\Carbon::parse($ticket['ticketPayment']['booked_date'])->format('Y-m-d');
        })->toArray();
        // dd($tickets);
        $flimActived = Movie::where('actived',1)->get();
        $flimNonActived = Movie::where('actived',0)->get();
        // dd($flimActived);

        $label = array_keys($tickets);
        $labelActive = ['Tiket Aktif','Tiket Tidak Aktif'];
        $data = [];
        $dataActive = [$flimNonActived,$flimActived];

        foreach($tickets as $ticket){
           array_push($data, count($ticket));
        }

        return response()->json([
            'label' => $label,
            'data' => $data,
            'dataActive' => $dataActive,
            'labelActive' => $labelActive
        ]);
    }
}
