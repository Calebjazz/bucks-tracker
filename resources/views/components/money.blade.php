{{--
    Formats an amount in Tanzanian shillings: <x-money :amount="1924500" /> → TZS 1,924,500
    Add signed and :type="'income'" to show + / − in front.
--}}
@props(['amount', 'signed' => false, 'type' => null])

@php
    $sign = $signed ? ($type === 'income' ? '+' : '−') : ($amount < 0 ? '−' : '');
@endphp

<span {{ $attributes->class('whitespace-nowrap tabular-nums') }}>{{ $sign }}TZS {{ number_format(abs($amount)) }}</span>
