@extends('api.v1.mails.layout.main')

@section('content')
    <p>{{ trans('mails.weekly-subscription.weekly_update') }}</p>
    <p>{{ trans('mails.weekly-subscription.click_on_link') }}</p>
    <p><a href="{{ $website_url }}" target="_blank">{{ trans('mails.weekly-subscription.go_to_website') }}</a></p>
    <p><a href="{{ $unsubscribe_url }}" target="_blank">{{ trans('mails.weekly-subscription.unsubscribe') }}</a></p>
@endsection
