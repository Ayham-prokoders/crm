<?php

namespace App\Listeners;

use App\Events\UserAssignedToClass;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{DesignedForm,User};
use App\Models\Role;
use App\Notifications\FormNotification;

class AssignFormToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserAssignedToClass $event): void
    {
        $user = $event->user;
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if ($role->name == 'trainee') {
            // Retrieve the class IDs associated with the user's trainees
            $classIds = $user->trainees()->pluck('class_id')->toArray();
            // Retrieve the forms assigned to the classes
            $forms = DesignedForm::whereIn('classe_id', $classIds)->get();
            if($forms){
            // Assign the forms to the user
            foreach ($forms as $form) {
                $form->recipients()->attach($user->id);
                $user->notify(new FormNotification($role->name,$user->id));

            }}
        }

        $rolesId = $user->roles()->pluck('id');
        // Retrieve forms based on the role
        $forms2 = DesignedForm::where('type', 'with_role')
            ->whereHas('roles', function ($query) use ($rolesId) {
                $query->whereIn('roles.id', $rolesId);
            })
            ->get();
        foreach ($forms2 as $form) {
            $form->recipients()->attach($user->id);
            $user->notify(new FormNotification($role->name,$user->id));

        }
    }
}
