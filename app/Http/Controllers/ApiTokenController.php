<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
/*public function create(array $data)
{

    return ApiToken::create([
        'user_id' => $data['user_id'],
        'token' => $data['token'],
    ]);
}*/
    public function update(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }
}
