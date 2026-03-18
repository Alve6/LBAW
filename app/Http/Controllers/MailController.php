<?php

namespace App\Http\Controllers;

use App\Mail\MailModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    function send(Request $request) {
        //Check if email exists
        $validator = Validator::make($request->all(), [
            'email' => [
                'required', function ($attribute, $value, $fail) {
                    if (is_null(User::where('email', $value)->first())) {
                        $fail('Email not found');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $code = bin2hex(random_bytes(10));
        $user = User::where('email', $request->email)->first();
        session([
            'recovery_code' => $code,
            'user' => $user->id,
        ]);

        $mailData = [
            'email' => $request->email,
            'code'  => $code,
        ];

        Mail::to($request->email)->send(new MailModel($mailData));
        return redirect()->route('recoverPasswordForm');
    }
}
