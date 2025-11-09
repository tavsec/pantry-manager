@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('locations.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Locations
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Location</h1>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('locations.update', $location) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <x-text-input
                        name="name"
                        type="text"
                        label="Location Name"
                        :value="$location->name"
                        required
                    />
                </div>

                <!-- Icon -->
                <div>
                    <x-text-input
                        name="icon"
                        type="text"
                        label="Icon (Emoji)"
                        placeholder="e.g., 🚪 or 🧊"
                        :value="$location->icon"
                        maxlength="10"
                    />
                    <p class="mt-1 text-xs text-gray-500">Optional: Add an emoji to represent this location</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('locations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
