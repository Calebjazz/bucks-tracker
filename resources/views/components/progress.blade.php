{{-- Budget progress bar. status: ok | warning | exceeded --}}
@props(['value', 'max', 'status' => 'ok', 'label' => 'Progress'])

@php
    $percent = $max > 0 ? $value / $max * 100 : 0;
    $colors = ['ok' => 'bg-brand-600', 'warning' => 'bg-amber-500', 'exceeded' => 'bg-red-600'];
@endphp

<div role="progressbar" aria-label="{{ $label }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ round($percent) }}"
    {{ $attributes->class('h-2 w-full overflow-hidden rounded-full bg-slate-100') }}>
    <div class="h-full rounded-full {{ $colors[$status] }}" style="width: {{ min(100, $percent) }}%"></div>
</div>
