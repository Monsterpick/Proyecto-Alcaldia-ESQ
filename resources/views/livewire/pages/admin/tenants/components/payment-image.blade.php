@if($row->image_path)
    <div class="flex items-center space-x-2">
        @php
            $extension = pathinfo($row->image_path, PATHINFO_EXTENSION);
            $isPDF = strtolower($extension) === 'pdf';
        @endphp
        
        @if($isPDF)
            <div class="flex items-center justify-center w-8 h-8 bg-red-50 rounded-lg dark:bg-red-900/20">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        @else
            <div class="relative w-8 h-8 overflow-hidden rounded-lg">
                <img 
                    src="{{ Storage::url($row->image_path) }}" 
                    alt="Comprobante"
                    class="w-full h-full object-cover"
                />
            </div>
        @endif
        
        <a 
            href="{{ Storage::url($row->image_path) }}" 
            target="_blank"
            class="text-xs text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap"
        >
            Ver comprobante
        </a>
    </div>
@else
    <span class="text-xs text-gray-400 dark:text-gray-500">Sin comprobante</span>
@endif 