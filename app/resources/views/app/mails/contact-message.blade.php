@extends('app.mails.layout.main')

@section('content')
    <p>{{ $data['name'] }} <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a> from {{ $data['client_ip_address'] ?? 'N/A' }}.</p>
    <p>{{ $data['subject'] }}</p>
    <p>{{ $data['message'] }}</p>
@endsection
