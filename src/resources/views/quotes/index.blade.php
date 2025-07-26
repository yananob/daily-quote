@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col">
        <form action="{{ route('quotes.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Search by message..." value="{{ $keyword ?? '' }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <form action="{{ route('quotes.create') }}">
                <button type="submit" class="btn btn-sm btn-primary">Add</button>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Message</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotes as $quote)
                    <tr>
                        <td><a href="{{ route('quotes.edit', $quote) }}">{{ $quote->id }}</a></td>
                        <td>{{ $quote->message }}</td>
                        <td>{{ $quote->author }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $quotes->appends(Request::all())->links() }}
    </div>
</div>
@endsection