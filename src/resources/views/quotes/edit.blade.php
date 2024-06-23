@extends("layouts.app")

@section("content")
@include('commons.errors')

<form name="edit_form" action="{{ route('quotes.update', $quote) }}" method="post">
    @csrf
    @method("patch")
    @include("quotes.form")
</form>

<button type="submit" class="btn btn-primary" onclick="document.edit_form.submit()">Update</button>
<button type="button" class="btn btn-warning" onclick="backToIndex()">Back</button>
<button type="button" class="btn btn-danger" onclick="document.delete_form.submit()">Delete</button>

<form name="delete_form" action="{{ route('quotes.destroy', $quote) }}" method="post">
    @csrf
    @method("delete")
</form>
@endsection