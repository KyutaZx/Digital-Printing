<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display Login History
    |--------------------------------------------------------------------------
    */

    public function loginHistory()
    {
        $logs = UserLog::with('user')
            ->where('action', 'login')
            ->latest()
            ->paginate(20);

        return view('logs.login_history', compact('logs'));
    }

    /*
    |--------------------------------------------------------------------------
    | Display All User Activity
    |--------------------------------------------------------------------------
    */

    public function activity()
    {
        $logs = UserLog::with('user')
            ->latest()
            ->paginate(20);

        return view('logs.activity', compact('logs'));
    }

    /*
    |--------------------------------------------------------------------------
    | Security Audit
    |--------------------------------------------------------------------------
    */

    public function audit()
    {
        $logs = UserLog::with('user')
            ->whereIn('action', ['login', 'logout'])
            ->latest()
            ->paginate(20);

        return view('logs.audit', compact('logs'));
    }
}