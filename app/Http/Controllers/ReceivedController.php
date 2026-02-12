<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivedController extends Controller
{
    /**
     * Display received documents (documents that have been received by user's unit)
     * These are documents where:
     * - Your unit is the RECEIVING unit (not sender)
     * - Status is 'received' (you marked them as received from incoming)
     * - Excludes 'forwarded' status (those have been forwarded to other units)
     */
    public function index()
    {
        $user = Auth::user();

        // Get filtered units for CREATE document form (excludes ADMN for non-admins)
        $units = Unit::visibleToUser($user);
        
        // Get ALL units for FORWARD dropdown (includes ADMN for forwarding)
        $allUnits = Unit::all();

        if ($user->isAdmin()) {
            // Admin sees all received documents (excluding forwarded ones)
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where('status', 'received')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            // Users see ONLY documents that were SENT TO their unit and marked as received
            // Excludes documents with status 'forwarded' (those appear in outgoing when forwarded)
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where('receiving_unit_id', $user->unit_id)
                ->where('status', 'received')
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        // Pass documents, units (filtered), and allUnits (unfiltered) to the view
        return view('received.received', compact('documents', 'units', 'allUnits'));
    }
}