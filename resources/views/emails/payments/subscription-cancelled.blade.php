@component('mail::message')
	## Your Account Subscription Has Been Cancelled

	Oops! We've recently tried and failed to renew your subscription, so we had to cancel your subscription and downgrade your account to our free plan.

	You can update your payment details and subscribe to a new plan at anytime from your account payments page or by clicking the button below.

	<!-- TODO ::: Implement button url to go to account subscription page. -->

	@component('mail::button', ['url' => ''])
		Update Payment
	@endcomponent

	Thanks,<br>
	bemail

@endcomponent
