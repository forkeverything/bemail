<!-- Field -->
<div class="form-group row {{ $errors->has($fieldName) ? 'mb-0' : '' }}">
	<div class="col-md-3 d-md-flex align-items-md-center justify-content-md-end">
		<label for="{{ $fieldName }}"
		       class="control-label mb-md-0 {{ $errors->has($fieldName) ? 'text-danger' : '' }}"
		>
			{{ $title }}
		</label>
	</div>
	<div class="col-md-9">
		{{ $slot }}
	</div>
</div>
<!-- Validation -->
@if(isset($shouldValidate) && $shouldValidate && $errors->has($fieldName))
	<div class="row mb-3">
		<div class="offset-md-3 col-md-9">
			@include('layouts.errors.single', ['errorField' => $fieldName])
		</div>
	</div>
@endif