<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingController extends Controller
{
    /**
     * Display outgoing documents (sent by user's unit)
     * Shows all statuses: incoming, received, rejected, forwarded
     * Includes both originally sent documents and forwarded documents
     */
public function index()
{
    $user = Auth::user();

    // Get units visible to the current user (excludes ADMN for non-admins)
    $units = Unit::visibleToUser($user);

    if ($user->isAdmin()) {
        // Admin sees all documents
        $documents = Document::with(['senderUnit', 'receivingUnit'])
            ->orderBy('created_at', 'desc')
            ->get();
    } else {
        // Users see only documents sent by their unit
        $documents = Document::with(['senderUnit', 'receivingUnit'])
            ->where('sender_unit_id', $user->unit_id)
            ->whereIn('status', ['incoming', 'received', 'rejected', 'forwarded'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    return view('outgoing.outgoing', compact('documents', 'units'));
}
}