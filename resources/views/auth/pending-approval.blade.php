@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 sm:py-16 lg:py-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-center">
                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-yellow-100">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-5 text-center">
                    <h3 class="text-2xl font-medium text-gray-900">{{ __('Compte en attente d\'approbation') }}</h3>
                    <div class="mt-4">
                        <p class="text-gray-600">{{ __('Merci pour votre inscription! Votre compte est actuellement en attente d\'approbation par nos administrateurs.') }}</p>
                        <p class="mt-2 text-gray-600">{{ __('Vous recevrez une notification par email une fois que votre compte aura été approuvé.') }}</p>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="text-center">
                        <h4 class="text-lg font-medium text-gray-900">{{ __('Qu\'est-ce qui se passe ensuite?') }}</h4>
                        <ul class="mt-4 space-y-3">
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Nos administrateurs examineront votre inscription.') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Vous recevrez une notification par email concernant le statut de votre compte.') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="h-6 w-6 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 flex-shrink-0 mr-3">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-gray-600 text-left">{{ __('Une fois approuvé, vous pouvez vous connecter et accéder à toutes les fonctionnalités.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        {{ __('Retour à la page de connexion') }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-gray-500 text-sm">
                {{ __('Si vous avez des questions, veuillez contacter notre équipe de support.') }}
            </p>
        </div>
    </div>
</div>
@endsection 