@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900">Quote Edit</h1>

    @if (isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="/quotes/update/{{ $quote->getNo() }}" method="POST" class="space-y-6">
            <div>
                <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="author" name="author" value="{{ $quote->getAuthor() }}">
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="message" name="message" rows="5">{{ $quote->getMessage() }}</textarea>
            </div>
            <div>
                <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="source" name="source" value="{{ $quote->getSource() }}">
            </div>
            <div>
                <label for="source_link" class="block text-sm font-medium text-gray-700">Source Link</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="source_link" name="source_link" value="{{ $quote->getSourceLink() }}">
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update
                </button>
                <a href="/" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
