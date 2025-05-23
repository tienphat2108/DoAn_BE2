<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.dangky');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'not_regex:/\s{2,}/'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'not_regex:/\s{2,}/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/@gmail\.com$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => trim(preg_replace('/\s+/', ' ', $data['name'])),
            'username' => $data['username'],
            'full_name' => trim(preg_replace('/\s+/', ' ', $data['name'])),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
            'avatar_url' => '/images/default-avatar.png',
        ]);
    }

    public function register(Request $request)
    {
        if (isset($data['name'])) {
             $data['name'] = trim(preg_replace('/\s+/', ' ', $request->input('name')));
             $request->merge(['name' => $data['name']]);
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}