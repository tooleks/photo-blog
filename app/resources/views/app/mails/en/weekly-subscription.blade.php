@extends('app.mails.layout.main')

@section('content')
    <p>Hi, I have uploaded new photo(s) this week.</p>
    <p><a href="{{ $data['website_url'] }}" target="_blank">Click here to see them all.</a></p>
    <p>If you don't want to receive the website updates just click the link below.</p>
    <p><a href="{{ $data['unsubscribe_url'] }}" target="_blank">Unsubscribe.</a></p>
@endsection
