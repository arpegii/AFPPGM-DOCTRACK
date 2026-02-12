<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class ForwardedController extends Controller
{
    /**
     * Show forwarded documents for the logged-in user
     */
    public function index()
    {
        $documents = Document::with(['forwardedUnit'])
            ->where('forwarded_by', Auth::id())
            ->latest('forwarded_at')
            ->get();

        return view('forwarded.index', compact('documents'));
    }
}
