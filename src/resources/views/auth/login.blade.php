@extends("layouts.app")

@section("content")
@include('commons.errors')
<form action="{{ route('login') }}" method="post">
    @csrf
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
@endsection
