<?php

namespace App\Domain\Users\Listeners;

use App\Core\CoreEmailTemplate;
use App\Domain\Users\Events\UserCreated;

class SendUserCredential extends CoreEmailTemplate
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\UserCreated $event
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {
        try {
            $subject = 'Your account credentials - CRM';
            $user = $event->user;
            $password = $event->password;
            $toEmail = [$user->email];
            $heading = '';
            $name = $user->name;
            $uiUrl = config('app.ui_url');
            $companyName = 'Innomaint CRM team';
            if ($user->company) {
                $companyName = $user->company->company_name;
            }
            $content = "<p style='font-size:15px;'>Hi {$name},</p>
            <p style='font-size:15px;'>Welcome to CRM innomaint application.
            A new login id and password has been generated. Please find the below credentials.</p>
            <br />
            <p style='font-size:15px;'> Username: {$user->user_name} </p>
            <p style='font-size:15px;'> Password: {$password} </p>
            <p style='font-size:15px;'> <a href={$uiUrl} target='_blank'>Click Here to Login</a> </p>
            <br/>
            <p style='font-size:15px;'> Thanks </p>
            <p style='font-size:15px;'> {$companyName} CRM team </p>
            ";
            // <p style='font-size:15px;'> <a href=$url target='_blank'>Click Here</a> </p>
            $emailTemplate = $this->triggerEmail($heading, $content, $subject, $toEmail, $user->fk_company_id);
        } catch (\Exception $ex) {
            $error_log_message = PHP_EOL.'------------'."\r\n".
            ' Created At: '.createddatetime()."\r\n".
            ' Line No: '.$ex->getLine()."\r\n".
            ' Message: '.(string) $ex."\r\n".
            '------------'.PHP_EOL;
            writeLogChannel('UserCredentialEmailError', $error_log_message);
        }
    }
}
