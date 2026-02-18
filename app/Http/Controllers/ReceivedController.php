<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivedController extends Controller
{
    /**
     * Display documents marked as received from incoming.
     * For regular users this is limited to documents they personally marked as received.
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

        // Get filtered units for CREATE document form (excludes ADMN for non-admins)
        $units = Unit::visibleToUser($user);
        
        // Get ALL units for FORWARD dropdown (includes ADMN for forwarding)
        $allUnits = Unit::all();

        if ($user->isAdmin()) {
            // Admin sees all received documents.
            $query = Document::with(['senderUnit', 'receivingUnit'])
                ->where('status', 'received');

            if ($selectedUnitId) {
                $query->where(function ($subQuery) use ($selectedUnitId) {
                    $subQuery->where('sender_unit_id', $selectedUnitId)
                        ->orWhere('receiving_unit_id', $selectedUnitId);
                });
            }

            $documents = $query->orderBy('updated_at', 'desc')->get();
        } else {
            // Users see documents they marked as received from incoming.
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where('receiving_unit_id', $user->unit_id)
                ->where('status', 'received')
                ->where('received_by', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        // Pass documents, units (filtered), and allUnits (unfiltered) to the view
        return view('received.received', compact(
            'documents',
            'units',
            'allUnits',
            'filterUnits',
            'selectedUnitId'
        ));
    }
}
