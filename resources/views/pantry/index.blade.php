@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    My Pantry
                </h1>
            </div>
            <div class="mt-4 flex gap-2 md:mt-0 md:ml-4">
                <!-- View Toggle -->
                <div class="inline-flex rounded-lg border border-gray-300 bg-white">
                    <button type="button" id="cardViewBtn" onclick="switchView('card')" class="px-3 py-2 text-sm font-medium text-gray-700 rounded-l-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button type="button" id="listViewBtn" onclick="switchView('list')" class="px-3 py-2 text-sm font-medium text-gray-700 rounded-r-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <a href="{{ route('pantry.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Item
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('pantry.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name..." class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                        <select name="location_id" id="location_id" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All Locations</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->icon }} {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Expiration Status Filter -->
                    <div>
                        <label for="expiration_status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="expiration_status" id="expiration_status" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All Items</option>
                            <option value="expired" {{ request('expiration_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="expiring_soon" {{ request('expiration_status') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select name="sort_by" id="sort_by" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="expiration_date" {{ request('sort_by') == 'expiration_date' ? 'selected' : '' }}>Expiration Date</option>
                            <option value="purchase_date" {{ request('sort_by') == 'purchase_date' ? 'selected' : '' }}>Purchase Date</option>
                            <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Quantity</option>
                        </select>
                        <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Apply Filters
                    </button>
                    @if (request()->hasAny(['search', 'category_id', 'location_id', 'expiration_status', 'sort_by']))
                        <a href="{{ route('pantry.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Items Display -->
        @if ($items->count() > 0)
            <!-- Card View -->
            <div id="cardView" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($items as $item)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
                        <!-- Expiration Status Bar -->
                        @if ($item->expiration_status === 'expired')
                            <div class="h-2 bg-red-500"></div>
                        @elseif ($item->expiration_status === 'expiring-soon')
                            <div class="h-2 bg-amber-500"></div>
                        @else
                            <div class="h-2 bg-green-500"></div>
                        @endif

                        <div class="p-6">
                            <!-- Photo -->
                            @if ($item->photo_path)
                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Item Details -->
                            <div class="space-y-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>

                                <div class="flex items-center text-sm text-gray-600">
                                    @if ($item->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $item->category->icon }} {{ $item->category->name }}
                                        </span>
                                    @endif
                                    <span class="ml-2">{{ $item->quantity }} {{ $item->unit }}</span>
                                </div>

                                @if ($item->location)
                                    <div class="text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <span class="mr-1">{{ $item->location->icon }}</span>
                                            {{ $item->location->name }}
                                        </div>
                                    </div>
                                @endif

                                @if ($item->expiration_date)
                                    <div class="text-sm">
                                        @if ($item->expiration_status === 'expired')
                                            <span class="text-red-600 font-medium">Expired {{ $item->expiration_date->diffForHumans() }}</span>
                                        @elseif ($item->expiration_status === 'expiring-soon')
                                            <span class="text-amber-600 font-medium">Expires {{ $item->expiration_date->diffForHumans() }}</span>
                                        @else
                                            <span class="text-gray-600">Expires {{ $item->expiration_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 space-y-2">
                                <div class="flex space-x-2">
                                    <button onclick="openLogModal({{ $item->id }}, '{{ $item->name }}', {{ $item->quantity }}, '{{ $item->unit }}')" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-300 shadow-sm text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Log Usage
                                    </button>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('pantry.edit', $item) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Edit
                                    </a>
                                    <form action="{{ route('pantry.destroy', $item) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- List View -->
            <div id="listView" class="hidden bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Expiration
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                @if ($item->notes)
                                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($item->notes, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $item->category->icon }} {{ $item->category->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if ($item->location)
                                            <span>{{ $item->location->icon }} {{ $item->location->name }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($item->expiration_date)
                                            <div>{{ $item->expiration_date->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->expiration_date->diffForHumans() }}</div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->expiration_status === 'expired')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Expired
                                            </span>
                                        @elseif ($item->expiration_status === 'expiring-soon')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                Expiring Soon
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Fresh
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <button onclick="openLogModal({{ $item->id }}, '{{ $item->name }}', {{ $item->quantity }}, '{{ $item->unit }}')" class="text-indigo-600 hover:text-indigo-900" title="Log Usage">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </button>
                                            <a href="{{ route('pantry.edit', $item) }}" class="text-gray-600 hover:text-gray-900" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('pantry.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding your first pantry item.</p>
                <div class="mt-6">
                    <a href="{{ route('pantry.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Item
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Log Usage Modal -->
<div id="logModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Log Usage for <span id="modalItemName"></span></h3>

            <form id="logForm" method="POST" action="">
                @csrf

                <div class="space-y-4">
                    <!-- Action -->
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700">Action *</label>
                        <select name="action" id="action" required class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select action</option>
                            <option value="consumed">Consumed</option>
                            <option value="wasted">Wasted</option>
                            <option value="expired">Expired</option>
                            <option value="added">Added More</option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <input type="number" name="quantity" id="quantity" step="0.01" min="0.01" required class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <span id="modalUnit" class="text-sm text-gray-600"></span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Available: <span id="modalQuantity"></span> <span id="modalUnit2"></span></p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Optional notes..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="button" onclick="closeLogModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// View switching functionality
function switchView(viewType) {
    const cardView = document.getElementById('cardView');
    const listView = document.getElementById('listView');
    const cardBtn = document.getElementById('cardViewBtn');
    const listBtn = document.getElementById('listViewBtn');

    if (viewType === 'card') {
        cardView.classList.remove('hidden');
        listView.classList.add('hidden');
        cardBtn.classList.add('bg-indigo-100', 'text-indigo-700');
        cardBtn.classList.remove('text-gray-700');
        listBtn.classList.remove('bg-indigo-100', 'text-indigo-700');
        listBtn.classList.add('text-gray-700');
    } else {
        cardView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-indigo-100', 'text-indigo-700');
        listBtn.classList.remove('text-gray-700');
        cardBtn.classList.remove('bg-indigo-100', 'text-indigo-700');
        cardBtn.classList.add('text-gray-700');
    }

    // Save preference to localStorage
    localStorage.setItem('pantryViewPreference', viewType);
}

// Load saved view preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('pantryViewPreference') || 'card';
    switchView(savedView);
});

function openLogModal(itemId, itemName, quantity, unit) {
    document.getElementById('logModal').classList.remove('hidden');
    document.getElementById('modalItemName').textContent = itemName;
    document.getElementById('modalQuantity').textContent = quantity;
    document.getElementById('modalUnit').textContent = unit;
    document.getElementById('modalUnit2').textContent = unit;
    document.getElementById('logForm').action = '/pantry/' + itemId + '/log';

    // Reset form
    document.getElementById('logForm').reset();
}

function closeLogModal() {
    document.getElementById('logModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('logModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogModal();
    }
});
</script>
@endsection
