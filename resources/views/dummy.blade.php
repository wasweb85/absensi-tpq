@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ $title ?? 'Halaman' }}</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info mt-3">
                    Halaman ini sedang dalam proses migrasi dari CodeIgniter 4 ke Laravel 11.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
