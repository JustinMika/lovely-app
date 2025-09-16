@extends('layouts.app')

@section('title', 'Nouvelle Vente')

@section('content')
    @livewire('sales-manager', ['view' => 'create'])
@endsection
