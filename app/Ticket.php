<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->belongsTo('App\Asset');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function agent()
    {
        return $this->belongsTo('App\Agent');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    public function progress_ticket()
    {
        return $this->hasMany('App\Progress_ticket');
    }

    public function ticket_approval()
    {
        return $this->hasOne('App\Ticket_approval');
    }

    public function ticket_detail()
    {
        return $this->hasOne('App\Ticket_detail');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->no_ticket = static::generateTicketNumber();
        });
    }

    protected static function generateTicketNumber()
    {
        $month = date('m');
        $year  = date('Y');

        // Hitung jumlah ticket pada bulan & tahun ini
        $countThisPeriod = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // Nomor berurutan
        $sequence = str_pad($countThisPeriod + 1, 4, '0', STR_PAD_LEFT);

        return 'T' . date('my') . $sequence;
    }
}