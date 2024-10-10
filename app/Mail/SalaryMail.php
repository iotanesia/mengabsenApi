<?php

namespace App\Mail;

use App\ApiHelper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalaryMail extends Mailable
{
    use Queueable, SerializesModels;

    private $month, $year, $path, $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dateArr, $path, $name)
    {
        $this->month = $dateArr[1];
        $this->year = $dateArr[0];
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.salary', [
            'first' => Carbon::createFromFormat('m', $this->month)->locale('id')->translatedFormat('F'),
            'end' => Carbon::createFromFormat('m', $this->month + 1)->locale('id')->translatedFormat('F'),
            'year' => $this->year
        ])->subject('Slip Gaji Karyawan PT. Iota Cipta Indonesia')
        ->attach(Storage::path($this->path), [
            'as' => $this->name,
            'mime' => 'application/pdf',
        ]);
    }
}
