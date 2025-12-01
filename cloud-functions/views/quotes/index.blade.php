<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Daily Quotes</title>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                <tr>
                    <td>{{ $quote->getNo() }}</td>
                    <td>{{ $quote->getMessage() }}</td>
                    <td>{{ $quote->getAuthor() }}</td>
                    <td>
                        <a href="/quotes/edit/{{ $quote->getNo() }}" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item @if($page <= 1) disabled @endif">
                    <a class="page-link" href="?page={{ $page - 1 }}">&lt;</a>
                </li>
                @for ($i = 1; $i <= $lastPage; $i++)
                    <li class="page-item @if($i == $page) active @endif">
                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                    </li>
                    @endfor
                    <li class="page-item @if(!$hasNextPage) disabled @endif">
                        <a class="page-link" href="?page={{ $page + 1 }}">&gt;</a>
                    </li>
            </ul>
        </nav>
    </div>
</body>

</html>