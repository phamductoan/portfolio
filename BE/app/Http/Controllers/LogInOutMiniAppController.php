<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LogInOutMiniAppController extends Controller
{
    /**
     * Hiển thị form đăng nhập/đăng ký.
     */
    public function showForm()
    {
        return view('logInOut');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Thử đăng nhập
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Nếu đăng nhập thành công, chuyển hướng đến trang chủ (hoặc theo id đã định)
            return redirect()->intended('/');
        }

        // Nếu đăng nhập thất bại, trở lại form với thông báo lỗi
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    /**
     * Xử lý đăng ký.
     */
    public function register(Request $request)
    {
        // Validate dữ liệu đăng ký
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Đăng nhập tự động sau khi đăng ký
        Auth::login($user);

        return redirect()->intended('/');
    }

    /**
     * Xử lý đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
