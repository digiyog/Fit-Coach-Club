<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Model Instance
     * 
     * @var \App\Models\User
     */
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userName = $this->mailData['name'];
        $userRoleName = ucwords($this->mailData['role_name']);
        $userLoginUsername = $this->mailData['username'];
        $userLoginPassword = $this->mailData['password'];

        if($userRoleName == config('constants.users.roles.PREMIUM_SALON.type'))
        {
            $userRoleName = config('constants.users.roles.PREMIUM_SALON.caption');
        }
        return $this->view('admin-panel.emails.user-create')
        ->with([
            'userName' => $userName,
            'userRoleName' => $userRoleName,
            'userLoginUsername' => $userLoginUsername,
            'userLoginPassword' => $userLoginPassword,
        ]);
    }
}
