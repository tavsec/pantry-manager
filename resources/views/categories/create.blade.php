@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Categories
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Create New Category</h1>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('categories.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-text-input
                        name="name"
                        type="text"
                        label="Category Name"
                        required
                    />
                </div>

                <!-- Icon -->
                <div>
                    <x-text-input
                        name="icon"
                        type="text"
                        label="Icon (Emoji)"
                        placeholder="e.g., 🌾 or 🥛"
                        maxlength="10"
                    />
                    <p class="mt-1 text-xs text-gray-500">Optional: Add an emoji to represent this category</p>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">
                        Color
                    </label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" name="color" id="color" value="{{ old('color', '#6B7280') }}"
                               class="h-10 w-20 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('color') border-red-300 @enderror">
                        <span class="text-sm text-gray-500">Choose a color for this category</span>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Common Category Suggestions -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Common Categories:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            ['name' => 'Grains', 'icon' => '🌾', 'color' => '#F59E0B'],
                            ['name' => 'Dairy', 'icon' => '🥛', 'color' => '#EFF6FF'],
                            ['name' => 'Vegetables', 'icon' => '🥕', 'color' => '#10B981'],
                            ['name' => 'Fruits', 'icon' => '🍎', 'color' => '#EF4444'],
                            ['name' => 'Meat', 'icon' => '🍖', 'color' => '#DC2626'],
                            ['name' => 'Canned Goods', 'icon' => '🥫', 'color' => '#6B7280'],
                            ['name' => 'Snacks', 'icon' => '🍿', 'color' => '#FBBF24'],
                            ['name' => 'Beverages', 'icon' => '🥤', 'color' => '#3B82F6'],
                        ] as $suggestion)
                            <button type="button" onclick="fillCategory('{{ $suggestion['name'] }}', '{{ $suggestion['icon'] }}', '{{ $suggestion['color'] }}')"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                <span class="mr-1">{{ $suggestion['icon'] }}</span>
                                {{ $suggestion['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillCategory(name, icon, color) {
    document.getElementById('name').value = name;
    document.getElementById('icon').value = icon;
    document.getElementById('color').value = color;
}
</script>
@endsection
