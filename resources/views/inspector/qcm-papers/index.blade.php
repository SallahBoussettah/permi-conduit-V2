@extends('layouts.main')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Papiers QCM') }}</h1>
                    <a href="{{ route('inspector.qcm-papers.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Créer un papier QCM') }}
                    </a>
                </div>
                <p class="mt-2 text-sm text-gray-700">
                    {{ __('Créer et gérer des papiers QCM pour vos catégories de permis.') }}</p>
            </div>

            <!-- Status Messages -->
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6">
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
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Papers List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                @if($papers->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($papers as $paper)
                            <li>
                                <div class="block hover:bg-gray-50">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full {{ $paper->status ? 'bg-green-100' : 'bg-red-100' }}">
                                                    <svg class="h-6 w-6 {{ $paper->status ? 'text-green-600' : 'text-red-600' }}"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <a href="{{ route('inspector.qcm-papers.show', $paper) }}"
                                                        class="text-lg font-medium text-indigo-600 hover:text-indigo-900 truncate">{{ $paper->title }}</a>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $paper->permitCategory->name ?? 'Aucune catégorie' }} ·
                                                        {{ $paper->questions()->count() }} questions
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paper->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $paper->status ? __('Actif') : __('Inactif') }}
                                                </span>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('inspector.qcm-papers.questions.index', $paper) }}"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        {{ __('Questions') }}
                                                    </a>
                                                    <a href="{{ route('inspector.qcm-papers.edit', $paper) }}"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                        {{ __('Modifier') }}
                                                    </a>
                                                    <form action="{{ route('inspector.qcm-papers.destroy', $paper) }}" method="POST"
                                                        class="inline delete-paper-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="delete-paper-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                            data-paper-title="{{ $paper->title }}"
                                                            data-questions-count="{{ $paper->questions()->count() }}"
                                                            data-exams-count="{{ $paper->exams()->count() }}">
                                                            {{ __('Supprimer') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    {{ Str::limit($paper->description, 100) ?? __('Aucune description') }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p>
                                                    {{ __('Créé le') }} {{ $paper->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-4 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('Aucun questionnaire QCM trouvé') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Commencez par créer un nouveau questionnaire QCM.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('inspector.qcm-papers.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Créer un questionnaire QCM') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $papers->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Paper Confirmation Modal -->
    <div id="delete-paper-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('Supprimer le papier QCM') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('Êtes-vous sûr de vouloir supprimer ce papier QCM ?') }}
                                </p>
                                <p class="text-sm text-gray-700 font-medium mt-2" id="paper-preview">
                                    <!-- Paper title will be inserted here -->
                                </p>
                                <div class="mt-3 p-3 bg-red-50 rounded-md">
                                    <div class="text-sm text-red-800">
                                        <p class="font-medium">{{ __('Cette action supprimera définitivement :') }}</p>
                                        <ul class="mt-2 list-disc list-inside space-y-1">
                                            <li id="questions-info"><!-- Questions count will be inserted here --></li>
                                            <li id="exams-info"><!-- Exams count will be inserted here --></li>
                                            <li>{{ __('Toutes les réponses et données associées') }}</li>
                                            <li>{{ __('Toutes les images de questions') }}</li>
                                        </ul>
                                        <p class="mt-2 font-medium text-red-900">
                                            {{ __('Cette action ne peut pas être annulée !') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete-paper"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Supprimer définitivement') }}
                    </button>
                    <button type="button" id="cancel-delete-paper"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Annuler') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let currentFormToSubmit = null;

                // Handle delete paper button clicks
                document.querySelectorAll('.delete-paper-btn').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();

                        const form = this.closest('.delete-paper-form');
                        const paperTitle = this.getAttribute('data-paper-title');
                        const questionsCount = parseInt(this.getAttribute('data-questions-count'));
                        const examsCount = parseInt(this.getAttribute('data-exams-count'));

                        currentFormToSubmit = form;

                        // Update modal content
                        document.getElementById('paper-preview').textContent = '"' + paperTitle + '"';
                        document.getElementById('questions-info').textContent = questionsCount + ' question(s) et leurs réponses';
                        document.getElementById('exams-info').textContent = examsCount + ' examen(s) et leurs résultats';

                        // Show modal
                        document.getElementById('delete-paper-modal').classList.remove('hidden');
                    });
                });

                // Handle confirm delete
                document.getElementById('confirm-delete-paper').addEventListener('click', function () {
                    if (currentFormToSubmit) {
                        currentFormToSubmit.submit();
                    }
                });

                // Handle cancel delete
                document.getElementById('cancel-delete-paper').addEventListener('click', function () {
                    currentFormToSubmit = null;
                    document.getElementById('delete-paper-modal').classList.add('hidden');
                });

                // Close modal when clicking outside
                document.getElementById('delete-paper-modal').addEventListener('click', function (e) {
                    if (e.target === this) {
                        currentFormToSubmit = null;
                        this.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush

@endsection