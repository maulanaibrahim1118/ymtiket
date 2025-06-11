<?php

namespace App\Jobs;

use App\Helpers\FonnteHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFonnteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $target;
    protected $message;

    public function __construct($target, $message)
    {
        $this->target = $target;
        $this->message = $message;
    }

    public function handle()
    {
        FonnteHelper::send($this->target, $this->message);
    }
}