@extends('custom.layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 bg-gray-50">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <div class="mb-6">
            <div class="mx-auto h-16 w-16 text-green-500">
                <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            Verify your email
        </h1>

        <p class="text-gray-600 mb-6">
            We've sent a verification link to your email address.
            Please check your inbox and click the link to verify your account.
        </p>

        <div class="space-y-4">
            <a href="{{ route('filament.admin.auth.login') }}"
               class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Go to Login
            </a>
        </div>
    </div>
</div>
@endsection
