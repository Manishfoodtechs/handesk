<?php

namespace App\Repositories;

use App\Ticket;
use Carbon\Carbon;

class TicketsRepository{

    public function assignedToMe(){
        return auth()->user()->tickets()->where('status','<',Ticket::STATUS_CLOSED);
    }

    public function unassigned(){
        if( auth()->user()->admin )
            return Ticket::whereNull('user_id')->where('status','<',Ticket::STATUS_CLOSED);
        return auth()->user()->teamsTickets()->whereRaw('tickets.user_id is NULL')->where('status','<',Ticket::STATUS_CLOSED);
    }

    public function all(){
        if( auth()->user()->admin ){
            return Ticket::where('status','<',Ticket::STATUS_CLOSED);
        }
        return auth()->user()->teamsTickets()->where('status','<',Ticket::STATUS_CLOSED);
    }

    public function recentlyUpdated(){
        return $this->all()->where('updated_at','>', Carbon::parse("-1 days"));
    }

    public function closed(){
        if( auth()->user()->admin ){
            return Ticket::where('status','=',Ticket::STATUS_CLOSED);
        }
        return auth()->user()->teamsTickets()->where('status','=',Ticket::STATUS_CLOSED);
    }
}