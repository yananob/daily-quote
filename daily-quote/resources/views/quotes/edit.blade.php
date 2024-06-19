@extends("layouts.app")

@section("content")
@include('commons.errors')
<form action="{{ route('quotes.update', $quote) }}" method="post">
    @method("patch")
    @include("quotes.form")
    <button type="submit">Update</button>
    <a href="{{ route('quotes.index') }}">Back</a>
</form>
<br>
<form action="{{ route('quotes.destroy', $quote) }}" method="post">
    @csrf
    @method("delete")
    <button type="submit">Delete</button>
</form>
@endsection