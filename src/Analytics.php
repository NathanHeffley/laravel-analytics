<?php

namespace NathanHeffley\Analytics;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    public function record($type, $data)
    {
        $function = 'record' . ucfirst(strtolower($type));

        $this->$function($data);
    }

    protected function recordPageview($data)
    {
        $user = empty($data['user']) ? null : $data['user'];

        $excludedColumn = config('excluded.column');
        $excludedValues = config('excluded.values');

        $abort = false;
        if ($user && $excludedValues) {
            foreach($excludedValues as $value) {
                if ($user->$excludedColumn == $value) {
                    $abort = true;
                }
            }
        }
        if ($abort) return;

        $userId = empty($user) ? null : $user->id;

        $pageview = new Pageview([
            'user_id' => $userId,
            'path' => $data['path'],
        ]);

        $pageview->save();
    }
}
