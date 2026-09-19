@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Quote Detail #{{ $quote->getNo() }}</h1>
        <a href="/" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            Back to List
        </a>
    </div>

    <!-- 配信時プレビュー -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">配信時プレビュー</h2>
        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ $quote->getFormattedMessage() }}</div>
    </div>

    <!-- 詳細情報 -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6 space-y-4">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">No</span>
            <span class="text-base text-gray-900">{{ $quote->getNo() }}</span>
        </div>

        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Message</span>
            <div class="text-base text-gray-900 whitespace-pre-wrap">{{ $quote->getMessage() }}</div>
        </div>

        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Author</span>
            <span class="text-base text-gray-900">{{ $quote->getAuthor() }}</span>
        </div>

        @if ($quote->getSource())
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Source</span>
            <span class="text-base text-gray-900">{{ $quote->getSource() }}</span>
        </div>
        @endif

        @if ($quote->getSourceLink())
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Source Link</span>
            <a href="{{ $quote->getSourceLink() }}" target="_blank" rel="noopener noreferrer" class="text-base text-blue-600 hover:underline break-all">
                {{ $quote->getSourceLink() }}
            </a>
        </div>
        @endif

        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">配信数</span>
            <span class="text-base text-gray-900">{{ $quote->getDeliveredCount() }}</span>
        </div>
    </div>

    <!-- 操作ボタン -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="/quotes/edit/{{ $quote->getNo() }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition">
                Edit
            </a>
            @if ($quote->getSourceLink())
                <a href="{{ $quote->getSourceLink() }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition">
                    Open Link
                </a>
            @endif
        </div>

        <form action="/quotes/delete/{{ $quote->getNo() }}" method="POST" class="inline">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 transition" onclick="return confirm('Are you sure?')">
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
