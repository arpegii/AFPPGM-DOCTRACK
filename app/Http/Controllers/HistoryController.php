<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Unit; // <-- Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display all document history (all statuses)
     */
    public function index()
    {
        $user = Auth::user();

        // Get all units for dropdowns or displaying names
        $units = Unit::visibleToUser($user);

        if ($user->isAdmin()) {
            // Admin sees all documents
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Users see only documents where their unit is sender OR receiver
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where(function($query) use ($user) {
                    $query->where('sender_unit_id', $user->unit_id)
                          ->orWhere('receiving_unit_id', $user->unit_id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Pass both documents and units to the view
        return view('history.history', compact('documents', 'units'));
    }
}