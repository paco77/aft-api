<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Send a 6-digit PIN code to the user's email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Return success even if user doesn't exist (security best practice)
            return response()->json([
                'message' => 'Si el correo existe, recibirás un código de recuperación.',
            ]);
        }

        // Generate a 6-digit PIN code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Store the hashed code
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($code),
            'created_at' => now(),
        ]);

        // Send the notification
        $user->notify(new ResetPasswordNotification($code));

        return response()->json([
            'message' => 'Si el correo existe, recibirás un código de recuperación.',
        ]);
    }

    /**
     * Step 2: Verify the PIN code and return a temporary token.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Código inválido o expirado.',
            ], 422);
        }

        // Check if the token has expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'message' => 'El código ha expirado. Solicita uno nuevo.',
            ], 422);
        }

        // Verify the code
        if (!Hash::check($request->code, $record->token)) {
            return response()->json([
                'message' => 'Código inválido o expirado.',
            ], 422);
        }

        // Generate a temporary reset token
        $resetToken = Str::random(64);

        // Update the record with the new token for the reset step
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->update([
            'token' => Hash::make($resetToken),
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'Código verificado correctamente.',
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * Step 3: Reset the password using the temporary token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Token inválido o expirado.',
            ], 422);
        }

        // Check expiration (10 minutes for the reset token)
        if (now()->diffInMinutes($record->created_at) > 10) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'message' => 'El token ha expirado. Solicita un nuevo código.',
            ], 422);
        }

        // Verify the token
        if (!Hash::check($request->token, $record->token)) {
            return response()->json([
                'message' => 'Token inválido o expirado.',
            ], 422);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token inválido o expirado.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Revoke all existing tokens
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña actualizada correctamente.',
        ]);
    }
}
