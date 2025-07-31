@extends('layouts.main')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center">
                    <a href="{{ route('inspector.qcm-papers.show', $qcmPaper) }}"
                        class="mr-4 text-indigo-600 hover:text-indigo-900">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Modifier la question') }}</h1>
                </div>
                <p class="mt-2 text-sm text-gray-700">{{ __('Mettre à jour la question pour le QCM:') }}
                    {{ $qcmPaper->title }}</p>
            </div>

            <!-- QCM Grading Information -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Informations du QCM') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ __('Chaque QCM doit contenir exactement 10 questions.') }}</p>
                            <p class="mt-1">{{ __('Temps limite pour les candidats: 6 minutes') }}</p>
                            <p class="mt-1">{{ __('Échelle de notation:') }}</p>
                            <ul class="list-disc pl-5 space-y-1 mt-1">
                                <li>{{ __('9-10 réponses correctes: 3 points') }}</li>
                                <li>{{ __('7-8 réponses correctes: 2 points') }}</li>
                                <li>{{ __('6 réponses correctes: 1 point') }}</li>
                                <li>{{ __('5 réponses correctes: 0 point') }}</li>
                                <li>{{ __('Moins de 5 réponses correctes: Eliminatoire') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form
                        action="{{ route('inspector.qcm-papers.questions.update', ['qcmPaper' => $qcmPaper->id, 'question' => $question->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Question Text -->
                            <div>
                                <label for="question_text"
                                    class="block text-sm font-medium text-gray-700">{{ __('Texte de la question') }} <span
                                        class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <textarea name="question_text" id="question_text" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('question_text') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                        required>{{ old('question_text', $question->question_text) }}</textarea>
                                </div>
                                @error('question_text')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Question Type -->
                            <div>
                                <label for="question_type"
                                    class="block text-sm font-medium text-gray-700">{{ __('Type de question') }} <span
                                        class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <select id="question_type" name="question_type"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('question_type') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                        required>
                                        <option value="multiple_choice" {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>
                                            {{ __('Choix multiple') }}</option>
                                        <option value="yes_no" {{ old('question_type', $question->question_type) == 'yes_no' ? 'selected' : '' }}>{{ __('Oui/Non') }}</option>
                                    </select>
                                </div>
                                @error('question_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sequence Number (Hidden) -->
                            <input type="hidden" name="sequence_number" value="{{ $question->sequence_number }}">

                            <!-- Image Upload for Traffic Signs -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">
                                    <svg class="inline-block w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ __('Panneau de signalisation / Symbole') }}
                                </label>

                                @if($question->image_path)
                                    <div class="mt-3 p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span
                                                    class="inline-block px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                    {{ __('Image actuelle') }}
                                                </span>
                                            </div>
                                            <img src="{{ asset('storage/' . $question->image_path) }}"
                                                alt="Panneau de signalisation"
                                                class="mx-auto max-h-48 max-w-full rounded-lg shadow-md border border-gray-200">
                                            <div class="mt-3">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="remove_image"
                                                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                    <span
                                                        class="ml-2 text-sm text-red-600 font-medium">{{ __('Supprimer cette image') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <div
                                        class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                                viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>{{ __('Télécharger un panneau') }}</span>
                                                    <input id="image" name="image" type="file" class="sr-only"
                                                        accept="image/*" onchange="previewImage(this)">
                                                </label>
                                                <p class="pl-1">{{ __('ou glisser-déposer') }}</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ __('PNG, JPG, GIF jusqu\'à 2MB') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="image-preview" class="mt-3 hidden">
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="text-center">
                                            <div class="mb-2">
                                                <span
                                                    class="inline-block px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                    {{ __('Aperçu de la nouvelle image') }}
                                                </span>
                                            </div>
                                            <img id="preview-img" src="" alt="Aperçu"
                                                class="mx-auto max-h-48 max-w-full rounded-lg shadow-md border border-gray-200">
                                            <button type="button" onclick="clearImagePreview()"
                                                class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium">
                                                {{ __('Annuler') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <div class="mt-3 p-3 bg-blue-50 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                <strong>{{ __('Conseils pour les panneaux de signalisation:') }}</strong><br>
                                                • {{ __('Utilisez des images claires et nettes') }}<br>
                                                • {{ __('Préférez un fond blanc ou transparent') }}<br>
                                                • {{ __('Assurez-vous que le panneau est bien visible') }}<br>
                                                • {{ __('L\'image apparaîtra au-dessus de la question pendant l\'examen') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div> }}

                            <!-- Explanation -->
                            <div>
                                <label for="explanation"
                                    class="block text-sm font-medium text-gray-700">{{ __('Explication (Optionnel)') }}</label>
                                <div class="mt-1">
                                    <textarea name="explanation" id="explanation" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('explanation') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('explanation', $question->explanation) }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __('Fournir une explication pour la réponse correcte. Cela sera affiché aux candidats après avoir terminé l\'examen.') }}
                                </p>
                                @error('explanation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Answers -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Réponses') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('Ajouter au moins 2 réponses et marquer une comme correcte.') }}</p>

                                <div id="answers-container" class="mt-4 space-y-4">
                                    @foreach($question->answers as $index => $answer)
                                        <div class="answer-item p-4 border border-gray-200 rounded-md">
                                            <div class="flex items-center">
                                                <input type="radio" name="answers[{{ $index }}][is_correct]" value="1"
                                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ $answer->is_correct ? 'checked' : '' }}>
                                                <div class="ml-3 flex-grow">
                                                    <input type="hidden" name="answers[{{ $index }}][id]"
                                                        value="{{ $answer->id }}">
                                                    <input type="text" name="answers[{{ $index }}][text]"
                                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                        placeholder="{{ __('Texte de la réponse') }}"
                                                        value="{{ $answer->answer_text }}" required>
                                                </div>
                                                <button type="button"
                                                    class="remove-answer ml-3 text-red-600 hover:text-red-900" 
                                                    style="{{ count($question->answers) <= 2 ? 'display: none;' : '' }}">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4">
                                    <button type="button" id="add-answer"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ __('Ajouter une réponse') }}
                                    </button>
                                </div>

                                @error('answers')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('inspector.qcm-papers.show', $qcmPaper) }}"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Mettre à jour') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                                {{ __('Supprimer la réponse') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('Êtes-vous sûr de vouloir supprimer cette réponse ? Cette action ne peut pas être annulée.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Supprimer') }}
                    </button>
                    <button type="button" id="cancel-delete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Annuler') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="error-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="error-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="error-modal-title">
                                {{ __('Attention') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="error-modal-message">
                                    <!-- Error message will be inserted here -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="close-error-modal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('OK') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Image preview functionality
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const previewContainer = document.getElementById('image-preview');
                        const previewImg = document.getElementById('preview-img');

                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function clearImagePreview() {
                const input = document.getElementById('image');
                const previewContainer = document.getElementById('image-preview');

                input.value = '';
                previewContainer.classList.add('hidden');
            }

            document.addEventListener('DOMContentLoaded', function () {
                const answersContainer = document.getElementById('answers-container');
                const addAnswerButton = document.getElementById('add-answer');
                let answerCount = {{ count($question->answers) }};

                // Debug logging
                console.log('Edit view loaded');
                console.log('answersContainer:', answersContainer);
                console.log('addAnswerButton:', addAnswerButton);
                console.log('Initial answerCount:', answerCount);

                // Check if elements exist
                if (!addAnswerButton) {
                    console.error('Add answer button not found in edit view!');
                    return;
                }
                if (!answersContainer) {
                    console.error('Answers container not found in edit view!');
                    return;
                }

                // Store the translated text in a variable
                const answerPlaceholder = 'Texte de la réponse';

                addAnswerButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Add answer button clicked in edit view!');

                    // Limit to maximum 4 answers for multiple choice
                    if (answerCount >= 4) {
                        showErrorModal('Maximum 4 réponses autorisées');
                        return;
                    }

                    const newAnswer = document.createElement('div');
                    newAnswer.className = 'answer-item p-4 border border-gray-200 rounded-md';
                    newAnswer.innerHTML = `
                        <div class="flex items-center">
                            <input type="radio" name="answers[${answerCount}][is_correct]" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <div class="ml-3 flex-grow">
                                <input type="text" name="answers[${answerCount}][text]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="${answerPlaceholder}" required>
                            </div>
                            <button type="button" class="remove-answer ml-3 text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    `;

                    answersContainer.appendChild(newAnswer);
                    answerCount++;

                    console.log('New answer added in edit view, count:', answerCount); // Debug log

                    // Add event listener to the new remove button
                    newAnswer.querySelector('.remove-answer').addEventListener('click', function (e) {
                        e.preventDefault();
                        console.log('New remove button clicked!');
                        
                        // Don't allow removal if we only have 2 answers
                        const answerItems = document.querySelectorAll('.answer-item');
                        if (answerItems.length <= 2) {
                            showErrorModal('Vous devez avoir au moins 2 réponses');
                            return;
                        }
                        
                        // Show confirmation modal
                        showDeleteConfirmationModal(newAnswer.querySelector('.remove-answer'));
                    });

                    updateRemoveButtons();
                });

                // Function to update answer indices after removal
                function updateAnswerIndices() {
                    const answerItems = document.querySelectorAll('.answer-item');
                    answerItems.forEach((item, index) => {
                        const radio = item.querySelector('input[type="radio"]');
                        const textInput = item.querySelector('input[type="text"]');
                        const hiddenId = item.querySelector('input[name*="[id]"]');

                        if (radio) radio.name = `answers[${index}][is_correct]`;
                        if (textInput) textInput.name = `answers[${index}][text]`;
                        if (hiddenId) hiddenId.name = `answers[${index}][id]`;
                    });
                    answerCount = answerItems.length;
                }

                // Function to show/hide remove buttons based on answer count
                function updateRemoveButtons() {
                    const removeButtons = document.querySelectorAll('.remove-answer');
                    const answerItems = document.querySelectorAll('.answer-item');

                    console.log('Updating remove buttons, answer count:', answerItems.length);

                    // Show remove buttons only if we have more than 2 answers
                    removeButtons.forEach(button => {
                        if (answerItems.length > 2) {
                            button.style.display = 'inline-block';
                        } else {
                            button.style.display = 'none';
                        }
                    });
                }

                // Add event listeners to existing remove buttons
                console.log('Setting up event listeners for existing remove buttons');
                const existingRemoveButtons = document.querySelectorAll('.remove-answer');
                console.log('Found', existingRemoveButtons.length, 'existing remove buttons');
                
                existingRemoveButtons.forEach((button, index) => {
                    console.log('Setting up listener for button', index, button);
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Remove button clicked!', button);
                        // Don't allow removal if we only have 2 answers
                        const answerItems = document.querySelectorAll('.answer-item');
                        if (answerItems.length <= 2) {
                            showErrorModal('Vous devez avoir au moins 2 réponses');
                            return;
                        }
                        
                        // Show confirmation modal
                        showDeleteConfirmationModal(button);
                    });
                });

                // Initialize remove button visibility
                updateRemoveButtons();
                
                // Debug: Check button visibility
                setTimeout(() => {
                    const buttons = document.querySelectorAll('.remove-answer');
                    console.log('Remove buttons after initialization:');
                    buttons.forEach((btn, i) => {
                        console.log(`Button ${i}:`, btn, 'Display:', btn.style.display, 'Visible:', btn.offsetWidth > 0);
                    });
                }, 100);

                // Drag and drop functionality for image upload
                const dropZone = document.querySelector('.border-dashed');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    dropZone.classList.add('border-indigo-400', 'bg-indigo-50');
                }

                function unhighlight(e) {
                    dropZone.classList.remove('border-indigo-400', 'bg-indigo-50');
                }

                dropZone.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        const input = document.getElementById('image');
                        input.files = files;
                        previewImage(input);
                    }
                }

                // Modal functions
                let currentButtonToDelete = null;

                function showDeleteConfirmationModal(button) {
                    currentButtonToDelete = button;
                    const modal = document.getElementById('delete-confirmation-modal');
                    modal.classList.remove('hidden');
                }

                function showErrorModal(message) {
                    const modal = document.getElementById('error-modal');
                    const messageElement = document.getElementById('error-modal-message');
                    messageElement.textContent = message;
                    modal.classList.remove('hidden');
                }

                // Handle delete confirmation
                document.getElementById('confirm-delete').addEventListener('click', function() {
                    if (currentButtonToDelete) {
                        currentButtonToDelete.closest('.answer-item').remove();
                        updateAnswerIndices();
                        updateRemoveButtons();
                        currentButtonToDelete = null;
                    }
                    document.getElementById('delete-confirmation-modal').classList.add('hidden');
                });

                // Handle delete cancellation
                document.getElementById('cancel-delete').addEventListener('click', function() {
                    currentButtonToDelete = null;
                    document.getElementById('delete-confirmation-modal').classList.add('hidden');
                });

                // Handle error modal close
                document.getElementById('close-error-modal').addEventListener('click', function() {
                    document.getElementById('error-modal').classList.add('hidden');
                });

                // Close modals when clicking outside
                document.getElementById('delete-confirmation-modal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        currentButtonToDelete = null;
                        this.classList.add('hidden');
                    }
                });

                document.getElementById('error-modal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
@endsection