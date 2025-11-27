@extends('layouts.master')

@section('title', 'Payment Error – BeatHive')

@section('content')
<div class="page-content">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card border-danger">
          <div class="card-body py-5">
            <div class="text-danger mb-4">
              <i class="bi bi-x-circle-fill" style="font-size: 4rem;"></i>
            </div>
            <h3 class="text-danger">Payment Failed</h3>
            <p class="text-muted lead">Pembayaran gagal atau dibatalkan.</p>
            
            <div class="alert alert-danger mt-4">
              <strong>Order ID:</strong> {{ $order->order_id }}<br>
              <strong>Amount:</strong> Rp {{ number_format($order->amount, 0, ',', '.') }}
            </div>

            <a href="{{ route('pricing') }}" class="btn btn-danger btn-lg mt-3">
              <i class="bi bi-arrow-left"></i> Coba Lagi
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection