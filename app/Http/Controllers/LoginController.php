<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Services\Login\RememberMeExpiration;
// use App\Http\Controllers\Log;

class LoginController extends Controller
{
    use RememberMeExpiration;
    public function __construct()
    {
        
    }
    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show(Request $request)
    {
        if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
        return view('auth.login_agil');
    }

    

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    // public function login(LoginRequest $request)
    // {
    //     $credentials = $request->getCredentials();

    //     if(!Auth::validate($credentials)):
    //         return redirect()->to('login')
    //             ->withErrors(trans('auth.failed'));
    //     endif;

    //     $user = Auth::getProvider()->retrieveByCredentials($credentials);

    //     Auth::login($user, $request->get('remember'));

    //     if($request->get('remember')):
    //         $this->setRememberMeExpiration($user);
    //     endif;

    //     return $this->authenticated($request, $user);
    // }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            // 'email' => [
            //             'required',
            //             'email',
            //             // 'email:dns'
            //             ],
            'username' => ['required','min:5'],
            'password' => ['required','min:5'],
        ]);
 
        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();
           
            if(Auth::user() )
            {
                $user = Auth::getProvider()->retrieveByCredentials($credentials);
                $this->authenticated($request, $user);
                
                return redirect()->intended("dashboard");

            }
        }
 
        return back()->withErrors([
            'main_alert' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        // get and insert log history
        Log::create([
            'user_id' => $user->id,
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

    }
}
