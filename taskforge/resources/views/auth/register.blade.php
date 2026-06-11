@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto mt-16">
    <h1 class="text-2xl font-bold mb-6">Create your account</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4 bg-white p-6 rounded-lg shadow">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            Register
        </button>
    </form>
    <p class="mt-4 text-center text-sm text-gray-600">
        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign in</a>
    </p>
</div>
@endsection
