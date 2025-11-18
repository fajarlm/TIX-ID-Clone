<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //mengaktifkan soft delete
    use SoftDeletes;

    //mendaftarkan column-column selian yag bawaanya , selain id dan timestampts softdeletes,agar dapat di isi datanya ke column tsb
    protected $fillable = ['name', 'location'];
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
}
