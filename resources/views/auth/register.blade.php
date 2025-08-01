@extends('layouts.main')

@section('content')
    <!-- Register Form Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gray-900 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">{{ __('Inscription Candidat') }}</h2>
                    <p class="text-sm text-gray-300 mt-1">{{ __('Créez votre compte candidat pour accéder aux cours de conduite') }}</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('auth.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.password') }}</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('auth.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                        </div>

                        <!-- School Selection -->
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700">{{ __('Sélectionner une école') }}</label>
                            <select name="school_id" id="school_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 @error('school_id') border-red-300 @enderror">
                                <option value="" disabled selected>{{ __('Choisissez votre école') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Note: Votre inscription sera en attente d\'approbation. L\'approbation finale dépend de la capacité de l\'école et de l\'examen de l\'administrateur.') }}</p>
                            @error('school_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms of Service -->
                        <div class="flex items-center">
                            <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-500 @error('terms') border-red-300 @enderror">
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                {{ __('auth.agree_terms') }}
                                <a href="{{ route('terms') }}" class="text-yellow-600 hover:text-yellow-500" target="_blank">{{ __('auth.terms') }}</a>
                                {{ __('auth.and') }}
                                <a href="{{ route('privacy') }}" class="text-yellow-600 hover:text-yellow-500" target="_blank">{{ __('auth.privacy') }}</a>
                            </label>
                            @error('terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-900 bg-yellow-500 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-300">
                                {{ __('auth.register_button') }}
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 text-center text-sm">
                        <p class="text-gray-600">
                            {{ __('auth.have_account') }}
                            <a href="{{ route('login') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                                {{ __('auth.login_now') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
