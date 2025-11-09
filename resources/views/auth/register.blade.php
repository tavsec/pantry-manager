@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Sign in
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There were errors with your submission
                            </h3>
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

            <div class="space-y-4 rounded-md shadow-sm">
                <div>
                    <x-text-input
                        name="name"
                        type="text"
                        label="Full Name"
                        placeholder="John Doe"
                        autocomplete="name"
                        required
                    />
                </div>

                <div>
                    <x-text-input
                        name="email"
                        type="email"
                        label="Email address"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                    />
                </div>

                <div>
                    <x-text-input
                        name="password"
                        type="password"
                        label="Password"
                        placeholder="Minimum 8 characters"
                        autocomplete="new-password"
                        required
                    />
                </div>

                <div>
                    <x-text-input
                        name="password_confirmation"
                        type="password"
                        label="Confirm Password"
                        placeholder="Re-enter your password"
                        autocomplete="new-password"
                        required
                    />
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="group relative flex w-full justify-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Create account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
