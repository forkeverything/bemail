@extends('layouts.app')
@section('content')
	<div id="error-page" class="d-flex">
		<div class="row justify-content-center align-items-center">
			<div class="col-md-8">
				<h1 class="display-4 text-warning text-center font-weight-bold mt-5 mb-2">
					<i class="fas fa-exclamation-circle mb-3" data-fa-transform="grow-10"></i>
					<br>
					Oh no...
				</h1>
				<p class="text-center">
					Sorry, we couldn't send your message. Don't worry, you didn't do anything wrong and you <strong>won't
						be
						charged for the message</strong>. It was most probably just a system hiccup and we should be up
					again soon.
				<div class="d-flex justify-content-center">
					<a href="/compose">
						<button type="button" class="btn btn-outline-secondary">I Want To Try Again</button>
					</a>
				</div>
			</div>
		</div>
	</div>
@endsection

