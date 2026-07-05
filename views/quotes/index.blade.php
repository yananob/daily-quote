@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6">Quotes</h1>

    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap gap-4 sm:gap-8 text-xs sm:text-sm text-gray-600 w-full sm:w-auto">
            <div>
                <span class="font-semibold text-gray-900">総格言数:</span>
                {{ $statistics['totalQuotes'] }}
            </div>
            <div>
                <span class="font-semibold text-gray-900">配信数:</span>
                {{ $statistics['totalDelivered'] }}
            </div>
            <div>
                <span class="font-semibold text-gray-900">平均配信数:</span>
                {{ $statistics['averageDelivered'] }}
            </div>
        </div>
        <a href="/quotes/new" class="w-full sm:w-auto text-center inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            Create Quote
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">配信数</th>
                    <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($quotes as $quote)
                <tr>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $quote->getNo() }}</td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-gray-900">
                        <div class="break-words min-w-[120px] max-w-xs sm:max-w-md">
                            {{ $quote->getMessage() }}
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-gray-500">
                        <div class="break-words max-w-[80px] sm:max-w-none">
                            {{ $quote->getAuthor() }}
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $quote->getDeliveredCount() }}</td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1 sm:space-x-2">
                        <a href="/quotes/edit/{{ $quote->getNo() }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        @if ($quote->getSourceLink())
                            <a href="{{ $quote->getSourceLink() }}" target="_blank" class="text-blue-600 hover:text-blue-900">Link</a>
                        @endif
                        <form action="/quotes/delete/{{ $quote->getNo() }}" method="POST" class="inline">
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex justify-center pb-8">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a href="?page={{ $page - 1 }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 @if($page <= 1) pointer-events-none opacity-50 @endif">
                <span class="sr-only">Previous</span>
                &lt;
            </a>

            @php
                $start = max(1, $page - 1);
                $end = min($lastPage, $page + 1);
                if ($page == 1) $end = min($lastPage, 3);
                if ($page == $lastPage) $start = max(1, $lastPage - 2);
            @endphp

            @if($start > 1)
                <a href="?page=1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                @if($start > 2)
                    <span class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                <a href="?page={{ $i }}" class="relative inline-flex items-center px-3 sm:px-4 py-2 border @if($i == $page) border-blue-500 bg-blue-50 text-blue-600 @else border-gray-300 bg-white text-gray-700 hover:bg-gray-50 @endif text-sm font-medium">
                    {{ $i }}
                </a>
            @endfor

            @if($end < $lastPage)
                @if($end < $lastPage - 1)
                    <span class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                @endif
                <a href="?page={{ $lastPage }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">{{ $lastPage }}</a>
            @endif

            <a href="?page={{ $page + 1 }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 @if(!$hasNextPage) pointer-events-none opacity-50 @endif">
                <span class="sr-only">Next</span>
                &gt;
            </a>
        </nav>
    </div>
</div>
@endsection
