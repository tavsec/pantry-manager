<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePantryItemRequest;
use App\Http\Requests\UpdatePantryItemRequest;
use App\Models\PantryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PantryItemController extends Controller
{
    /**
     * Display a listing of the resource with search, filter, and sorting.
     */
    public function index(Request $request): View
    {
        $query = $request->user()->pantryItems()->with(['category', 'location']);

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->input('location_id'));
        }

        // Filter by expiration status
        if ($request->filled('expiration_status')) {
            $status = $request->input('expiration_status');
            if ($status === 'expired') {
                $query->whereNotNull('expiration_date')->whereDate('expiration_date', '<', now());
            } elseif ($status === 'expiring_soon') {
                $query->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '>=', now())
                    ->whereDate('expiration_date', '<=', now()->addDays(7));
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSortFields = ['name', 'purchase_date', 'expiration_date', 'quantity', 'created_at'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $items = $query->paginate(12)->withQueryString();

        // Get categories and locations for filters
        $categories = $request->user()->categories()->orderBy('name')->get();
        $locations = $request->user()->locations()->orderBy('name')->get();

        return view('pantry.index', compact('items', 'categories', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $categories = $request->user()->categories()->orderBy('name')->get();
        $locations = $request->user()->locations()->orderBy('name')->get();

        return view('pantry.create', compact('categories', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePantryItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('pantry-photos', 'public');
        }

        PantryItem::create($data);

        return redirect()->route('pantry.index')->with('success', 'Item added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PantryItem $pantryItem): View
    {
        $this->authorize('view', $pantryItem);

        $pantryItem->load(['category', 'location']);

        return view('pantry.show', compact('pantryItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, PantryItem $pantryItem): View
    {
        $this->authorize('update', $pantryItem);

        $categories = $request->user()->categories()->orderBy('name')->get();
        $locations = $request->user()->locations()->orderBy('name')->get();

        return view('pantry.edit', compact('pantryItem', 'categories', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePantryItemRequest $request, PantryItem $pantryItem): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($pantryItem->photo_path) {
                Storage::disk('public')->delete($pantryItem->photo_path);
            }

            $data['photo_path'] = $request->file('photo')->store('pantry-photos', 'public');
        }

        $pantryItem->update($data);

        return redirect()->route('pantry.index')->with('success', 'Item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PantryItem $pantryItem): RedirectResponse
    {
        $this->authorize('delete', $pantryItem);

        // Delete photo if exists
        if ($pantryItem->photo_path) {
            Storage::disk('public')->delete($pantryItem->photo_path);
        }

        $pantryItem->delete();

        return redirect()->route('pantry.index')->with('success', 'Item deleted successfully!');
    }
}
