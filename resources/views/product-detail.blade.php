@extends('layouts.app')

@section('title', ($product['name'] ?? 'Detail Produk') . ' — Jaya Mandiri')

@section('content')
<!-- Data passed to React -->
<script type="application/json" id="product-data">
    {!! json_encode($product) !!}
</script>
<script type="text/plain" id="api-url">
    {{ $apiUrl ?? '' }}
</script>
<script type="text/plain" id="csrf-token">
    {{ csrf_token() }}
</script>
<script type="text/plain" id="is-logged-in">
    {{ session('user') ? 'true' : 'false' }}
</script>

<!-- React Mount Point -->
<div id="product-detail-root"></div>

@push('scripts')
    @vite('resources/js/product-detail-app.jsx')
@endpush
@endsection
