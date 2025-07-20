@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Standard Course Templates</h1>
        <p class="mt-2 text-gray-600">Manage the standard course templates that will be auto-seeded when creating new schools.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('super_admin.course-templates.seed') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Seed Standard Templates
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            @if($permitCategories->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">No permit categories found. Please create permit categories first.</p>
                </div>
            @else
                @foreach($permitCategories as $permitCategory)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $permitCategory->name }}</h2>
                        
                        @if($templates->where('permit_category_id', $permitCategory->id)->isEmpty())
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <p class="text-yellow-700">No templates found for this permit category.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Section</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sequence</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($templates->where('permit_category_id', $permitCategory->id)->sortBy('sequence_order') as $template)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $template->title }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $template->exam_section }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $template->sequence_order }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($template->description, 100) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection