<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Mail;

trait SendNotification
{
    /**
	 * Common function to send notification
	 */
    private function sendAllNotification($email = [], $sms = [], $push = [])
    {
        $notificationResponse = [
            'isMailSent' => 0,
            'isSmsSent' => 0,
            'isPushSent' => 0
        ];
        
		// Upload image
		try {
			
            if(!(empty($email)))
            {
                // Call send email function
                $notificationResponse['isMailSent'] = $this->sendEmail($email);
            }

            if(!(empty($sms)))
            {
                // Call send email function
            }

            if(!(empty($push)))
            {
                // Call send email function
            }

		} catch (\Exception $e) {
			//
		}

		return $notificationResponse;
    }

    private function sendEmail($email)
    {
        Mail::send($email['template']['email_template'], $email, function ($message) use ($email) {
            $message->to($email['mail']['to']);
            if(!(empty($email['mail']['cc'])))
            {
                $message->cc($email['mail']['cc']);
            }

            if(!(empty($email['mail']['bcc'])))
            {
                $message->bcc($email['mail']['bcc']);
            }

            $message->subject($email['mail']['subject']);
        });

        if(count(Mail::failures()) > 0)
        {
            return 0;
        }

        return 1;
    }
}
