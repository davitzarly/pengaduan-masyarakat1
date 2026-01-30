<?php

namespace App\Http\Controllers;

use App\Models\UsabilityFeedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = UsabilityFeedback::with('user')->latest()->get();
        $average = $feedbacks->avg('rating');
        $count = $feedbacks->count();

        return view('feedback.index', compact('feedbacks', 'average', 'count'));
    }

    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        UsabilityFeedback::create([
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()->route('feedback.create')->with('success', 'Terima kasih atas feedback Anda.');
    }

    // --- PUBLIC METHODS (Guest) ---

    public function createPublic()
    {
        return view('feedback.public');
    }

    public function storePublic(Request $request)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'guest_name' => 'nullable|string|max:100', // Opsional, nama pengisi
        ]);

        UsabilityFeedback::create([
            'user_id' => null, // Guest
            'guest_name' => $data['guest_name'] ?? 'Masyarakat Umum',
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()->route('feedback.public.create')->with('success', 'Terima kasih! Masukan Anda sangat berarti bagi kami.');
    }
}
