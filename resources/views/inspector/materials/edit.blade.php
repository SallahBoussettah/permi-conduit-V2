@extends('layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">
                        {{ __('Modifier le matériel du cours') }}
                    </h2>
                    <a href="{{ route('inspector.courses.show', $course) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Retour au cours') }}
                    </a>
                </div>

                @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('inspector.courses.materials.update', [$course, $material]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Titre') }}</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title', $material->title) }}" required>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $material->description) }}</textarea>
                        </div>

                        <!-- Material Type (read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Type de matériel') }}</label>
                            <div class="text-sm text-gray-700 bg-gray-100 p-2 rounded">
                                @if($material->material_type === 'video')
                                    <span class="inline-flex items-center">
                                        <svg class="mr-1 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                        </svg>
                                        {{ __('Vidéo YouTube') }}
                                    </span>
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Le type de matériel ne peut pas être modifié après sa création.') }}</p>
                                @elseif($material->material_type === 'audio')
                                    <span class="inline-flex items-center">
                                        <svg class="mr-1 h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __('Fichier audio') }}
                                    </span>
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Le type de matériel ne peut pas être modifié après sa création.') }}</p>
                                @else
                                    <span class="inline-flex items-center">
                                        <svg class="mr-1 h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __('Document PDF') }}
                                    </span>
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Le type de matériel ne peut pas être modifié après sa création.') }}</p>
                                @endif
                                
                                <!-- Hidden field to ensure material_type is submitted with the form -->
                                <input type="hidden" name="material_type" value="{{ $material->material_type }}">
                            </div>
                        </div>

                        @if($material->material_type === 'pdf')
                        <!-- Current PDF File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Fichier PDF actuel') }}</label>
                            <div class="mt-1 text-sm text-gray-700">
                                {{ $material->content_path_or_url }}
                            </div>
                        </div>

                        <!-- Upload New PDF -->
                        <div>
                            <label for="pdf_file" class="block text-sm font-medium text-gray-700">{{ __('Remplacer le fichier PDF (Optionnel)') }}</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" name="pdf_file" id="pdf_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="application/pdf">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Laisser vide pour conserver le fichier actuel. Maximum de 10MB') }}</p>
                        </div>
                        @elseif($material->material_type === 'audio')
                        <!-- Current Audio File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Fichier audio actuel') }}</label>
                            <div class="mt-1 text-sm text-gray-700">
                                {{ $material->content_path_or_url }}
                            </div>
                        </div>

                        <!-- Audio Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Aperçu audio actuel') }}</label>
                            <div class="bg-gray-100 rounded-lg p-4">
                                <audio controls class="w-full">
                                    <source src="{{ route('inspector.courses.materials.audio', ['course' => $course, 'material' => $material]) }}" type="audio/mpeg">
                                    {{ __('Votre navigateur ne supporte pas l\'élément audio.') }}
                                </audio>
                            </div>
                        </div>

                        <!-- Upload New Audio -->
                        <div>
                            <label for="audio_file" class="block text-sm font-medium text-gray-700">{{ __('Remplacer le fichier audio (Optionnel)') }}</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" name="audio_file" id="audio_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="audio/mp3,audio/wav,audio/ogg,audio/mpeg">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Laisser vide pour conserver le fichier actuel. Maximum de 20MB') }}</p>
                        </div>
                        @else
                        <!-- YouTube Video URL -->
                        <div>
                            <label for="video_url" class="block text-sm font-medium text-gray-700">{{ __('URL de la vidéo YouTube') }}</label>
                            <input type="url" name="video_url" id="video_url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('video_url', 'https://www.youtube.com/watch?v='.$material->content_path_or_url) }}" pattern="https?://(www\.)?(youtube\.com|youtu\.be)/.+" required>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Entrez l\'URL complète de la vidéo YouTube (e.g., https://www.youtube.com/watch?v=abcdefghijk)') }}</p>
                        </div>
                        
                        <!-- Current YouTube Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Aperçu de la vidéo actuelle') }}</label>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden">
                                <iframe src="https://www.youtube.com/embed/{{ $material->content_path_or_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                            </div>
                        </div>
                        @endif

                        <!-- Current Thumbnail -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Image miniature actuelle') }}</label>
                            <div class="mt-1">
                                <img src="{{ asset('storage/' . $material->thumbnail_path) }}" alt="{{ $material->title }}" class="h-32 w-auto border rounded">
                            </div>
                        </div>

                        <!-- Replace Thumbnail -->
                        <div>
                            <label for="custom_thumbnail" class="block text-sm font-medium text-gray-700">{{ __('Remplacer l\'image miniature (Optionnel)') }}</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" name="custom_thumbnail" id="custom_thumbnail" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Laisser vide pour conserver l\'image miniature actuelle.') }}</p>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Mettre à jour le matériel') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 