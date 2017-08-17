<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth2fa;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $user = Auth::user();
        $model = new Auth2fa();
        $authflag = $model->is2faAuthed($user->id);
        return view('settings.index')->with('auth2fa',$authflag);
    }
}
