{{-- Budget status label that never relies on colour alone: icon + text. --}}
@props(['status'])

@if ($status === 'exceeded')
    <x-badge variant="danger" icon="alert-circle" {{ $attributes }}>Over budget</x-badge>
@elseif ($status === 'warning')
    <x-badge variant="warning" icon="alert-triangle" {{ $attributes }}>Almost there</x-badge>
@else
    <x-badge variant="success" icon="check" {{ $attributes }}>On track</x-badge>
@endif
