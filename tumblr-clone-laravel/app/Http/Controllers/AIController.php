<?php

namespace App\Http\Controllers;

use App\Services\AI\ContentGeneratorAgent;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function generate(Request $request, ContentGeneratorAgent $agent)
    {
        $data = $request->validate([
            'idea' => 'required|string|max:500',
            'type' => 'sometimes|in:text,quote',
        ]);

        $result = $agent->generate($data['idea'], $data['type'] ?? 'text');

        return response()->json($result);
    }
}
