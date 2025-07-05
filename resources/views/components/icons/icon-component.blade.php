<span class="icon @if(!empty($class)) {{ $class }} @endif">
    <x-icons.svg-icon-component
        :filename="$filename"
        :label="$altText"
        :title="$titleText"
    />
</span>
