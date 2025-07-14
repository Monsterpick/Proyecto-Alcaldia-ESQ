@if ($errors->any())
    <div {{ $attributes }}>
        {{-- <div class="font-medium text-red-600 dark:text-red-400">¡Oh no! Algo salió mal. :(</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul> --}}

        <x-alert title="¡Oh no! Algo salió mal. :(" negative>
            @foreach ($errors->all() as $error)
                <x-slot name="slot" class="italic">
        
                    {{ $error }}
                </x-slot>
            @endforeach
        </x-alert>
    </div>
@endif