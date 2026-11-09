@extends('errors.layout')

@section('title', 'Terjadi Gangguan Server (500)')
@section('code', '500')
@section('icon') <span class="material-symbols-rounded" style="font-size:2.5rem;color:#f59e0b;">warning</span> @endsection
@section('heading', 'Terjadi Gangguan Server')
@section('message', 'Sistem sedang mengalami kendala internal sementara. Tim teknis sedang menangani hal ini. Silakan coba beberapa saat lagi.')
