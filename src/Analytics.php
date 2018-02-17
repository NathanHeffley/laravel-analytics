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

        if ($this->isExcluded($user)) return;

        $pageview = new Pageview([
            'user_id' => is_null($user) ? null : $user->id,
            'path' => $data['path'],
        ]);

        $pageview->save();
    }

    protected function isExcluded($user)
    {
        $excludedColumn = config('excluded.column');
        $excludedValues = collect(config('excluded.values'));

        if ($excludedValues->isEmpty()) return false;

        if ($excludedValues->contains($user->$excludedColumn)) return true;

        return false;
    }
}
