@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto mt-16">
    <h1 class="text-2xl font-bold mb-6">Sign in to TaskForge</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4 bg-white p-6 rounded-lg shadow">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            Sign In
        </button>
    </form>
    <p class="mt-4 text-center text-sm text-gray-600">
        No account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
    </p>
</div>
@endsection
