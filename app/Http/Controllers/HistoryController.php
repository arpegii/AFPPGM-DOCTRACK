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

        // Get all units for dropdowns or displaying names
        $units = Unit::visibleToUser($user);

        if ($user->isAdmin()) {
            // Admin sees all documents
            $query = Document::with(['senderUnit', 'receivingUnit']);

            if ($selectedUnitId) {
                $query->where(function ($subQuery) use ($selectedUnitId) {
                    $subQuery->where('sender_unit_id', $selectedUnitId)
                        ->orWhere('receiving_unit_id', $selectedUnitId);
                });
            }

            $documents = $query->orderBy('created_at', 'desc')->get();
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
        return view('history.history', compact(
            'documents',
            'units',
            'filterUnits',
            'selectedUnitId'
        ));
    }
}
