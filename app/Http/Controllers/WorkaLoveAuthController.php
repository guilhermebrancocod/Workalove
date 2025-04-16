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

        $cpf = $request->cpf;

        $url = "https://workability-stg.worka.love/api/v1/students/$cpf/token";

        try {
            $response = Http::acceptJson()
                ->withBasicAuth('faesa', '6731d48639b1144987c478afe09bbf285e8cec82')
                ->withOptions([
                    'verify' => false
                ])
                ->post($url);

            if ($response->successful()) {
                $token = $response->json('authentication_token');

                if (!$token) {
                    return response()->json(['error' => 'Token não encontrado.'], 500);
                }

                $redirectUrl = 'https://workability-stg.worka.love/#/login/faesa/aluno?authentication_token=' . $token;

                return redirect()->away($redirectUrl);
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
