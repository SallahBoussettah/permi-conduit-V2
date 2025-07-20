@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 sm:py-16 lg:py-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-center">
                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-red-100">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-5 text-center">
                    <h3 class="text-2xl font-medium text-gray-900">{{ __('Compte inactif') }}</h3>
                    <div class="mt-4">
                        <p class="text-gray-600">{{ __('Votre compte a été désactivé.') }}</p>
                        
                        @if(auth()->user() && auth()->user()->hasExpired())
                        <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-md">
                            <p class="text-red-700 font-medium">{{ __('Votre compte a expiré') }}</p>
                            <p class="mt-1 text-red-600">
                                {{ __('Votre accès a expiré le') }} {{ auth()->user()->expires_at->format('Y-m-d') }}.
                            </p>
                        </div>
                        @endif
                        
                        <p class="mt-2 text-gray-600">{{ __('Veuillez contacter un administrateur si vous croyez que cela a été fait par erreur.') }}</p>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="text-center">
                        <h4 class="text-lg font-medium text-gray-900">{{ __('Que signifie cela?') }}</h4>
                        <ul class="mt-4 space-y-3">
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-red-100 text-red-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Vous ne pouvez pas accéder à votre compte pendant qu\'il est inactif.') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-red-100 text-red-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Votre compte peut avoir expiré si elle a été approuvée avec une date limite.') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-red-100 text-red-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Cela peut être dû à une violation de nos conditions d\'utilisation ou d\'autres questions de politique.') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-red-100 text-red-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Contactez un administrateur pour plus d\'informations et pour demander la réactivation ou une extension.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        {{ __('Retour à la page de connexion') }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-gray-500 text-sm">
                {{ __('Si vous croyez que c\'est une erreur, veuillez contacter notre équipe de support.') }}
            </p>
        </div>
    </div>
</div>
@endsection 