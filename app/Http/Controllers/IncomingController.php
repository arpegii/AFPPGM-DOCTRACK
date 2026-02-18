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
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedUnitId = null;
        $filterUnits = $user->isAdmin() ? Unit::all() : collect();

        if ($user->isAdmin()) {
            if ($request->has('unit_id')) {
                $selectedUnitId = $request->input('unit_id');

                if ($selectedUnitId) {
                    $request->session()->put('admin_unit_filter_id', $selectedUnitId);
                } else {
                    $request->session()->forget('admin_unit_filter_id');
                }
            } else {
                $selectedUnitId = $request->session()->get('admin_unit_filter_id');
            }
        }
        
        if ($user->isAdmin()) {
            // Admin sees all incoming documents
            $query = Document::with(['senderUnit', 'receivingUnit'])
                ->where('status', 'incoming');

            if ($selectedUnitId) {
                $query->where(function ($subQuery) use ($selectedUnitId) {
                    $subQuery->where('sender_unit_id', $selectedUnitId)
                        ->orWhere('receiving_unit_id', $selectedUnitId);
                });
            }

            $documents = $query->orderBy('created_at', 'desc')->get();
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
        
        return view('incoming.incoming', compact(
            'documents',
            'units',
            'filterUnits',
            'selectedUnitId'
        ));
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
