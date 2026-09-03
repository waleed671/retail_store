@extends('layouts.app')
@section('title', 'Create Account — '.config('app.name'))
@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md anim-up">

        <div class="glass-card rounded-3xl p-8 shadow-2xl shadow-indigo-100/60">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200"
                     style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
            </div>

            <h1 class="text-2xl font-black text-gray-900 text-center mb-1">Create Account</h1>
            <p class="text-sm text-gray-400 text-center mb-8">Join thousands of happy shoppers</p>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="Your full name">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="03xx-xxxxxxx">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Password</label>
                        <input type="password" name="password" required
                               class="input-cyber w-full px-4 py-3 text-sm" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Confirm</label>
                        <input type="password" name="password_confirmation" required
                               class="input-cyber w-full px-4 py-3 text-sm" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full py-3.5 text-sm mt-2">Create Account</button>
            </form>

            <div class="cyber-divider my-6"></div>
            <p class="text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors ml-1">Sign in →</a>
            </p>
        </div>

    </div>
</div>
@endsection
