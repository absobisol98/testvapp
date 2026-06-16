<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Visualbuilder\EmailTemplates\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run() {
        $emailTemplates = [
            [
                'key'       => 'user-welcome',
                'from'      =>  ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'User Welcome Email',
                'title'     => 'Welcome to ##config.app.name##',
                'subject'   => 'Welcome to ##config.app.name##',
                'preheader' => 'Lets get you started',
                'content'   => "<p>Dear ##user.name##,</p>
                                <p>Thanks for registering with ##config.app.name##.</p>
                                <p>If you need any assistance please contact our customer services team ##config.email-templates.customer-services.email## who will be happy to help.</p>
                                <p>Kind Regards<br>
                                ##config.app.name##</p>"
            ],
            [
                'key'       => 'user-request-reset',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'User Request Password Reset',
                'title'     => 'Reset your password',
                'subject'   => '##config.app.name## Password Reset',
                'preheader' => 'Reset Password',
                'content'   => "<p>Hello ##user.name##,</p>
                                <p>You are receiving this email because we received a password reset request for your account.</p>
                                <div>{{button url='##tokenUrl##' title='Change My Password'}}</div>
                                <p>If you didn't request this password reset, no further action is needed. However if this has happened more than once in a short space of time, please let us know.</p>
                                <p>We'll never ask for your credentials over the phone or by email and you should never share your credentials</p>
                                <p>If you’re having trouble clicking the 'Change My Password' button, copy and paste the URL below into your web browser:</p>
                                <p><a href='##tokenUrl##'>##tokenUrl##</a></p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'user-password-reset-success',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'User Password Reset',
                'title'     => 'Password Reset Success',
                'subject'   => '##config.app.name## password has been reset',
                'preheader' => 'Success',
                'content'   => "<p>Dear ##user.name##,</p>
                                <p>Your password has been reset.</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'user-locked-out',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],

                'name'      => 'User Account Locked Out',
                'title'     => 'Account Locked',
                'subject'   => '##config.app.name## account has been locked',
                'preheader' => 'Oops!',
                'content'   => "<p>Dear ##user.name##,</p>
                                <p>Sorry your account has been locked out due to too many bad password attempts.</p>
                                <p>Please contact our customer services team on ##config.email-templates.customer-services.email## who will be able to help</p>
                                 <p>Kind Regards,<br>##config.app.name##</p>"

            ],
            [
                'key'       => 'user-verify-email',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],

                'name'      => 'User Verify Email',
                'title'     => 'Verify your email',
                'subject'   => 'Verify your email with ##config.app.name##',
                'preheader' => 'Gain Access Now',
                'content'   => "<p>Dear ##user.name##,</p>
                                <p>Your receiving this email because your email address has been registered on ##config.app.name##.</p>
                                <p>To activate your account please click the button below.</p>
                                <div>{{button url='##verificationUrl##' title='Verify Email Address'}}</div>
                                <p>If you’re having trouble clicking the 'Verify Email Address' button, copy and paste the URL below into your web browser:</p>
                                <p><a href='##verificationUrl##'>##verificationUrl##</a></p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'user-verified',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'User Verified',
                'title'     => 'Verification Success',
                'subject'   => 'Verification success for ##config.app.name##',
                'preheader' => 'Verification success for ##config.app.name##',
                'content'   => "<p>Hi ##user.name##,</p>
                                <p>Your email address ##user.email## has been verified on ##config.app.name##</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'user-login',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'User Logged In',
                'title'     => 'Login Success',
                'subject'   => 'Login Success for ##config.app.name##',
                'preheader' => 'Login Success for ##config.app.name##',
                'content'   => "<p>Hi ##user.name##,</p>
                                <p>You have been logged into ##config.app.name##.</p>
                                <p>If this was not you please contact: </p>
                                <p>You can disable this email in your account notification preferences.</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],

            // ── Volunteer / Opportunity Templates ────────────────────────
            [
                'key'       => 'volunteer-registration-confirmed',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Volunteer Registration Confirmed',
                'title'     => 'Registration Confirmed',
                'subject'   => 'You\'re registered for ##event.title##',
                'preheader' => 'Your spot is confirmed!',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>Great news! Your registration for <strong>##event.title##</strong> has been confirmed.</p>
                                <p><strong>Date:</strong> ##event.start_date##<br>
                                <strong>Location:</strong> ##event.location##</p>
                                <p>Please make sure to arrive on time. If you have any questions, don't hesitate to reach out to us.</p>
                                <p>Thank you for volunteering with ##config.app.name##!</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'volunteer-shift-reminder',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Volunteer Shift Reminder',
                'title'     => 'Reminder: Your Shift is Tomorrow',
                'subject'   => 'Reminder: ##event.title## is coming up!',
                'preheader' => 'Don\'t forget — your shift is tomorrow.',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>This is a friendly reminder that you have an upcoming volunteer shift for <strong>##event.title##</strong>.</p>
                                <p><strong>Date:</strong> ##event.start_date##<br>
                                <strong>Location:</strong> ##event.location##</p>
                                <p>If you need to make any changes to your registration, please contact us as soon as possible.</p>
                                <p>We look forward to seeing you!</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'event-cancelled',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Event Cancelled',
                'title'     => 'Event Cancellation Notice',
                'subject'   => 'Important: ##event.title## has been cancelled',
                'preheader' => 'We regret to inform you of a cancellation.',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>We regret to inform you that <strong>##event.title##</strong> has been cancelled.</p>
                                <p>We apologise for any inconvenience this may cause. Your commitment to volunteering is greatly appreciated, and we hope to see you at a future opportunity.</p>
                                <p>If you have any questions, please don't hesitate to contact us.</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'volunteer-registration-approved',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Volunteer Registration Approved',
                'title'     => 'Registration Approved',
                'subject'   => 'Your registration for ##event.title## has been approved',
                'preheader' => 'You\'re all set!',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>Good news! Your registration for <strong>##event.title##</strong> has been approved.</p>
                                <p><strong>Date:</strong> ##event.start_date##<br>
                                <strong>Location:</strong> ##event.location##</p>
                                <p>We look forward to seeing you there. Thank you for volunteering with ##config.app.name##!</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'volunteer-registration-rejected',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Volunteer Registration Rejected',
                'title'     => 'Registration Update',
                'subject'   => 'Update on your registration for ##event.title##',
                'preheader' => 'An update on your registration.',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>We're sorry to let you know that your registration for <strong>##event.title##</strong> was not approved.</p>
                                <p>##message##</p>
                                <p>We hope you'll consider joining us for a future opportunity.</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'volunteer-registration-cancelled',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Volunteer Registration Cancelled',
                'title'     => 'Registration Cancelled',
                'subject'   => 'Your registration for ##event.title## has been cancelled',
                'preheader' => 'Your registration has been cancelled.',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>This confirms that your registration for <strong>##event.title##</strong> has been cancelled.</p>
                                <p>If this was a mistake, you're welcome to register again from the opportunity page.</p>
                                <p>Kind Regards,<br>##config.app.name##</p>"
            ],
            [
                'key'       => 'volunteer-completion-summary',
                'from'      => ['email'=>config('mail.from.address'),'name'=>config('mail.from.name')],
                'name'      => 'Volunteer Completion Summary',
                'title'     => 'Thank You for Volunteering!',
                'subject'   => 'Thank you for volunteering at ##event.title##',
                'preheader' => 'Your contribution made a difference.',
                'content'   => "<p>Dear ##user.firstname##,</p>
                                <p>Thank you so much for volunteering at <strong>##event.title##</strong>!</p>
                                <p>Your time and effort are truly appreciated by ##config.app.name## and the community you served. Your volunteer hours have been recorded and added to your profile.</p>
                                <p>We hope to see you at more volunteer opportunities soon. Keep an eye out for upcoming events on the platform.</p>
                                <p>With gratitude,<br>##config.app.name##</p>"
            ],
        ];

        foreach ($emailTemplates as $template) {
            EmailTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
