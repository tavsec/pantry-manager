<?php

namespace App\Http\Controllers;

use App\Models\PantryItem;
use App\Models\PantryLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PantryLogController extends Controller
{
    /**
     * Display a listing of all pantry logs.
     */
    public function index(): View
    {
        $logs = PantryLog::with(['pantryItem', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('pantry-logs.index', compact('logs'));
    }

    /**
     * Store a newly created log in storage.
     */
    public function store(Request $request, PantryItem $pantryItem): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:consumed,added,wasted,expired',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
        ]);

        // Ensure the item belongs to the authenticated user
        if ($pantryItem->user_id !== auth()->id()) {
            abort(403);
        }

        // Create the log
        PantryLog::create([
            'pantry_item_id' => $pantryItem->id,
            'user_id' => auth()->id(),
            'action' => $validated['action'],
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update the pantry item quantity based on action
        if (in_array($validated['action'], ['consumed', 'wasted', 'expired'])) {
            $pantryItem->quantity -= $validated['quantity'];
        } else {
            $pantryItem->quantity += $validated['quantity'];
        }

        // If quantity is 0 or less, soft delete the item
        if ($pantryItem->quantity <= 0) {
            $pantryItem->delete();

            return redirect()->route('pantry.index')
                ->with('success', 'Item logged and removed from pantry (quantity reached zero).');
        }

        $pantryItem->save();

        return redirect()->route('pantry.index')
            ->with('success', 'Pantry log created successfully.');
    }
}
