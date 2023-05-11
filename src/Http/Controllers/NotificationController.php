<?php

namespace Uneca\Scaffold\Http\Controllers;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function __invoke()
    {
        return view('scaffold::notification.index');
    }
}
