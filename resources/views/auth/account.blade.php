@extends('layouts.app')
@section('title', 'My Account — '.config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 pb-14">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8 anim-up">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-200"
             style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-2xl font-black text-gray-900">My Account</h1>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Profile --}}
        <div class="glass-card rounded-2xl p-6 anim-up d1">
            <h2 class="font-black text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile Details
            </h2>
            <form action="{{ route('account.update') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                @foreach([
                    ['text','name','Full Name',$user->name,'Your full name'],
                    ['email','email','Email Address',$user->email,'you@example.com'],
                    ['text','phone','Phone Number',$user->phone,'03xx-xxxxxxx'],
                    ['text','city','City',$user->city,'Karachi, Lahore…'],
                ] as [$type,$name,$label,$val,$ph])
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $val) }}"
                           {{ in_array($name,['name','email']) ? 'required' : '' }}
                           class="input-cyber w-full px-4 py-2.5 text-sm" placeholder="{{ $ph }}">
                </div>
                @endforeach
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Address</label>
                    <textarea name="address" rows="2" class="input-cyber w-full px-4 py-2.5 text-sm resize-none"
                              placeholder="Street, area…">{{ old('address', $user->address) }}</textarea>
                </div>
                <button class="btn-primary w-full py-2.5 text-sm">Save Changes</button>
            </form>
        </div>

        {{-- Password --}}
        <div class="glass-card rounded-2xl p-6 h-fit anim-up d2">
            <h2 class="font-black text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Change Password
            </h2>
            <form action="{{ route('account.password') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                @foreach([
                    ['current_password','Current Password'],
                    ['password','New Password'],
                    ['password_confirmation','Confirm New Password'],
                ] as [$name,$label])
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ $label }}</label>
                    <input type="password" name="{{ $name }}" required
                           class="input-cyber w-full px-4 py-2.5 text-sm" placeholder="••••••••">
                </div>
                @endforeach
                <button class="btn-primary w-full py-2.5 text-sm">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
