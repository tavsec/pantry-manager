@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add New Item</h1>
            <a href="{{ route('pantry.index') }}" class="mt-4 md:mt-0 inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Pantry
            </a>
        </div>

        <form method="POST" action="{{ route('pantry.store') }}" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg p-6">
            @csrf

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <!-- Item Name -->
                <div>
                    <x-text-input
                        name="name"
                        type="text"
                        label="Item Name"
                        required
                    />
                </div>

                <!-- Category and Location -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category_id') border-red-300 @enderror">
                            <option value="">Select a category (optional)</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            <a href="{{ route('categories.index') }}" class="text-indigo-600 hover:text-indigo-500" target="_blank">Manage categories</a>
                        </p>
                    </div>

                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700">Storage Location</label>
                        <select name="location_id" id="location_id" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('location_id') border-red-300 @enderror">
                            <option value="">Select a location (optional)</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->icon }} {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            <a href="{{ route('locations.index') }}" class="text-indigo-600 hover:text-indigo-500" target="_blank">Manage locations</a>
                        </p>
                    </div>
                </div>

                <!-- Quantity and Unit -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <x-text-input
                            name="quantity"
                            type="number"
                            label="Quantity"
                            step="0.01"
                            min="0"
                            required
                        />
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit *</label>
                        <select name="unit" id="unit" required class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit') border-red-300 @enderror">
                            <option value="">Select a unit</option>
                            <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>pieces</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="lbs" {{ old('unit') == 'lbs' ? 'selected' : '' }}>lbs</option>
                            <option value="grams" {{ old('unit') == 'grams' ? 'selected' : '' }}>grams</option>
                            <option value="oz" {{ old('unit') == 'oz' ? 'selected' : '' }}>oz</option>
                            <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>liters</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ml</option>
                            <option value="cans" {{ old('unit') == 'cans' ? 'selected' : '' }}>cans</option>
                            <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>boxes</option>
                            <option value="bottles" {{ old('unit') == 'bottles' ? 'selected' : '' }}>bottles</option>
                        </select>
                    </div>
                </div>

                <!-- Purchase Date and Expiration Date -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <x-text-input
                            name="purchase_date"
                            type="date"
                            label="Purchase Date"
                            :value="date('Y-m-d')"
                            :max="date('Y-m-d')"
                            required
                        />
                    </div>

                    <div>
                        <x-text-input
                            name="expiration_date"
                            type="date"
                            label="Expiration Date"
                        />
                        <p class="mt-1 text-xs text-gray-500">Optional, but recommended</p>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-300 @enderror" placeholder="Cooking notes, dietary info, etc.">{{ old('notes') }}</textarea>
                </div>

                <!-- Photo Upload -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>

                    <!-- Preview Container -->
                    <div id="photoPreviewContainer" class="hidden mt-2 mb-4">
                        <div class="relative inline-block">
                            <img id="photoPreview" src="" alt="Preview" class="h-48 w-48 object-cover rounded-lg border-2 border-gray-300">
                            <button type="button" onclick="clearPhotoPreview()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="photoUploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="photo" name="photo" type="file" accept="image/*" class="sr-only" onchange="previewPhoto(event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-3">
                <a href="{{ route('pantry.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoPreviewContainer').classList.remove('hidden');
            document.getElementById('photoUploadArea').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function clearPhotoPreview() {
    document.getElementById('photo').value = '';
    document.getElementById('photoPreview').src = '';
    document.getElementById('photoPreviewContainer').classList.add('hidden');
    document.getElementById('photoUploadArea').classList.remove('hidden');
}
</script>
@endsection
