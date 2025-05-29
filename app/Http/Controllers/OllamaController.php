<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaController extends Controller
{
    /**
     * Handle the chat request
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'present|array',
            'language' => 'nullable|string'
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);
        $language = $request->input('language', 'fr');

        // Format history for Ollama
        $messages = [];
        
        // Add system message to set language and context
        $systemPrompt = $language === 'fr' 
            ? "Tu es un assistant intelligent en français. Sois concis, utile et amical."
            : "You are an intelligent assistant. Be concise, helpful and friendly.";
            
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt
        ];

        // Format conversation history for Ollama
        foreach ($history as $item) {
            if (!empty($item['user'])) {
                $messages[] = [
                    'role' => 'user',
                    'content' => $item['user']
                ];
            }
            if (!empty($item['assistant'])) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $item['assistant']
                ];
            }
        }

        // Add the current user message
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        try {
            // Make request to Ollama API
            $response = Http::timeout(30)->post('http://localhost:11434/api/chat', [
                'model' => 'mistral',
                'messages' => $messages,
                'stream' => false
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json([
                    'response' => $responseData['message']['content'] ?? 'No response content',
                    'success' => true
                ]);
            } else {
                Log::error('Ollama API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'message' => $language === 'fr' 
                        ? 'Erreur de communication avec le modèle AI.'
                        : 'Error communicating with the AI model.',
                    'success' => false
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Ollama exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => $language === 'fr' 
                    ? 'Une erreur est survenue lors du traitement de votre demande.'
                    : 'An error occurred while processing your request.',
                'success' => false
            ], 500);
        }
    }
}