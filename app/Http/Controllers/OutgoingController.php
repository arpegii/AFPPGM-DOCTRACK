<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingController extends Controller
{
    /**
     * Display outgoing pending documents sent by user's unit.
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

        // Get units visible to the current user (excludes ADMN for non-admins)
        $units = Unit::visibleToUser($user);

        if ($user->isAdmin()) {
            // Admin sees all pending outgoing documents.
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
            // Users see pending documents sent by their unit.
            $documents = Document::with(['senderUnit', 'receivingUnit'])
                ->where('sender_unit_id', $user->unit_id)
                ->where('status', 'incoming')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('outgoing.outgoing', compact(
            'documents',
            'units',
            'filterUnits',
            'selectedUnitId'
        ));
    }
}
