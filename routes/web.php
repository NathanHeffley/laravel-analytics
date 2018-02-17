<?php

use Illuminate\Support\Facades\Route;

Route::prefix('analytics')->group(function () {
    Route::get('/', 'AnalyticsController@index')->name('analytics.index');
});
