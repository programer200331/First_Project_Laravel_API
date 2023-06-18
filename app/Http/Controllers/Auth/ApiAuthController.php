<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => [
                'required', 'string',
                Password::min(8) // ثمانية حروف على الأقل
                    ->letters() // تحتوي على حروف
                    ->symbols() // و رموز
                    ->numbers() // و أرقام
                    ->mixedCase() // و أحرف كبيرة وصغيرة
                    ->uncompromised(), // ليست من الكلمات الضعيفة
            ],
        ]);

        if (!$validator->fails()) {
            $admin = new Admin();
            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $admin->password = Hash::make($request->input('password'));
            $saved = $admin->save();

            return response()->json(
                ['status' => $saved, 'message' => $saved ? "Registered Successfully" : "Registration Failed", 'object' => $admin],
                $saved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json(['status' => false, 'message' => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|string'
        ]);

        if (!$validator->fails()) {
            $admin = Admin::where('email', '=', $request->input('email'))->first();
            if (Hash::check($request->input('password'), $admin->password)) {
                $token = $admin->createToken('admin-api-token-' . $admin->id);
                $admin->token = $token->accessToken;
                return response()->json(['status' => true, 'message' => 'Login Successfully', 'object' => $admin], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'The password is not correct'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(['status' => false, 'message' => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        $admin = $request->user('admin-api'); // OR $request->user();
        $revoked = $admin->token()->revoke();
        return response()->json([
            'status' => $revoked, 'message' => $revoked ? 'Logged out successfully' : 'Logout failed :('
        ], $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
