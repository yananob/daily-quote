<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Quote Edit</h1>

        @if (isset($error))
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endif

        <form action="{{ $basePath }}/quotes/update/{{ $quote->getNo() }}" method="POST">
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ $quote->getAuthor() }}">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5">{{ $quote->getMessage() }}</textarea>
            </div>
            <div class="mb-3">
                <label for="source" class="form-label">Source</label>
                <input type="text" class="form-control" id="source" name="source" value="{{ $quote->getSource() }}">
            </div>
            <div class="mb-3">
                <label for="source_link" class="form-label">Source Link</label>
                <input type="text" class="form-control" id="source_link" name="source_link" value="{{ $quote->getSourceLink() }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
