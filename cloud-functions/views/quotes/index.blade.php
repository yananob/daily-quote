<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Quotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Quotes</h1>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Message</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                    <tr>
                        <td>{{ $quote->getNo() }}</td>
                        <td>{{ $quote->getMessage() }}</td>
                        <td>{{ $quote->getAuthor() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item @if($page <= 1) disabled @endif">
                    <a class="page-link" href="?page={{ $page - 1 }}">Previous</a>
                </li>
                <li class="page-item @if(!$hasNextPage) disabled @endif">
                    <a class="page-link" href="?page={{ $page + 1 }}">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>
