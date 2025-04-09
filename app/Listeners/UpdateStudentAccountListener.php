<?php
namespace App\Listeners;

use App\Events\UpdateStudentAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;

class UpdateStudentAccountListener
{
    /**
     * Handle the event.
     *
     * @param  UpdateStudentAccount  $event
     * @return void
     */
    public function handle(UpdateStudentAccount $event)
    {
        $student = $event->student;
        $email = $event->email;
        
        $user = User::where('id', $student->user_id)->first();
        if ($user) {
            $user->name = $student->name;
            $user->email = $email;
            // Lakukan perubahan lain yang diperlukan
            $user->save();
        }
    }
}
