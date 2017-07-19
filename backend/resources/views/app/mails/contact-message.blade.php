@extends('app.mails.layout.main')

@section('content')
    <p>{{ $name }} <a href="mailto:{{ $email }}">{{ $email }}</a> from {{ $client_ip_address ?? 'N/A' }}.</p>
    <p>{{ $subject }}</p>
    <p>{{ $text }}</p>
@endsection
