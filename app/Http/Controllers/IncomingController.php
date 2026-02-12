<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomingController extends Controller
{
    /**
     * Display a listing of incoming documents
     * Only shows documents with status = 'incoming'
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin sees all incoming documents
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where('status', 'incoming')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Regular users see ONLY incoming documents sent to their unit
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where('receiving_unit_id', $user->unit_id)
                ->where('status', 'incoming')  // Only show pending/incoming documents
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Get units for the create form (excluding admin unit for non-admins)
        $units = Unit::visibleToUser($user);
        
        return view('incoming.incoming', compact('documents', 'units'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get units visible to this user
        $units = Unit::visibleToUser($user);
        
        return view('incoming.create', compact('units'));
    }
}