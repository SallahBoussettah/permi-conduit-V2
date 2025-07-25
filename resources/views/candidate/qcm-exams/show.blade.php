@extends('layouts.main')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $qcmExam->paper->title }}</h1>
                    <p class="mt-2 text-sm text-gray-700">{{ $qcmExam->paper->permitCategory->name }}</p>
                    <p class="mt-2 text-sm font-medium text-red-600">
                        {{ __('Cet examen QCM comporte 10 questions à compléter en 6 minutes. Vous devez avoir au moins 6 réponses correctes pour passer.') }}
                    </p>
                </div>
                <div class="flex items-center">
                    <div class="px-4 py-2 bg-white shadow rounded-lg mr-4">
                        <div class="text-sm text-gray-500">{{ __('Temps restant') }}</div>
                        <div id="timer" class="text-2xl font-bold text-gray-900">
                            {{ sprintf('%02d:%02d', floor($remainingTime / 60), $remainingTime % 60) }}
                        </div>

                        <!-- INLINE TIMER SCRIPT - GUARANTEED TO WORK -->
                        <script>
                            (function () {
                                let remainingSeconds = {{ $remainingTime }};
                                const timerElement = document.getElementById('timer');
                                let timerStopped = false;
                                let timerInterval;

                                function updateTimer() {
                                    // If timer is already stopped, don't continue
                                    if (timerStopped) {
                                        return;
                                    }

                                    const minutes = Math.floor(remainingSeconds / 60);
                                    const seconds = remainingSeconds % 60;
                                    const timeString = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

                                    if (timerElement) {
                                        timerElement.textContent = timeString;

                                        // Change colors based on time remaining
                                        if (remainingSeconds <= 30) {
                                            timerElement.className = 'text-2xl font-bold text-red-600 animate-pulse';
                                        } else if (remainingSeconds <= 60) {
                                            timerElement.className = 'text-2xl font-bold text-yellow-600';
                                        } else {
                                            timerElement.className = 'text-2xl font-bold text-gray-900';
                                        }
                                    }

                                    // Check if time is up
                                    if (remainingSeconds <= 0) {
                                        // Stop the timer immediately to prevent multiple alerts
                                        timerStopped = true;
                                        clearInterval(timerInterval);

                                        // Show the same modal as manual submission
                                        showTimeUpModal();
                                        return;
                                    }

                                    remainingSeconds--;
                                }

                                // Function to show timeout modal
                                window.showTimeUpModal = function () {
                                    const modal = document.getElementById('confirmation-modal');
                                    const modalTitle = document.getElementById('modal-title');
                                    const modalMessage = modal.querySelector('.text-sm.text-gray-500');
                                    const submitButton = modal.querySelector('.bg-indigo-600');
                                    const cancelButton = modal.querySelector('#cancel-submit');
                                    const iconContainer = modal.querySelector('.bg-yellow-100');
                                    const icon = iconContainer.querySelector('svg');

                                    // Update modal content for timeout
                                    modalTitle.textContent = '⏰ Temps écoulé !';
                                    modalMessage.innerHTML = 'Le temps de l\'examen est écoulé. Votre examen sera soumis automatiquement avec vos réponses actuelles.<br><br><strong>Soumission automatique dans <span id="countdown">5</span> secondes...</strong>';
                                    submitButton.textContent = 'Soumettre maintenant';

                                    // Change icon to red for urgency
                                    iconContainer.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10';
                                    icon.className = 'h-6 w-6 text-red-600';

                                    // Hide cancel button for timeout (no choice)
                                    if (cancelButton) {
                                        cancelButton.style.display = 'none';
                                    }

                                    // Show the modal
                                    modal.classList.remove('hidden');

                                    // Countdown timer
                                    let countdown = 5;
                                    const countdownElement = document.getElementById('countdown');
                                    const countdownInterval = setInterval(function () {
                                        countdown--;
                                        if (countdownElement) {
                                            countdownElement.textContent = countdown;
                                        }

                                        if (countdown <= 0) {
                                            clearInterval(countdownInterval);
                                            const form = document.getElementById('exam-form');
                                            if (form) {
                                                form.submit();
                                            }
                                        }
                                    }, 1000);

                                    // Allow immediate submission
                                    submitButton.onclick = function () {
                                        clearInterval(countdownInterval);
                                        const form = document.getElementById('exam-form');
                                        if (form) {
                                            form.submit();
                                        }
                                    };
                                };

                                // Start immediately and then every second
                                updateTimer();
                                timerInterval = setInterval(updateTimer, 1000);
                            })();
                        </script>
                    </div>
                    <div class="px-4 py-2 bg-white shadow rounded-lg">
                        <div class="text-sm text-gray-500">{{ __('Questions') }}</div>
                        <div class="text-2xl font-bold text-gray-900">
                            <span id="current-question">1</span> / {{ count($questions) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Form -->
            <form id="exam-form" action="{{ route('candidate.qcm-exams.submit', $qcmExam) }}" method="POST">
                @csrf

                <!-- Progress Bar -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-indigo-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                    <!-- Question Navigation Buttons -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-medium text-gray-700">{{ __('Navigation des questions') }}</h4>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full bg-white border border-gray-300 mr-1"></div>
                                    <span>{{ __('Non répondue') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full bg-green-500 mr-1"></div>
                                    <span>{{ __('Répondue') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full bg-blue-500 mr-1"></div>
                                    <span>{{ __('Actuelle') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($questions as $index => $question)
                                <button type="button" data-question="{{ $index + 1 }}" id="nav-btn-{{ $index + 1 }}" class="question-nav-btn w-10 h-10 rounded-lg text-sm font-bold transition-all duration-200 transform hover:scale-105
                                                    {{ isset($examAnswers[$question->id]) ? 'bg-green-500 text-white shadow-md' : 'bg-white border-2 border-gray-300 text-gray-700 hover:border-gray-400' }}
                                                    {{ $index === 0 ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}
                                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ $index + 1 }}
                                    @if(isset($examAnswers[$question->id]))
                                        <svg class="w-3 h-3 absolute -top-1 -right-1 text-white bg-green-600 rounded-full p-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Questions -->
                <div id="questions-container" class="space-y-6">
                    @foreach($questions as $index => $question)
                        <div id="question-{{ $index + 1 }}"
                            class="question-slide bg-white shadow overflow-hidden sm:rounded-lg {{ $index > 0 ? 'hidden' : '' }}">
                            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Question') }} {{ $index + 1 }}
                                </h3>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                                <div class="mb-6">
                                    @if($question->image_path)
                                        <div class="mb-6 flex justify-center">
                                            <div class="bg-white p-4 rounded-lg shadow-md border-2 border-gray-200">
                                                <img src="{{ $question->image_url }}" alt="Panneau de signalisation"
                                                    class="max-h-64 max-w-full mx-auto rounded-lg shadow-sm">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="text-center">
                                        <p class="text-lg font-medium text-gray-900">{{ $question->question_text }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <input type="hidden" name="questions[{{ $question->id }}][id]" value="{{ $question->id }}">

                                    @foreach($question->answers as $answer)
                                        <div
                                            class="relative flex items-start py-2 px-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors {{ isset($examAnswers[$question->id]) && $examAnswers[$question->id]->qcm_answer_id == $answer->id ? 'bg-indigo-50 border-indigo-300' : '' }}">
                                            <div class="flex items-center h-5">
                                                <input id="answer-{{ $question->id }}-{{ $answer->id }}"
                                                    name="questions[{{ $question->id }}][answer_id]" value="{{ $answer->id }}"
                                                    type="radio"
                                                    class="answer-radio focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                                    {{ isset($examAnswers[$question->id]) && $examAnswers[$question->id]->qcm_answer_id == $answer->id ? 'checked' : '' }}
                                                    data-question-id="{{ $question->id }}" data-answer-id="{{ $answer->id }}">
                                            </div>
                                            <div class="ml-3 text-sm flex-grow">
                                                <label for="answer-{{ $question->id }}-{{ $answer->id }}"
                                                    class="font-medium text-gray-700 cursor-pointer">
                                                    {{ $answer->answer_text }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between">
                                <button type="button"
                                    class="prev-btn bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $index === 0 ? 'invisible' : '' }}"
                                    data-target-question="{{ $index }}">
                                    {{ __('Précédent') }}
                                </button>

                                @if($index === count($questions) - 1)
                                    <button type="button"
                                        onclick="document.getElementById('confirmation-modal').classList.remove('hidden');"
                                        class="finish-exam-btn ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Terminer l\'examen') }}
                                    </button>
                                @else
                                    <button type="button"
                                        class="next-btn ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        data-target-question="{{ $index + 2 }}">
                                        {{ __('Suivant') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Confirmation Modal -->
                <div id="confirmation-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
                    role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            {{ __('Soumettre l\'examen') }}
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                {{ __('Êtes-vous sûr de vouloir soumettre votre examen ? Vous ne pouvez pas modifier vos réponses après la soumission.') }}
                                            </p>
                                            <div id="unanswered-warning" class="mt-2 text-sm text-red-600 hidden">
                                                {{ __('Attention: Vous avez des questions non répondues.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="document.getElementById('exam-form').submit();"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ __('Soumettre') }}
                                </button>
                                <button type="button" id="cancel-submit"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ __('Continuer l\'examen') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- NAVIGATION SCRIPT - INLINE TO AVOID STACK ISSUES -->
    <script>
        // Unified Question Navigation System
        document.addEventListener('DOMContentLoaded', function () {
            console.log("Setting up unified exam navigation...");

            const totalQuestions = {{ count($questions) }};
            let currentQuestionNumber = 1; // Track current question globally
            const navButtons = document.querySelectorAll('.question-nav-btn');

            console.log('Total questions:', totalQuestions);
            console.log('Found', navButtons.length, 'navigation buttons');

            // Central function to navigate to any question
            function navigateToQuestion(questionNumber) {
                if (questionNumber < 1 || questionNumber > totalQuestions) {
                    console.error('Invalid question number:', questionNumber);
                    return;
                }

                console.log('Navigating to question:', questionNumber);

                // Hide all questions
                document.querySelectorAll('.question-slide').forEach(function (slide) {
                    slide.classList.add('hidden');
                });

                // Show the target question
                const targetQuestion = document.getElementById('question-' + questionNumber);
                if (targetQuestion) {
                    targetQuestion.classList.remove('hidden');
                }

                // Update global current question
                currentQuestionNumber = questionNumber;

                // Update question counter
                const counterElement = document.getElementById('current-question');
                if (counterElement) {
                    counterElement.textContent = questionNumber;
                }

                // Update progress bar
                const progressBar = document.getElementById('progress-bar');
                if (progressBar) {
                    progressBar.style.width = ((questionNumber / totalQuestions) * 100) + '%';
                }

                // Update navigation button indicators
                updateNavigationIndicators();
            }

            // Update navigation button visual indicators
            function updateNavigationIndicators() {
                navButtons.forEach(function (btn, index) {
                    const btnQuestionNumber = index + 1;
                    
                    // Remove all indicator classes
                    btn.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                    
                    // Add current question indicator
                    if (btnQuestionNumber === currentQuestionNumber) {
                        btn.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                    }
                });
            }

            // Navigation button click handlers
            navButtons.forEach(function (button, index) {
                button.addEventListener('click', function () {
                    const questionNumber = parseInt(this.getAttribute('data-question'));
                    navigateToQuestion(questionNumber);
                });
            });

            // Previous/Next button handlers
            document.querySelectorAll('.prev-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetQuestion = parseInt(this.getAttribute('data-target-question'));
                    navigateToQuestion(targetQuestion);
                });
            });

            document.querySelectorAll('.next-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetQuestion = parseInt(this.getAttribute('data-target-question'));
                    navigateToQuestion(targetQuestion);
                });
            });

            // Initialize - set current question based on visible question
            const visibleQuestion = document.querySelector('.question-slide:not(.hidden)');
            if (visibleQuestion) {
                const questionId = visibleQuestion.id.replace('question-', '');
                currentQuestionNumber = parseInt(questionId);
                updateNavigationIndicators();
                console.log('Initialized current question to:', currentQuestionNumber);
            }

            // Handle answer selection and update navigation buttons
            document.querySelectorAll('.answer-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const questionId = this.getAttribute('data-question-id');
                    const answerId = this.getAttribute('data-answer-id');

                    // Find the question index for this question ID
                    const questionSlides = document.querySelectorAll('.question-slide');
                    let questionIndex = -1;
                    
                    questionSlides.forEach(function (slide, index) {
                        if (slide.querySelector('[data-question-id="' + questionId + '"]')) {
                            questionIndex = index;
                        }
                    });

                    // Update navigation button to show question is answered
                    if (questionIndex >= 0 && questionIndex < navButtons.length) {
                        const navBtn = navButtons[questionIndex];

                        // Remove unanswered classes
                        navBtn.classList.remove('bg-white', 'border-2', 'border-gray-300', 'text-gray-700', 'hover:border-gray-400');

                        // Add answered classes
                        navBtn.classList.add('bg-green-500', 'text-white', 'shadow-md');

                        // Add checkmark icon if not already present
                        if (!navBtn.querySelector('svg')) {
                            navBtn.style.position = 'relative';
                            const checkmark = document.createElement('svg');
                            checkmark.className = 'w-3 h-3 absolute -top-1 -right-1 text-white bg-green-600 rounded-full p-0.5';
                            checkmark.setAttribute('fill', 'currentColor');
                            checkmark.setAttribute('viewBox', '0 0 20 20');
                            checkmark.innerHTML = '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>';
                            navBtn.appendChild(checkmark);
                        }
                    }

                    // Save answer via AJAX
                    fetch('{{ route('candidate.qcm-exams.answer', $qcmExam) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            answer_id: answerId
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error('Error saving answer:', data.error);
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                console.log('Answer saved successfully');
                            }
                        })
                        .catch(error => {
                            console.error('Failed to save answer:', error);
                        });
                });
            });

            // Handle exam submission modal
            const confirmationModal = document.getElementById('confirmation-modal');
            const cancelButton = document.getElementById('cancel-submit');
            const unansweredWarning = document.getElementById('unanswered-warning');

            // Cancel button for submission modal
            if (cancelButton) {
                cancelButton.addEventListener('click', function () {
                    confirmationModal.classList.add('hidden');
                });
            }

            // Check for unanswered questions when finishing exam
            const finishBtn = document.querySelector('.finish-exam-btn');
            if (finishBtn) {
                finishBtn.addEventListener('click', function () {
                    // Count answered questions (green buttons)
                    const answeredCount = document.querySelectorAll('.question-nav-btn.bg-green-500').length;

                    if (answeredCount < totalQuestions) {
                        unansweredWarning.classList.remove('hidden');
                        unansweredWarning.textContent = 'Attention: Vous avez ' + (totalQuestions - answeredCount) + ' questions non répondues.';
                    } else {
                        unansweredWarning.classList.add('hidden');
                    }
                });
            }

            console.log("Unified navigation system initialized successfully");
        });
    </script>
@endsectionion modal
                        confirmationModal.classList.remove('hidden');
                    });
                }
            });
        </script>
@endsection