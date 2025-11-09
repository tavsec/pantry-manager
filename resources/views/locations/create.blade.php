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
            <h1 class="text-2xl font-bold text-gray-900">Create New Location</h1>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('locations.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Location Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700">
                        Icon (Emoji)
                    </label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g., 🚪 or 🧊" maxlength="10"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('icon') border-red-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Optional: Add an emoji to represent this location</p>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Common Location Suggestions -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Common Locations:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            ['name' => 'Pantry', 'icon' => '🚪'],
                            ['name' => 'Fridge', 'icon' => '🧊'],
                            ['name' => 'Freezer', 'icon' => '❄️'],
                            ['name' => 'Cabinet', 'icon' => '🗄️'],
                            ['name' => 'Counter', 'icon' => '🏠'],
                            ['name' => 'Basement', 'icon' => '🏚️'],
                            ['name' => 'Garage', 'icon' => '🚗'],
                            ['name' => 'Wine Cellar', 'icon' => '🍷'],
                        ] as $suggestion)
                            <button type="button" onclick="fillLocation('{{ $suggestion['name'] }}', '{{ $suggestion['icon'] }}')"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                <span class="mr-1">{{ $suggestion['icon'] }}</span>
                                {{ $suggestion['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('locations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillLocation(name, icon) {
    document.getElementById('name').value = name;
    document.getElementById('icon').value = icon;
}
</script>
@endsection
