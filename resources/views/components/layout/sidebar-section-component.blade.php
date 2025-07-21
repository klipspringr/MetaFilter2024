<section @if (!empty($class)) class="{{ $class }}" @endif>
    @if ($open === true)
        <details open>
    @else
        <details>
    @endif
        <summary>
            <h3>{{  trans($heading) }}</h3>
            <x-icons.icon-component class="chevron-details" filename="chevron-details"/>
        </summary>

        {{ $slot }}
    </details>
{{-- Error is a false positive because of the conditional opening details tag --}}
</section>
