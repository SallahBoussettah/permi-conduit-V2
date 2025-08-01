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
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Créer une question') }}</h1>
                </div>
                <p class="mt-2 text-sm text-gray-700">{{ __('Ajouter une nouvelle question au questionnaire QCM:') }}
                    {{ $qcmPaper->title }}
                </p>
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
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Informations sur le questionnaire QCM') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ __('Chaque questionnaire QCM doit contenir exactement 10 questions.') }}</p>
                            <p class="mt-1">{{ __('Nombre de questions actuel:') }} <strong>{{ $maxSequence }}/10</strong>
                            </p>
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
                    <form action="{{ route('inspector.qcm-papers.questions.store', $qcmPaper) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Debug info - Remove after fixing -->
                        <div class="bg-gray-100 p-4 mb-4 rounded">
                            <p>Debug: QCM Paper ID: {{ $qcmPaper->id }}</p>
                        </div>

                        <!-- Hidden QCM Paper ID field -->
                        <input type="hidden" name="qcm_paper_id" value="{{ $qcmPaper->id }}">

                        @if ($errors->any())
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
                                        <h3 class="text-sm font-medium text-red-800">
                                            {{ __('Des erreurs ont été rencontrées dans votre soumission') }}
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <!-- Question Text -->
                            <div>
                                <label for="question_text"
                                    class="block text-sm font-medium text-gray-700">{{ __('Texte de la question') }} <span
                                        class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <textarea name="question_text" id="question_text" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('question_text') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                        required>{{ old('question_text') }}</textarea>
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
                                        <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>{{ __('Choix multiple') }}</option>
                                        <option value="yes_no" {{ old('question_type') == 'yes_no' ? 'selected' : '' }}>
                                            {{ __('Oui/Non') }}
                                        </option>
                                    </select>
                                </div>
                                @error('question_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Section Selection -->
                            <div>
                                <label for="section_id"
                                    class="block text-sm font-medium text-gray-700">{{ __('Section') }}</label>
                                <div class="mt-1">
                                    <select id="section_id" name="section_id"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('section_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">{{ __('Section par défaut') }}</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ __('Laisser vide pour ajouter à la section par défaut') }}
                                </p>
                                @error('section_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sequence Number (Hidden) -->
                            <input type="hidden" name="sequence_number" value="{{ $maxSequence + 1 }}">

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
                                                    {{ __('Aperçu de l\'image') }}
                                                </span>
                                            </div>
                                            <img id="preview-img" src="" alt="Aperçu"
                                                class="mx-auto max-h-48 max-w-full rounded-lg shadow-md border border-gray-200">
                                            <button type="button" onclick="clearImagePreview()"
                                                class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium">
                                                {{ __('Supprimer') }}
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
                            </div>

                            <!-- Explanation -->
                            <div>
                                <label for="explanation"
                                    class="block text-sm font-medium text-gray-700">{{ __('Explication (Optionnel)') }}</label>
                                <div class="mt-1">
                                    <textarea name="explanation" id="explanation" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('explanation') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('explanation') }}</textarea>
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
                                    {{ __('Ajouter au moins 2 réponses et marquer une comme correcte.') }}
                                </p>

                                <div id="answers-container" class="mt-4 space-y-4">
                                    <!-- Answer 1 -->
                                    <div class="answer-item p-4 border border-gray-200 rounded-md">
                                        <div class="flex items-center">
                                            <input type="radio" name="answers[0][is_correct]" value="1"
                                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                            <div class="ml-3 flex-grow">
                                                <input type="text" name="answers[0][text]"
                                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    placeholder="{{ __('Texte de la réponse') }}" required>
                                            </div>
                                            <button type="button" class="remove-answer ml-3 text-red-600 hover:text-red-900"
                                                style="display: none;">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Answer 2 -->
                                    <div class="answer-item p-4 border border-gray-200 rounded-md">
                                        <div class="flex items-center">
                                            <input type="radio" name="answers[1][is_correct]" value="1"
                                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                            <div class="ml-3 flex-grow">
                                                <input type="text" name="answers[1][text]"
                                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    placeholder="{{ __('Texte de la réponse') }}" required>
                                            </div>
                                            <button type="button" class="remove-answer ml-3 text-red-600 hover:text-red-900"
                                                style="display: none;">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
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
                                {{ __('Créer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            console.log('DOM loaded, initializing...');
            
            const form = document.querySelector('form');
            const questionTypeSelect = document.getElementById('question_type');
            const answersContainer = document.getElementById('answers-container');
            const addAnswerButton = document.getElementById('add-answer');
            let answerCount = 2;

            // Check if elements exist
            if (!addAnswerButton) {
                console.error('Add answer button not found!');
                return;
            }
            if (!answersContainer) {
                console.error('Answers container not found!');
                return;
            }

            console.log('All elements found, setting up functionality...');

            // Store the translated text
            const answerPlaceholder = 'Texte de la réponse';

            // Function to update answer indices
            function updateAnswerIndices() {
                const answerItems = document.querySelectorAll('.answer-item');
                answerItems.forEach((item, index) => {
                    const radio = item.querySelector('input[type="radio"]');
                    const textInput = item.querySelector('input[type="text"]');
                    if (radio) radio.name = `answers[${index}][is_correct]`;
                    if (textInput) textInput.name = `answers[${index}][text]`;
                });
                answerCount = answerItems.length;
                updateRemoveButtons();
            }

            // Function to show/hide remove buttons
            function updateRemoveButtons() {
                const removeButtons = document.querySelectorAll('.remove-answer');
                const answerItems = document.querySelectorAll('.answer-item');
                removeButtons.forEach(button => {
                    if (answerItems.length > 2 && questionTypeSelect.value !== 'yes_no') {
                        button.style.display = 'block';
                    } else {
                        button.style.display = 'none';
                    }
                });
            }

            // Function to set up Yes/No answers
            function setYesNoAnswers() {
                answersContainer.innerHTML = `
                    <div class="answer-item p-4 border border-gray-200 rounded-md">
                        <div class="flex items-center">
                            <input type="radio" name="answers[0][is_correct]" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <div class="ml-3 flex-grow">
                                <input type="text" name="answers[0][text]" value="Oui" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="answer-item p-4 border border-gray-200 rounded-md">
                        <div class="flex items-center">
                            <input type="radio" name="answers[1][is_correct]" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <div class="ml-3 flex-grow">
                                <input type="text" name="answers[1][text]" value="Non" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" readonly>
                            </div>
                        </div>
                    </div>
                `;
                answerCount = 2;
            }

            // Handle question type change
            questionTypeSelect.addEventListener('change', function() {
                console.log('Question type changed to:', this.value);
                if (this.value === 'yes_no') {
                    setYesNoAnswers();
                    addAnswerButton.style.display = 'none';
                } else {
                    addAnswerButton.style.display = 'inline-flex';
                    // Reset to editable answers if switching from yes/no
                    const firstAnswer = answersContainer.querySelector('input[name="answers[0][text]"]');
                    const secondAnswer = answersContainer.querySelector('input[name="answers[1][text]"]');
                    if (firstAnswer && firstAnswer.value === 'Oui') {
                        firstAnswer.value = '';
                        firstAnswer.removeAttribute('readonly');
                    }
                    if (secondAnswer && secondAnswer.value === 'Non') {
                        secondAnswer.value = '';
                        secondAnswer.removeAttribute('readonly');
                    }
                    updateRemoveButtons();
                }
            });

            // Add answer button functionality
            addAnswerButton.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('Add answer button clicked!');
                
                if (answerCount >= 4) {
                    alert('Maximum 4 réponses autorisées');
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
                console.log('New answer added, total count:', answerCount);

                // Add event listener to the new remove button
                newAnswer.querySelector('.remove-answer').addEventListener('click', function () {
                    newAnswer.remove();
                    updateAnswerIndices();
                });

                updateRemoveButtons();
            });

            // Add event listeners to existing remove buttons
            document.querySelectorAll('.remove-answer').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.answer-item').remove();
                    updateAnswerIndices();
                });
            });

            // Form validation
            form.addEventListener('submit', function (e) {
                const correctAnswers = document.querySelectorAll('input[name*="[is_correct]"]:checked');
                if (correctAnswers.length !== 1) {
                    e.preventDefault();
                    alert('Veuillez sélectionner exactement une réponse correcte.');
                    return false;
                }
            });

            // Initialize
            if (questionTypeSelect.value === 'yes_no') {
                setYesNoAnswers();
                addAnswerButton.style.display = 'none';
            } else {
                updateRemoveButtons();
            }

            console.log('Initialization complete!');
        });
    </script>
@endsection