<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;
use Illuminate\Support\Str;

class EmailController extends Controller
{

    public function forgotpassword(){
        return view('forgetPass\forgotPassword');
    }


    public function sendOtp(Request $request)
    {
      $request->validate([
          'email' => 'required|email|exists:users,email',
      ]);

      $otp = rand(100000, 999999);
      $email = $request->email;

      PasswordResetToken::updateOrCreate(
          ['email' => $email],
          ['token' => $otp, 'created_at' => now()]
      );

      Mail::send([], [], function ($message) use ($email, $otp) {
          $message->to($email)
              ->subject('OTP for Password Reset')
              ->text("Your OTP for password reset is: $otp");
      });

      return redirect()->route('otp.verify')->with('message', 'OTP has been sent to your email.');
    }

    public function showVerifyOtpForm()
    {
        return view('forgetPass\verifOtp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);

        $resetToken = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if ($resetToken && now()->diffInMinutes($resetToken->created_at) <= 5) {
            return redirect()->route('password.update', ['email' => $request->email]);
        }

        return back()->withErrors(['otp' => 'The OTP is invalid or expired.']);
    }

    public function resetPasswordview(){
        return view('forgetPass/resetPassword');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        PasswordResetToken::where('email', $request->email)->delete();

        return redirect()->route('index')->with('message', 'Password has been successfully reset.');
    }

    public function testEmail()
    {
        Mail::raw('Your OTP "345623", use this code to reset your password', function ($message) {
        $message->to('jonathansendewana17@gmail.com')
                ->subject('OTP Reset Password');
        });

        return 'Email sent!';
    }
}
