<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Quotes</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 800px; margin: 0 auto; }
        .quote { border-left: 3px solid #ccc; padding-left: 1em; margin-bottom: 2em; }
        .quote p { margin: 0; }
        .quote .author { text-align: right; }
        .pagination { display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quotes</h1>

        @foreach ($quotes as $quote)
            <div class="quote">
                <p>{{ $quote->getMessage() }}</p>
                <p class="author">-- {{ $quote->getAuthor() }}</p>
            </div>
        @endforeach

        <div class="pagination">
            @if ($page > 1)
                <a href="?page={{ $page - 1 }}">Previous</a>
            @endif

            @if ($hasNextPage)
                <a href="?page={{ $page + 1 }}">Next</a>
            @endif
        </div>
    </div>
</body>
</html>
