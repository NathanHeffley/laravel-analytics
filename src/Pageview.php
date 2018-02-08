<?php

namespace NathanHeffley\Analytics;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Pageview extends Model
{
    protected $fillable = ['user_id', 'path'];

    /**
     * Record a pageview (optionally for a user)
     *
     * @param string $path
     * @param User|null $user
     * @return bool
     */
    public function record(string $path, User $user = null): bool
    {
        $pageview = new Pageview([
            'user_id' => is_null($user) ? null : $user->id,
            'path' => $path,
        ]);

        return $pageview->save();
    }
}
