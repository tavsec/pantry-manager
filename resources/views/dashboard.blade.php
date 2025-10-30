@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                <p class="text-gray-600">Welcome to your dashboard, {{ auth()->user()->name }}!</p>
            </div>
        </div>
    </div>
</div>
@endsection
