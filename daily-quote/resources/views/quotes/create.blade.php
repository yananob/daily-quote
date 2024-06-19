@extends("layouts.app")

@section("content")
@include('commons.errors')
<form action="{{ route('quotes.store') }}" method="post">
    @include("quotes.form")
    <button type="submit" class="btn btn-primary">Store</button>
    <button type="button" class="btn btn-warning" onclick="backToIndex()">Back</button>
</form>
@endsection
