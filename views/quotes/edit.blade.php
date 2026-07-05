@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Quote Edit</h1>

    @if (isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    <form action="/quotes/update/{{ $quote->getNo() }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border border-gray-200">
        <div class="mb-4">
            <label for="author" class="block text-gray-700 text-sm font-bold mb-2">Author</label>
            <input type="text" id="author" name="author" value="{{ $quote->getAuthor() }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message</label>
            <textarea id="message" name="message" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $quote->getMessage() }}</textarea>
        </div>
        <div class="mb-4">
            <label for="source" class="block text-gray-700 text-sm font-bold mb-2">Source</label>
            <input type="text" id="source" name="source" value="{{ $quote->getSource() }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-6">
            <label for="source_link" class="block text-gray-700 text-sm font-bold mb-2">Source Link</label>
            <input type="text" id="source_link" name="source_link" value="{{ $quote->getSourceLink() }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Update
            </button>
            <a href="/" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
