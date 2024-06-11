<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contents.login.index', [
            'title' => 'Form Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('nik', 'password');

        // Cek kredensial terlebih dahulu
        if (Auth::attempt($credentials)) {
            // Ambil pengguna yang terotentikasi
            $user = Auth::user();

            // Cek apakah status pengguna aktif
            if ($user->is_active !== '1') {
                // Logout pengguna segera
                Auth::logout();

                // Redirect kembali dengan pesan error jika user tidak aktif
                return back()->with('loginError', 'Username or Password is incorrect!');
            }

            // Otentikasi berhasil dan pengguna aktif
            return redirect()->intended('/dashboard');
        }

        // Otentikasi gagal
        return back()->with('loginError', 'Username or Password is incorrect!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}