@extends('layouts.main')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('inspector.qcm-papers.show', $qcmPaper) }}" class="mr-4 text-indigo-600 hover:text-indigo-900">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Questions du QCM') }}</h1>
                </div>
                
                <div class="flex space-x-3">
                    
                    <a href="{{ route('inspector.qcm-papers.questions.create', $qcmPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Ajouter une question') }}
                    </a>
                    
                    <a href="{{ route('inspector.qcm-papers.questions.import', $qcmPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        {{ __('Importer des questions') }}
                    </a>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-700">{{ __('Gérer les questions pour le QCM:') }} {{ $qcmPaper->title }}</p>
        </div>

        <!-- Status Messages -->
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Questions List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            @php
                $allQuestionsCount = 0;
                foreach ($sections as $section) {
                    $allQuestionsCount += count($section->questions);
                }
            @endphp
            
            @if($allQuestionsCount > 0)
                <!-- Questions List by Section -->
                @foreach($sections as $section)
                    @if(count($section->questions) > 0)
                        <div class="border-b border-gray-200">
                            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ $section->title ?? 'All Questions' }}
                                </h3>
                                @if($section->description)
                                    <p class="mt-1 text-sm text-gray-500">{{ $section->description }}</p>
                                @endif
                            </div>
                            
                            <ul class="divide-y divide-gray-200">
                                @foreach($section->questions as $question)
                                    <li>
                                        <div class="block hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                                                            <span class="text-lg font-medium">{{ $question->sequence_number }}</span>
                                                        </div>
                                                        <div class="ml-4 flex-grow">
                                                            <div class="flex items-center">
                                                                <div class="text-md font-medium text-gray-900">
                                                                    {{ Str::limit($question->question_text, 80) }}
                                                                </div>
                                                                @if($question->image_path)
                                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                        </svg>
                                                                        {{ __('Panneau') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="mt-1 text-sm text-gray-500">
                                                                {{ __('Type:') }} 
                                                                <span class="font-medium">
                                                                    @if($question->question_type == 'multiple_choice')
                                                                        {{ __('Choix multiple') }}
                                                                    @elseif($question->question_type == 'yes_no')
                                                                        {{ __('Oui/Non') }}
                                                                    @endif
                                                                </span>
                                                                | {{ __('Réponses:') }} {{ count($question->answers) }}
                                                                @if($question->image_path)
                                                                    <span class="ml-2 text-blue-600">
                                                                        | {{ __('Avec image') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('inspector.qcm-papers.questions.edit', ['qcmPaper' => $qcmPaper->id, 'question' => $question->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                            {{ __('Modifier') }}
                                                        </a>
                                                        <form action="{{ route('inspector.qcm-papers.questions.destroy', ['qcmPaper' => $qcmPaper->id, 'question' => $question->id]) }}" method="POST" class="inline delete-question-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="delete-question-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-question-text="{{ Str::limit($question->question_text, 50) }}">
                                                                {{ __('Supprimer') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('Aucune question trouvée') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Commencez par créer une nouvelle question.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('inspector.qcm-papers.questions.create', $qcmPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Créer une question') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Question Confirmation Modal -->
<div id="delete-question-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ __('Supprimer la question') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ __('Êtes-vous sûr de vouloir supprimer cette question ?') }}
                            </p>
                            <p class="text-sm text-gray-700 font-medium mt-2" id="question-preview">
                                <!-- Question text will be inserted here -->
                            </p>
                            <p class="text-sm text-red-600 mt-2">
                                {{ __('Cette action ne peut pas être annulée et supprimera également toutes les réponses associées.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirm-delete-question" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Supprimer') }}
                </button>
                <button type="button" id="cancel-delete-question" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Annuler') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentFormToSubmit = null;
    
    // Handle delete question button clicks
    document.querySelectorAll('.delete-question-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('.delete-question-form');
            const questionText = this.getAttribute('data-question-text');
            
            currentFormToSubmit = form;
            
            // Update modal content
            document.getElementById('question-preview').textContent = '"' + questionText + '"';
            
            // Show modal
            document.getElementById('delete-question-modal').classList.remove('hidden');
        });
    });
    
    // Handle confirm delete
    document.getElementById('confirm-delete-question').addEventListener('click', function() {
        if (currentFormToSubmit) {
            currentFormToSubmit.submit();
        }
    });
    
    // Handle cancel delete
    document.getElementById('cancel-delete-question').addEventListener('click', function() {
        currentFormToSubmit = null;
        document.getElementById('delete-question-modal').classList.add('hidden');
    });
    
    // Close modal when clicking outside
    document.getElementById('delete-question-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            currentFormToSubmit = null;
            this.classList.add('hidden');
        }
    });
});
</script>
@endpush

@endsection 