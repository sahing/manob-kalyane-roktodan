@extends('layouts.app')

@section('title', 'Edit Profile — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="glass-card p-8 rounded-2xl border border-slate-800 shadow-xl">
        <h1 class="text-2xl font-extrabold text-white mb-2">Edit Donor Profile</h1>
        <p class="text-xs text-slate-400 mb-6">Keep your contact details and availability status up-to-date.</p>

        <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Blood Group</label>
                    <select name="blood_group" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                            <option value="{{ $g }}" {{ ($user->donorProfile?->blood_group ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Availability Status</label>
                    <select name="availability_status" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                        <option value="available" {{ ($user->donorProfile?->availability_status ?? 'available') === 'available' ? 'selected' : '' }}>Available for Emergency Call</option>
                        <option value="unavailable" {{ ($user->donorProfile?->availability_status ?? '') === 'unavailable' ? 'selected' : '' }}>Currently Unavailable</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Donor Classification</label>
                    <select name="donor_type" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                        <option value="regular" {{ ($user->donorProfile?->donor_type ?? 'regular') === 'regular' ? 'selected' : '' }}>Regular Donor</option>
                        <option value="emergency" {{ ($user->donorProfile?->donor_type ?? '') === 'emergency' ? 'selected' : '' }}>Emergency On-Call Donor</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Village / Area</label>
                    <input type="text" name="village" value="{{ old('village', $user->donorProfile?->village) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Block</label>
                    <input type="text" name="block" value="{{ old('block', $user->donorProfile?->block ?? 'Bhagwangola-I') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">District</label>
                    <input type="text" name="district" value="{{ old('district', $user->donorProfile?->district ?? 'Murshidabad') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Last Donation Date</label>
                <input type="date" name="last_donation_date" value="{{ old('last_donation_date', $user->donorProfile?->last_donation_date?->format('Y-m-d')) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Medical Notes (Optional)</label>
                <textarea name="medical_notes" rows="2" placeholder="Any health allergies or blood pressure notes..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">{{ old('medical_notes', $user->donorProfile?->medical_notes) }}</textarea>
            </div>

            <div class="pt-4 flex items-center space-x-4">
                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-3 rounded-xl shadow-md transition">
                    Save Profile Changes
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl font-semibold text-sm bg-slate-800 text-slate-300 hover:bg-slate-700 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
