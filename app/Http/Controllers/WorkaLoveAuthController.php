<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WorkaloveAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string'
        ]);

        $url = "https://workability-stg.worka.love/api/v1/students/{$cpf}/token";

        try {
            $response = Http::acceptJson()->post($url);

            if ($response->successful()) {
                $token = $response->json('token');

                if (!$token) {
                    return response()->json(['error' => 'Token não encontrado.'], 500);
                }

                $redirectUrl = "https://workability-stg.worka.love/#/login/faesa/aluno?authentication_token={$token}";

                return response()->json([
                    'token' => $token,
                    'redirect_url' => $redirectUrl
                ]);
            }

            return response()->json([
                'error' => 'Erro ao obter token da Workalove.',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno ao tentar autenticar.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}
