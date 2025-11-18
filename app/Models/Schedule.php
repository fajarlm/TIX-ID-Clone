<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cinema_id',
        'movie_id',
        'hours',    
        'price'
    ];

    protected function casts():array{
        return [
            // aggar data nya bertipe array
            'hours' => 'array'
        ];
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
