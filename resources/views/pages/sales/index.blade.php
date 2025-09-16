@extends('layouts.app')

@section('title', 'Gestion des Ventes')

@section('content')
    @livewire('sales-manager', ['view' => 'list'])
@endsection
