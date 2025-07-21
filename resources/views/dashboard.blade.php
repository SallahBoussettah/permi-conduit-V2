@extends('layouts.main')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            <!-- Welcome Banner -->
            <div class="bg-gray-900 rounded-xl shadow-xl overflow-hidden mb-8 relative">
                <!-- Background pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                        <defs>
                            <pattern id="smallGrid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#smallGrid)" />
                    </svg>
                </div>

                <div class="flex justify-between items-center relative z-10">
                    <div class="p-8">
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500 text-gray-900">
                            @if(Auth::user()->role)
                                {{ Auth::user()->role->name }}
                            @else
                                {{ __('User') }}
                            @endif
                        </div>
                        <h1 class="mt-3 text-3xl leading-8 font-extrabold tracking-tight text-white">
                            {{ __('Bienvenue') }}, {{ Auth::user()->name }}!
                        </h1>
                        <p class="mt-3 max-w-md text-gray-300">
                            {{ __('Accédez à votre tableau de bord personnalisé pour gérer vos activités et ressources.') }}
                        </p>
                    </div>
                    <div class="flex justify-center p-8">
                        <div
                            class="bg-yellow-500 rounded-full p-4 shadow-lg transform transition-transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-900" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                @if(Auth::user()->role && Auth::user()->role->name === 'admin')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @elseif(Auth::user()->role && Auth::user()->role->name === 'inspector')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                @elseif(Auth::user()->role && Auth::user()->role->name === 'super_admin')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                @endif
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Decorative element -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-yellow-500">
                </div>
            </div>

            @if(!Auth::user()->role)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ __('Votre rôle n\'a pas été assigné. Veuillez contacter un administrateur.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dashboard Content -->
            @if(Auth::user()->role)
                @if(Auth::user()->role->name === 'super_admin')
                    <!-- Super Admin Redirect -->
                    <script>
                        window.location.href = "{{ route('super_admin.dashboard') }}";
                    </script>
                    <div class="p-8 text-center">
                        <p>{{ __('Redirection vers le tableau de bord de l\'administrateur...') }}</p>
                        <p class="mt-4">
                            <a href="{{ route('super_admin.dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cliquez ici si vous n\'êtes pas redirigé') }}
                            </a>
                        </p>
                    </div>
                @elseif(Auth::user()->role->name === 'admin')
                    <!-- Admin Dashboard -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Administration') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <a href="{{ route('admin.users.index') }}" class="block">
                                        <div class="p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-5">
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        {{ __('Gestion des utilisateurs') }}
                                                    </h3>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ __('Gérer tous les utilisateurs du système.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-6">
                                                <a href="{{ route('admin.users.index') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    {{ __('Gérer les utilisateurs') }}
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Permit Categories Card -->
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <a href="{{ route('admin.permit-categories.index') }}" class="block">
                                        <div class="p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-5">
                                                    <h3 class="text-lg font-medium text-gray-900">{{ __('Catégories de permis') }}
                                                    </h3>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ __('Gérer les catégories de permis de conduire (C, CE, D, etc.).') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-6">
                                                <a href="{{ route('admin.permit-categories.index') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    {{ __('Gérer les catégories de permis') }}
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-lg font-medium text-gray-900">{{ __('Gestion des inspecteurs') }}
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ __('Créer et gérer les comptes d\'inspecteurs.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.inspectors') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                {{ __('Gérer les inspecteurs') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QCM Reports Section -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Module QCM') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-lg font-medium text-gray-900">{{ __('Rapports QCM') }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ __('Voir les résultats des examens des candidats et les statistiques.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.qcm-reports.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                {{ __('Voir les rapports') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-lg font-medium text-gray-900">{{ __('Performance des candidats') }}
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ __('Voir l\'historique des examens des candidats.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.qcm-reports.candidates') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Voir les candidats') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-lg font-medium text-gray-900">{{ __('Export des données') }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ __('Exporter les résultats des examens QCM pour la création de rapports.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.qcm-reports.export') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                {{ __('Exporter les rapports') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Support Section -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Support Client') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-lg font-medium text-gray-900">{{ __('FAQ Chat IA') }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ __('Gérer les questions fréquentes pour le chat IA.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.ai-chat-faqs.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ __('Gérer les FAQ') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-lg font-medium text-gray-900">{{ __('Conversations') }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ __('Voir toutes les conversations des candidats avec le support.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.chat-conversations.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                {{ __('Voir les conversations') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(Auth::user()->role->name === 'inspector')
                    <!-- Inspector Dashboard -->
                    <div class="space-y-8">
                        <!-- Statistics Overview -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Tableau de bord de l\'inspecteur') }}</h2>
                                <p class="text-gray-600 mb-6">
                                    {{ __('Bienvenue dans votre espace de travail. Voici un aperçu de vos activités.') }}
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <!-- Total Courses -->
                                    <div class="bg-white rounded-lg p-4 border border-indigo-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-indigo-700 font-semibold">{{ __('Cours créés') }}</p>
                                                <h3 class="text-3xl font-bold mt-1 text-indigo-700">
                                                    {{ \App\Models\Course::where('created_by', Auth::id())->count() }}
                                                </h3>
                                            </div>
                                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('inspector.courses.index') }}"
                                                class="text-xs text-indigo-900 font-medium flex items-center">
                                                {{ __('Voir tous les cours') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Total QCM Papers -->
                                    <div class="bg-white rounded-lg p-4 border border-yellow-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-yellow-700 font-semibold">{{ __('QCM créés') }}</p>
                                                <h3 class="text-3xl font-bold mt-1 text-yellow-700">
                                                    {{ \App\Models\QcmPaper::where('created_by', Auth::id())->count() }}
                                                </h3>
                                            </div>
                                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('inspector.qcm-papers.index') }}"
                                                class="text-xs text-yellow-900 font-medium flex items-center">
                                                {{ __('Voir tous les QCM') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Candidate Count -->
                                    <div class="bg-white rounded-lg p-4 border border-green-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-green-700 font-semibold">{{ __('Candidats actifs') }}</p>
                                                <h3 class="text-3xl font-bold mt-1 text-green-700">
                                                    {{ \App\Models\User::whereHas('role', function ($q) {
                        $q->where('name', 'candidate'); })->where('is_active', true)->count() }}
                                                </h3>
                                            </div>
                                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="#" class="text-xs text-green-900 font-medium flex items-center">
                                                {{ __('Voir les statistiques') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Messages Count -->
                                    <div class="bg-white rounded-lg p-4 border border-blue-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-blue-700 font-semibold">{{ __('Messages non lus') }}</p>
                                                <h3 class="text-3xl font-bold mt-1 text-blue-700">
                                                    0
                                                </h3>
                                            </div>
                                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('inspector.chat.index') }}"
                                                class="text-xs text-blue-900 font-medium flex items-center">
                                                {{ __('Voir les messages') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-xl font-bold text-gray-900">{{ __('Actions rapides') }}</h2>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('inspector.courses.create') }}"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            {{ __('Nouveau cours') }}
                                        </a>
                                        <a href="{{ route('inspector.qcm-papers.create') }}"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            {{ __('Nouveau QCM') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <a href="{{ route('inspector.courses.index') }}"
                                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('Gérer les cours') }}</h3>
                                        </div>
                                    </a>

                                    <a href="{{ route('inspector.qcm-papers.index') }}"
                                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex-shrink-0 bg-yellow-100 rounded-full p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('Gérer les QCM') }}</h3>
                                        </div>
                                    </a>

                                    <a href="{{ route('inspector.permit-categories.index') }}"
                                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex-shrink-0 bg-purple-100 rounded-full p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('Catégories de permis') }}</h3>
                                        </div>
                                    </a>

                                    <a href="{{ route('inspector.chat.index') }}"
                                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">{{ __('Support par chat') }}</h3>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content Sections -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Recent Courses -->
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden lg:col-span-2">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-xl font-bold text-gray-900">{{ __('Cours récents') }}</h2>
                                        <a href="{{ route('inspector.courses.index') }}"
                                            class="text-sm text-indigo-600 hover:text-indigo-800">{{ __('Voir tout') }}</a>
                                    </div>

                                    <div class="overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Titre') }}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Catégorie') }}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Date') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach(\App\Models\Course::where('created_by', Auth::id())->latest()->take(5)->get() as $course)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">
                                                                {{ $course->permitCategory->name ?? 'N/A' }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $course->created_at->format('d/m/Y') }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @if(\App\Models\Course::where('created_by', Auth::id())->count() == 0)
                                                    <tr>
                                                        <td colspan="3"
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                            {{ __('Aucun cours créé pour le moment.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Messages -->
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-xl font-bold text-gray-900">{{ __('Messages récents') }}</h2>
                                        <a href="{{ route('inspector.chat.index') }}"
                                            class="text-sm text-blue-600 hover:text-blue-800">{{ __('Voir tout') }}</a>
                                    </div>

                                    <div class="space-y-4">
                                        <!-- Messages will be displayed here -->
                                        <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-700">S</span>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Système</p>
                                                <p class="text-sm text-gray-600 truncate">Bienvenue dans votre espace de chat.</p>
                                                <p class="text-xs text-gray-500 mt-1">Aujourd'hui</p>
                                            </div>
                                        </div>

                                        @if(true)
                                            <div class="text-center py-4 text-gray-500">
                                                {{ __('Aucun message récent.') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tools Section -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Outils de l\'inspecteur') }}</h2>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div
                                        class="bg-gradient-to-br from-indigo-50 to-indigo-100 overflow-hidden shadow-md rounded-lg hover:shadow-xl transition-shadow duration-300 border border-indigo-200">
                                        <div class="p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                                <div class="ml-5">
                                                    <h3 class="text-lg font-medium text-gray-900">{{ __('Gestion des cours') }}</h3>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ __('Créer et gérer des supports de cours.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-6">
                                                <a href="{{ route('inspector.courses.index') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Gérer les cours') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- QCM Papers Card -->
                                    <div
                                        class="bg-gradient-to-br from-yellow-50 to-yellow-100 overflow-hidden shadow-md rounded-lg hover:shadow-xl transition-shadow duration-300 border border-yellow-200">
                                        <div class="p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                    </svg>
                                                </div>
                                                <div class="ml-5">
                                                    <h3 class="text-lg font-medium text-gray-900">{{ __('Gestion du QCM') }}</h3>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ __('Créer et gérer des QCM et des questions.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-6">
                                                <a href="{{ route('inspector.qcm-papers.index') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                    {{ __('Gérer les QCM') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permit Categories Card -->
                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-purple-100 overflow-hidden shadow-md rounded-lg hover:shadow-xl transition-shadow duration-300 border border-purple-200">
                                        <div class="p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-5">
                                                    <h3 class="text-lg font-medium text-gray-900">{{ __('Catégories de permis') }}
                                                    </h3>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ __('Gérer les catégories de permis de conduire (C, CE, D, etc.).') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-6">
                                                <a href="{{ route('inspector.permit-categories.index') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    {{ __('Gérer les catégories de permis') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Candidate Dashboard -->
                    <div class="space-y-8">
                        <!-- Stats Overview -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Aperçu de votre progression') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- Course Progress -->
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-purple-500 rounded-full p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-3xl font-bold text-gray-900">
                                                    {{ Auth::user()->completedMaterials()->count() }}
                                                </div>
                                                <div class="text-sm font-medium text-gray-500">{{ __('Matériels complétés') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QCM Exams Taken -->
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-yellow-500 rounded-full p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-3xl font-bold text-gray-900">
                                                    {{ Auth::user()->qcmExams()->count() }}
                                                </div>
                                                <div class="text-sm font-medium text-gray-500">{{ __('Examens passés') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QCM Success Rate -->
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-green-500 rounded-full p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                @php
                                                    $totalExams = Auth::user()->qcmExams()->where('status', '!=', 'in_progress')->count();
                                                    $passedExams = Auth::user()->qcmExams()->where('status', '!=', 'in_progress')->where('is_eliminatory', false)->count();
                                                    $successRate = $totalExams > 0 ? round(($passedExams / $totalExams) * 100) : 0;
                                                @endphp
                                                <div class="text-3xl font-bold text-gray-900">
                                                    {{ $successRate }}%
                                                </div>
                                                <div class="text-sm font-medium text-gray-500">{{ __('Taux de réussite') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permit Categories -->
                                <div
                                    class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-blue-500 rounded-full p-3">
                                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-3xl font-bold text-gray-900">
                                                    {{ Auth::user()->permitCategories->count() }}
                                                </div>
                                                <div class="text-sm font-medium text-gray-500">{{ __('Catégories de permis') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Recent Exams -->
                            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                                <div class="px-6 py-5 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-medium text-gray-900">{{ __('Examens récents') }}</h3>
                                        <a href="{{ route('candidate.qcm-exams.index') }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ __('Voir tout') }} →
                                        </a>
                                    </div>
                                </div>
                                <div class="px-6 py-5">
                                    @php
                                        $recentExams = Auth::user()->qcmExams()->with('paper.permitCategory')->orderBy('created_at', 'desc')->take(3)->get();
                                    @endphp

                                    @if($recentExams->count() > 0)
                                        <ul class="divide-y divide-gray-200">
                                            @foreach($recentExams as $exam)
                                                <li class="py-4">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0">
                                                                @if($exam->status === 'in_progress')
                                                                    <span
                                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100">
                                                                        <span class="text-xs font-medium text-yellow-800">{{ __('IP') }}</span>
                                                                    </span>
                                                                @elseif(!$exam->is_eliminatory)
                                                                    <span
                                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                                                        <span class="text-xs font-medium text-green-800">{{ __('P') }}</span>
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                                                                        <span class="text-xs font-medium text-red-800">{{ __('E') }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="ml-4">
                                                                <p class="text-sm font-medium text-gray-900">{{ $exam->paper->title }}</p>
                                                                <p class="text-xs text-gray-500">{{ $exam->paper->permitCategory->name }} •
                                                                    {{ $exam->created_at->format('d/m/Y') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            @if($exam->status === 'in_progress')
                                                                <a href="{{ route('candidate.qcm-exams.show', $exam) }}"
                                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                                    {{ __('Continuer') }}
                                                                </a>
                                                            @else
                                                                <a href="{{ route('candidate.qcm-exams.results', $exam) }}"
                                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                    {{ __('Résultats') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-sm text-gray-500">{{ __('Vous n\'avez pas encore passé d\'examen.') }}</p>
                                            <a href="{{ route('candidate.qcm-exams.available') }}"
                                                class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Passer un examen') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Course Progress -->
                            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                                <div class="px-6 py-5 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-medium text-gray-900">{{ __('Progression des cours') }}</h3>
                                        <a href="{{ route('candidate.courses.index') }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ __('Voir tout') }} →
                                        </a>
                                    </div>
                                </div>
                                <div class="px-6 py-5">
                                    @php
                                        $courseCompletions = DB::table('user_course_completions')
                                            ->join('courses', 'user_course_completions.course_id', '=', 'courses.id')
                                            ->where('user_course_completions.user_id', Auth::id())
                                            ->select('courses.title', 'user_course_completions.progress_percentage', 'user_course_completions.completed_at')
                                            ->orderBy('user_course_completions.updated_at', 'desc')
                                            ->take(3)
                                            ->get();
                                    @endphp

                                    @if($courseCompletions->count() > 0)
                                        <ul class="space-y-4">
                                            @foreach($courseCompletions as $completion)
                                                <li>
                                                    <div>
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm font-medium text-gray-900">{{ $completion->title }}</p>
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $completion->progress_percentage }}%
                                                            </p>
                                                        </div>
                                                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                                            <div class="bg-indigo-600 h-2.5 rounded-full"
                                                                style="width: {{ $completion->progress_percentage }}%"></div>
                                                        </div>
                                                        @if($completion->completed_at)
                                                            <p class="mt-1 text-xs text-gray-500">{{ __('Complété le') }}
                                                                {{ \Carbon\Carbon::parse($completion->completed_at)->format('d/m/Y') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-sm text-gray-500">{{ __('Vous n\'avez pas encore commencé de cours.') }}</p>
                                            <a href="{{ route('candidate.courses.index') }}"
                                                class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Explorer les cours') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Quick Access -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Accès rapide') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- QCM Exams Card -->
                                <div
                                    class="bg-gradient-to-br from-yellow-500 to-yellow-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-white bg-opacity-80 rounded-md p-3">
                                                <svg class="h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-xl font-bold text-gray-900">{{ __('Examens QCM') }}</h3>
                                                <p class="mt-1 text-sm text-gray-700">
                                                    {{ __('Passer des examens à choix multiple et voir les résultats.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex space-x-3">
                                            <a href="{{ route('candidate.qcm-exams.available', ['random_challenge' => true]) }}"
                                                class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md shadow-sm text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-300">
                                                {{ __('Mode Challenge') }}
                                            </a>
                                            <a href="{{ route('candidate.qcm-exams.available', ['show_all' => true]) }}"
                                                class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md shadow-sm text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-300">
                                                {{ __('Voir tous les examens') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Courses Card -->
                                <div
                                    class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-white bg-opacity-80 rounded-md p-3">
                                                <svg class="h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-xl font-bold text-gray-900">{{ __('Mes cours') }}</h3>
                                                <p class="mt-1 text-sm text-gray-700">
                                                    {{ __('Accéder à vos supports de cours et continuer votre apprentissage.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex space-x-3">
                                            <a href="{{ route('candidate.courses.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-purple-300 text-sm font-medium rounded-md shadow-sm text-purple-800 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-300">
                                                {{ __('Voir les cours') }}
                                            </a>
                                            <a href="{{ route('candidate.courses.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-purple-300 text-sm font-medium rounded-md shadow-sm text-purple-800 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-300">
                                                {{ __('Continuer l\'apprentissage') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Support Card -->
                                <div
                                    class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-white bg-opacity-80 rounded-md p-3">
                                                <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5">
                                                <h3 class="text-xl font-bold text-gray-900">{{ __('Support par chat') }}</h3>
                                                <p class="mt-1 text-sm text-gray-700">
                                                    {{ __('Besoin d\'aide ? Discutez avec notre assistant IA ou un inspecteur.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex space-x-3">
                                            <a href="{{ route('candidate.chat.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md shadow-sm text-blue-800 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300">
                                                {{ __('Accéder au chat') }}
                                            </a>
                                            <a href="{{ route('candidate.chat.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md shadow-sm text-blue-800 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300">
                                                {{ __('Poser une question') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Section -->
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Mon profil') }}</h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-5">
                                        <h4 class="text-xl font-medium text-gray-900">{{ Auth::user()->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                        <div class="mt-2">
                                            @foreach(Auth::user()->permitCategories as $category)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2 mb-2">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        <a href="{{ route('profile.edit') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            {{ __('Modifier le profil') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection