<?php

namespace Uneca\Scaffold\Mail;

use Uneca\Scaffold\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitation $invitation) {}

    public function build()
    {
        return $this->subject(config('app.name') . ' registration invitation')
            ->markdown('scaffold::mail.invitation')
            ->with(['ttl' => config('scaffold.invitation.ttl_hours')]);
    }
}
