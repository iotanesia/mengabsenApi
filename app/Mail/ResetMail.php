<?php

namespace App\Mail;

use App\Models\passwordReset as PasswordReset;
use Illuminate\Mail\Mailable;

class ResetMail extends Mailable
{
    public $url, $token;

    public function __construct($url)
    {
        $this->url = $url;
        $parsedUrl = parse_url($this->url);
        $queryParams = [];
        parse_str($parsedUrl['query'], $queryParams);
        $this->token = $queryParams['token'];
    }

    public function build()
    {
        $email = PasswordReset::where('token', $this->token)->first()->email;
        return $this->view('mail.reset', ['email' => $email, 'url' => $this->url])->subject('Permintaan Ubah Kata Sandi')
            ->with([
                'name' => $email,
                'url' => $this->url,
            ]);
    }
}
