@extends('layouts.app')
@section('title', 'Login — '.config('app.name'))
@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md anim-up">

        {{-- Card --}}
        <div class="glass-card rounded-3xl p-8 shadow-2xl shadow-indigo-100/60">
            {{-- Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200"
                     style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>

            <h1 class="text-2xl font-black text-gray-900 text-center mb-1">Welcome Back</h1>
            <p class="text-sm text-gray-400 text-center mb-8">Sign in to your account</p>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" required
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="••••••••">
                </div>
                <label class="flex items-center gap-2.5 text-sm text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 accent-indigo-600 rounded">
                    Remember me
                </label>
                <button type="submit" class="btn-primary w-full py-3.5 text-sm mt-2">Sign In</button>
            </form>

            <div class="cyber-divider my-6"></div>
            <p class="text-center text-sm text-gray-500">
                New here?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors ml-1">Create an account →</a>
            </p>
        </div>

    </div>
</div>
@endsection
