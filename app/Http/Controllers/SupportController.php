<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        $messages = SupportMessage::where('session_id', $sessionId)
            ->orWhere(function ($q) {
                if (Auth::check()) {
                    $q->where('user_id', Auth::id());
                }
            })
            ->latest()
            ->take(30)
            ->get()
            ->reverse();

        return view('shop.support', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = session()->getId();

        $userMsg = SupportMessage::create([
            'user_id' => Auth::id(),
            'session_id' => $sessionId,
            'sender_type' => 'user',
            'message' => $request->message,
        ]);

        // Jibu la mfumo moja kwa moja (Automated smart bot assistant)
        $autoReplies = [
            'Habari! Asante kwa kuwasiliana na TanzaMart Support. Mhudumu wetu anapitia ujumbe wako sasa.',
            'Kama una swali kuhusu kufuatilia oda yako, tafadhali tumia ukurasa wa "Fuatilia Oda" na weka namba yako ya oda.',
            'Kwa masuala ya malipo ya M-Pesa, tafadhali hakikisha namba yako ya simu imewashwa kupokea arifa ya PIN (STK Push).',
        ];
        $botReply = $autoReplies[array_rand($autoReplies)];

        SupportMessage::create([
            'user_id' => null,
            'session_id' => $sessionId,
            'sender_type' => 'bot',
            'message' => $botReply,
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->message,
            'reply' => $botReply,
        ]);
    }

    public function getProductJson(Request $request)
    {
        $query = trim($request->get('query', ''));
        if (empty($query)) {
            return response()->json(['found' => false]);
        }

        $lowerQuery = strtolower($query);
        $listKeywords = ['list', 'products available', 'phones available', 'all products', 'all phones', 'how many', 'picha', 'orodha', 'ngapi zipo', 'bidhaa zote'];
        foreach ($listKeywords as $keyword) {
            if (strpos($lowerQuery, $keyword) !== false) {
                return response()->json(['found' => false]);
            }
        }

        $stopWords = [
            'bei gani', 'bei', 'inauzwa', 'inagharimu', 'kiasi gani', 'kiasi', 
            'gharama', 'ni shilingi', 'shingapi', 'shngapi', 'tsh', 'tshs', 
            'how much', 'price of', 'cost of', 'much is', 'how much is',
            'is', 'available', 'ipo', 'zipo', 'mna', 'mnao', 'naomba', 'about',
            'how about', 'do you have', 'have', 'there', 'any', 'give', 'the', 'of'
        ];

        $cleanQuery = preg_replace('/[^\w\s]/u', '', $lowerQuery);
        foreach ($stopWords as $word) {
            $cleanQuery = preg_replace('/\b' . preg_quote($word, '/') . '\b/ui', '', $cleanQuery);
        }
        $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));

        if (empty($cleanQuery)) {
            return response()->json(['found' => false]);
        }

        $product = \App\Models\Product::whereRaw('LOWER(name) LIKE ?', ['%' . $cleanQuery . '%'])
            ->orWhereRaw('LOWER(details) LIKE ?', ['%' . $cleanQuery . '%'])
            ->first();

        if ($product) {
            return response()->json([
                'found' => true,
                'name' => $product->name,
                'price' => number_format($product->price)
            ]);
        }

        return response()->json(['found' => false]);
    }
}
