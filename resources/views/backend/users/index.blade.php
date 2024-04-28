@extends('backend.layouts.app')
 
@section('content')
<div class="box">
    <div class="row">
        <div class="col-sm-12">
            <div class="container">
                <div class="card">
                    <div class="card-header">Manage Users</div>
                    <div class="card-body">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

