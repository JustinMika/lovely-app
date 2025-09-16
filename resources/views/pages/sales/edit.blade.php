@extends('layouts.app')

@section('title', 'Modifier la vente #' . $sale->id)

@section('content')
    @livewire('sales-manager', ['view' => 'edit', 'saleId' => $sale->id])
@endsection
