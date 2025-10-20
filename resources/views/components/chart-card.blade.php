@props(['title', 'chartId', 'chartData'])

<div class="card-box">
    <h5 class="h5 text-dark mb-20 p-4">{{ $title }}</h5>
    <div class="p-4">
        <canvas id="{{ $chartId }}" width="400" height="400"></canvas>
    </div>
</div>