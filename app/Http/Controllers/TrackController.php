<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    /**
     * Display track/search page
     * UPDATED: Now includes forwarding history in access control
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $documents = collect(); // Empty collection initially
        $searchQuery = $request->input('search');
        
        if ($searchQuery) {
            // Build base query with access control and eager load all relationships
            $query = Document::with([
                'senderUnit',
                'receivingUnit',
                'creator',
                'receivedBy',
                'rejectedBy',
                'forwardHistory.fromUnit',
                'forwardHistory.toUnit',
                'forwardHistory.forwardedBy'
            ]);
            
            // Apply access control - includes forwarding history
            if (!$user->isAdmin()) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_unit_id', $user->unit_id)
                      ->orWhere('receiving_unit_id', $user->unit_id)
                      ->orWhereHas('forwardHistory', function($subQuery) use ($user) {
                          $subQuery->where('from_unit_id', $user->unit_id)
                                   ->orWhere('to_unit_id', $user->unit_id);
                      });
                });
            }
            
            // Apply search filters
            $query->where(function($q) use ($searchQuery) {
                $q->where('document_number', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('title', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('document_type', 'LIKE', "%{$searchQuery}%");
            });
            
            $documents = $query->orderBy('created_at', 'desc')->get();
        }
        
        return view('track.track', compact('documents', 'searchQuery'));
    }
}