@extends('layouts.app')

@section('content')

	<h5>Credit Card</h5>

	<p>You currently don't have a credit card saved.</p>

	<button type="button" class="btn btn-outline-primary mb-5">Add Card</button>

	<credit-card-form></credit-card-form>

	<h5>Subscription</h5>


	<p>Pick a subscription that suits your needs.</p>

	<div class="subscription-table row">


		<div class="col-xs-12 col-lg-4">
			<div class="card text-center">
				<div class="card-header">
					<h3 class="display-2">
						<span class="currency">$</span>
						<span>0</span>
						<span class="period">/month</span>
					</h3>
				</div>
				<div class="card-body">
					<h4 class="card-title">
						Free
					</h4>
					<ul class="list-group">
						<li class="list-group-item">
							Service Charge $0.07 / Word
						</li>
						<li class="list-group-item">
							Auto-Translate Replies
						</li>
						<li class="list-group-item">
							Send to Self
						</li>
						<li class="list-group-item">
							Send AttachmentsbaseStyle
						</li>
					</ul>
				</div>
			</div>
		</div>

	</div>

@endsection
