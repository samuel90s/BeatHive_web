<!-- Menghubungkan dengan view template master -->
@extends('master')

<!-- isi bagian judul halaman -->
<!-- cara penulisan isi section yang pendek -->
@section('title', 'Index | BeatHive')


<!-- isi bagian konten -->
<!-- cara penulisan isi section yang panjang -->
@section('content')

<div class="content-wrapper container">
    <div class="page-heading">
        <h3>Dashboard – Stock Music</h3>
        <p class="text-muted">Ringkasan performa marketplace BeatHive kamu.</p>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <!-- KPI Cards -->
                <div class="row g-4">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded me-3"
                                        style="width:46px; height:46px;">
                                        <i class="bi bi-soundwave fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">1,245</h5>
                                        <h6 class="mb-0 text-muted fs-6">Total Tracks</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white d-flex align-items-center justify-content-center rounded me-3"
                                        style="width:46px; height:46px;">
                                        <i class="bi bi-download fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">6,980</h5>
                                        <h6 class="mb-0 text-muted fs-6">Downloads (30d)</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white d-flex align-items-center justify-content-center rounded me-3"
                                        style="width:46px; height:46px;">
                                        <i class="bi bi-currency-dollar fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">125,450,000</h5>
                                        <h6 class="mb-0 text-muted fs-6">Revenue (IDR)</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-12 col-lg-3">
                <!-- Profile card -->
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl"><img src="./assets/compiled/jpg/1.jpg"
                                    alt="Face 1" /></div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">BeatHive</h5>
                                <h6 class="text-muted mb-0">@beathive</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection