@if (count($errors->all()))
	@foreach ($errors->all() as $error)
		<div class="validation-error-msg">{!! $error !!}</div>
	@endforeach
@endif