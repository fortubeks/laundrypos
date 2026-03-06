<?php
namespace App\Observers;

use App\Models\User;
use Database\Seeders\DefaultLaundrySeeder;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role === "Super Admin") {
            //create a laundry for this user
            $laundry = $user->laundry()->create([
                'name'    => $user->name . "'s Laundry",
                'user_id' => $user->id,
            ]);
            //update the user with the laundry id
            $user->laundry_id = $laundry->id;
            $user->save();

            // if ($user->laundry_id) {
            //     $seeder = new DefaultLaundrySeeder();
            //     $seeder->run($user->laundry_id);
            // }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
