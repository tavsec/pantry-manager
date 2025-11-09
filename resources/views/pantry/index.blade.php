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
            <div class="mt-4 flex md:mt-0 md:ml-4">
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

        <!-- Items Grid -->
        @if ($items->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                            <div class="mt-4 flex space-x-2">
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
                @endforeach
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
@endsection
