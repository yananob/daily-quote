@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Quotes</h1>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="text-sm font-medium text-gray-500 uppercase">総格言数</div>
            <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $statistics['total_quotes'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="text-sm font-medium text-gray-500 uppercase">総配信数</div>
            <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $statistics['total_delivered'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="text-sm font-medium text-gray-500 uppercase">平均配信数</div>
            <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($statistics['average_delivered'], 2) }}</div>
        </div>
    </div>

    <div class="mb-6">
        <a href="/quotes/new" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
            Create Quote
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-center">配信回数</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                @foreach ($quotes as $quote)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $quote->getNo() }}</td>
                    <td class="px-6 py-4 text-sm">{{ $quote->getMessage() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $quote->getAuthor() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $quote->getDeliveredCount() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="/quotes/edit/{{ $quote->getNo() }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                            @if ($quote->getSourceLink())
                                <a href="{{ $quote->getSourceLink() }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium">Link</a>
                            @endif
                            <form action="/quotes/delete/{{ $quote->getNo() }}" method="POST" class="inline">
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav class="mt-8 flex justify-center">
        <ul class="inline-flex items-center -space-x-px">
            <li>
                <a href="?page={{ $page - 1 }}" class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 @if($page <= 1) pointer-events-none opacity-50 @endif">
                    <span class="sr-only">Previous</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </a>
            </li>
            @for ($i = 1; $i <= $lastPage; $i++)
                <li>
                    <a href="?page={{ $i }}" class="px-3 py-2 leading-tight border border-gray-300 hover:bg-gray-100 hover:text-gray-700 @if($i == $page) z-10 text-blue-600 bg-blue-50 border-blue-300 hover:bg-blue-100 hover:text-blue-700 @else text-gray-500 bg-white @endif">
                        {{ $i }}
                    </a>
                </li>
            @endfor
            <li>
                <a href="?page={{ $page + 1 }}" class="block px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 @if(!$hasNextPage) pointer-events-none opacity-50 @endif">
                    <span class="sr-only">Next</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </a>
            </li>
        </ul>
    </nav>
</div>
@endsection
