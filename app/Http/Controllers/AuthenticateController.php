<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthenticateController extends Controller
{
    protected $flasher;

    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loginUser' => 'required',
            'loginPass' => 'required',
        ], [
            'loginUser.required' => 'Username or email is required',
            'loginPass.required' => 'Password is required',
        ]);

        if ($validator->fails()) 
        {
            foreach ($validator->errors()->all() as $error) 
            {
                $this->flasher->addError($error);
            }
            return redirect('/')->withInput();
        }

        try 
        {
            $user = DB::table('users')
                ->where('email', $request->loginUser)
                ->orWhere('name', $request->loginUser)
                ->first();

            if ($user && Hash::check($request->loginPass, $user->password)) 
            {
                session([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                ]);

                $this->flasher->addSuccess('Welcome back, '.$user->name.'!');
                return redirect()->route('software.manage');
            }

            $this->flasher->addError('Invalid username or password.');
            return back()->withInput();

        } 
        catch(Exception $error) 
        {
            $this->flasher->addError('Login failed. Please try again.');
            return back()->withInput();
        }
    }

    public function logout()
    {
        session()->flush();
        $this->flasher->addSuccess('You have been logged out.');
        return redirect()->route('login');
    }
}