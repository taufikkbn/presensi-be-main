<?php

namespace App\Listeners;

use App\Events\CreateStudentAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class CreateStudentAccountListener
{
    public function __construct()
    {

    }

    public function handle(CreateStudentAccount $event)
    {
        $student = $event->student;
        $email = $event->email;
    
        $user = new User();
        $user->name = $student->name;
        $user->email = $email;
        $user->password = Hash::make('welcome1');
        $user->role = 'student';
        $user->isDefault = true;
        $user->save();

        $student->user_id = $user->id;
        $student->save();
    }
}
