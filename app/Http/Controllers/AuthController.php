<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\LogApp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function index()
    {
        $data['title'] = 'IFG CMS | Login';
        return view('auth.login', $data);
    }

    public function store(LoginRequest $request)
    {


        $request->authenticate();
        $request->session()->regenerate();

        $user = User::where('email', $request->email)->first();

        session([
            'id' => $user->id,
            'name' => $user->name
        ]);

        $response = [
            'status' => 'success',
            'message' => 'Login successful'
        ];

        $log_app = new LogApp();
        $log_app->method = $request->method();
        $log_app->request =  "Authentication";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'login';
        $log_app->user_id = $user->id;
        $log_app->ip_address = $request->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function destroy(Request $request)
    {
        if (Auth::guard('web')->logout()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect('/');
    }
}
