@extends('layouts.app')
@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div0 class="main-container container-fluid">
            <div class="page-header">
                <h1>Notifications</h1>
            </div>

            <div class="card">
                @forelse ($notifications as $item)
                <div class="card-body">
                    <div class="me-3 notifyimg {{ $item->type === "info-over-due" ? "bg-danger" : ($item->type === "info-due-date" ? "bg-warning" : "bg-info") }} brround box-shadow-primary">
                        <i class="fe fe-mail"></i>
                    </div>
                    <p class="{{ $item->type === "info-over-due" ? "text-danger" : ($item->type === "info-due-date" ? "text-warning" : "text-info") }}">{{ $item->content }}</p>
                    <p class="text-muted">{{ $item->date }}</p>
                </div>
                @empty
                <div class="card-body d-flex justify-content-center">
                    <p>Empty Notifications</p>
                </div>
                @endforelse
                <div class="card-body">
                    {{  $notifications->appends(request()->input())->links()}}
                </div>
            </div>
            </br></br></br></br>
        </div0>
    </div>
</div>
@endsection
