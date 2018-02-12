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
        $pageview = new Pageview([
            'user_id' => empty($data['user']) ? null : $data['user']->id,
            'path' => $data['path'],
        ]);

        $pageview->save();
    }
}
