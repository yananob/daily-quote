@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Quote Add</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">新しい格言を追加します。</p>
    </div>

    @if (isset($error))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-6 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ $error }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        <form action="/quotes/store" method="POST" class="space-y-6">
            <div>
                <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                <div class="mt-1">
                    <input type="text" name="author" id="author" value="{{ $quote['author'] ?? '' }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                </div>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <div class="mt-1">
                    <textarea id="message" name="message" rows="5" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">{{ $quote['message'] ?? '' }}</textarea>
                </div>
            </div>

            <div>
                <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                <div class="mt-1">
                    <input type="text" name="source" id="source" value="{{ $quote['source'] ?? '' }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                </div>
            </div>

            <div>
                <label for="source_link" class="block text-sm font-medium text-gray-700">Source Link</label>
                <div class="mt-1">
                    <input type="text" name="source_link" id="source_link" value="{{ $quote['source_link'] ?? '' }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="/" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
