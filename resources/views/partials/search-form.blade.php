<form class="search-form" action="{{ route('search') }}">
    <input type="text" name="query" class="@error('query') is-invalid @enderror" placeholder="What are you looking for?" value="{{ request()->input('query') }}" required="" minlength="3">

    <button type="submit"><i class="fa fa-search"></i></button>

    @error('query')
        <p class="invalid-feedback text-center" role="alert">{{ $message }}</p>
    @enderror
</form>