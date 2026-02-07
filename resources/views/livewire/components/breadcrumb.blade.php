<?php

use Livewire\Volt\Component;

new class extends Component {

    public array $breadcrumbs;

}; ?>

<div>
    {{-- Compruebo si hay breadcrumbs enviados al componente --}}
    @if (isset($breadcrumbs))
        <nav class="mb-4 mt-4">
            <ol class="flex flex-wrap ">
                @foreach ($breadcrumbs as $item)
                    <li
                        class="text-sm leading-normal text-slate-700 dark:text-white dark:bg-gray-900 {{ !$loop->first ? 'pl-2 before:float-left before:pr-2 before:content-["/"] ' : '' }}">
                        {{-- el !$loop->first es para que no se muestre el primer elemento --}}

                        @isset($item['route'])
                            <a href="{{ $item['route'] }}" class="opacity-50" wire:navigate>
                                {{ $item['name'] }}
                            </a>
                        @else
                            {{ $item['name'] }}
                        @endisset
                    </li>
                @endforeach
            </ol>

            @if (count($breadcrumbs) > 1)
                <h6 class="font-bold">
                    {{ end($breadcrumbs)['name'] }} {{-- Esto recupera el ultimo valor del array de breadcrumbs --}}
                </h6>
            @endif
        </nav>
    @endif
</div>
