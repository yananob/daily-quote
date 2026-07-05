@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- 統計ダッシュボード -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">総格言数</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $statistics['totalQuotes'] }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">総配信数</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $statistics['totalDelivered'] }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">平均配信数</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $statistics['averageDelivered'] }}</dd>
            </div>
        </div>
    </div>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Quotes</h1>
            <p class="mt-2 text-sm text-gray-700">登録されている格言の一覧です。</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="/quotes/new" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Create Quote
            </a>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Message</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Author</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">配信数</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($quotes as $quote)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $quote->getNo() }}</td>
                                <td class="px-3 py-4 text-sm text-gray-500">{{ $quote->getMessage() }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $quote->getAuthor() }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $quote->getDeliveredCount() }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-2">
                                    <a href="/quotes/edit/{{ $quote->getNo() }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    @if ($quote->getSourceLink())
                                        <a href="{{ $quote->getSourceLink() }}" target="_blank" class="text-cyan-600 hover:text-cyan-900">Link</a>
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
            </div>
        </div>
    </div>

    <!-- ページネーション -->
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-lg shadow">
        <div class="flex flex-1 justify-between sm:hidden">
            <a href="?page={{ $page - 1 }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $page <= 1 ? 'pointer-events-none opacity-50' : '' }}">Previous</a>
            <a href="?page={{ $page + 1 }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ !$hasNextPage ? 'pointer-events-none opacity-50' : '' }}">Next</a>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Page <span class="font-medium">{{ $page }}</span> of <span class="font-medium">{{ $lastPage }}</span>
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <a href="?page={{ $page - 1 }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 {{ $page <= 1 ? 'pointer-events-none opacity-50' : '' }}">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01.02 1.06L9.41 10l3.4 3.71a.75.75 0 11-1.14.98l-3.85-4.2a.75.75 0 010-1.06l3.85-4.2a.75.75 0 011.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    @for ($i = 1; $i <= $lastPage; $i++)
                        <a href="?page={{ $i }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold {{ $i == $page ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0' }}">
                            {{ $i }}
                        </a>
                    @endfor
                    <a href="?page={{ $page + 1 }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 {{ !$hasNextPage ? 'pointer-events-none opacity-50' : '' }}">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.59 10 7.19 6.29a.75.75 0 111.14-.98l3.85 4.2a.75.75 0 010 1.06l-3.85 4.2a.75.75 0 01-1.06.02z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
