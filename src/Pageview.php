<?php

namespace NathanHeffley\Analytics;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Pageview extends Model
{
    protected $fillable = ['user_id', 'path'];
}
