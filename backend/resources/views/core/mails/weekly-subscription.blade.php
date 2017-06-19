@extends('core.mails.layout.main')

@section('content')
    <p>{{ trans('mails.weekly-subscription.updates_message') }}</p>
    <p><a href="{{ $website_url }}" target="_blank">{{ trans('mails.weekly-subscription.updates_link_message') }}</a></p>
    <p>{{ trans('mails.weekly-subscription.unsubscribe_message') }}</p>
    <p><a href="{{ $unsubscribe_url }}" target="_blank">{{ trans('mails.weekly-subscription.unsubscribe_link_message') }}</a></p>
@endsection
