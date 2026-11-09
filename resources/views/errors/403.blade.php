@extends('errors.layout')

@section('title', 'Akses Ditolak (403)')
@section('code', '403')
@section('icon') <span class="material-symbols-rounded" style="font-size:2.5rem;color:#ef4444;">lock</span> @endsection
@section('heading', 'Akses Ditolak')
@section('message', $exception->getMessage() ?: 'Anda tidak memiliki hak akses atau izin yang sesuai untuk membuka halaman atau melakukan tindakan ini.')
