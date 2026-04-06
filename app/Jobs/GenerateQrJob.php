<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateQrJob implements ShouldQueue
{
    public $timeout = 120;
    public $tries = 3;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        // 👉 pindahkan logic QR kamu ke sini
        // contoh:
        // generate QR pakai bacon-qr-code
    }
}