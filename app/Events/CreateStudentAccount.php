<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;

class CreateStudentAccount
{
    use Dispatchable, SerializesModels;

    public $student;
    public $email;

    public function __construct($student, $email)
    {
        $this->student = $student;
        $this->email = $email;
    }
}
