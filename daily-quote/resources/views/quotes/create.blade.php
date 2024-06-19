@extends("layouts.app")

@section("content")
@include('commons.errors')
<form action="{{ route('quotes.store') }}" method="post">
    @include("quotes.form")
    <button type="submit">Store</button>
    <a href="{{ route('quotes.index') }}">Back</a>
</form>
@endsection