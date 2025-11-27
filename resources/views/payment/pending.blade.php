@extends('layouts.master')

@section('title', 'Payment Pending – BeatHive')

@section('content')
<div class="page-content">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card border-warning">
          <div class="card-body py-5">
            <div class="text-warning mb-4">
              <i class="bi bi-clock-fill" style="font-size: 4rem;"></i>
            </div>
            <h3 class="text-warning">Payment Pending</h3>
            <p class="text-muted lead">Menunggu konfirmasi pembayaran Anda.</p>
            
            <div class="alert alert-warning mt-4">
              <strong>Order ID:</strong> {{ $order->order_id }}<br>
              <strong>Amount:</strong> Rp {{ number_format($order->amount, 0, ',', '.') }}
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg mt-3">
              <i class="bi bi-house"></i> Kembali ke Dashboard
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection