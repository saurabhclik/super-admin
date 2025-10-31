<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Session;

class AuthenticateAccess
{
    protected $flasher;

    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) 
        {
            $this->flasher->addError('Access denied. Please log in first.');
            return redirect()->route('login');
        }

        return $next($request);
    }
    
}