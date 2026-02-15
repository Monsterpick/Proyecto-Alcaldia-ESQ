<form wire:submit="enviarSolicitud" class="space-y-8">

    {{-- Sección: Datos Personales --}}
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-escuque-red rounded-lg flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"/></svg>
            </div>
            <span>Datos Personales</span>
        </h3>
        <div class="grid md:grid-cols-2 gap-5">
            {{-- Cédula --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Cédula <span class="text-escuque-red">*</span></label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-700 font-semibold text-sm">V-</span>
                    <input type="text" wire:model.blur="cedula" placeholder="12345678" inputmode="numeric" maxlength="8"
                        class="flex-1 px-4 py-3 text-base border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('cedula') border-red-500 ring-1 ring-red-500 @enderror">
                </div>
                @error('cedula') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-escuque-red">*</span></label>
                <input type="email" wire:model.blur="email" placeholder="tucorreo@ejemplo.com"
                    class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('email') border-red-500 ring-1 ring-red-500 @enderror">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre <span class="text-escuque-red">*</span></label>
                <input type="text" wire:model.blur="nombre" placeholder="Tu nombre"
                    class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('nombre') border-red-500 ring-1 ring-red-500 @enderror">
                @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Apellido --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Apellido <span class="text-escuque-red">*</span></label>
                <input type="text" wire:model.blur="apellido" placeholder="Tu apellido"
                    class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('apellido') border-red-500 ring-1 ring-red-500 @enderror">
                @error('apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Teléfono Móvil --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Teléfono Móvil <span class="text-escuque-red">*</span></label>
                <input type="tel" wire:model.blur="telefono_movil" placeholder="04121234567" inputmode="tel" maxlength="11"
                    class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('telefono_movil') border-red-500 ring-1 ring-red-500 @enderror">
                @error('telefono_movil') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- WhatsApp --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    WhatsApp <span class="text-escuque-red">*</span>
                    <span class="text-xs text-gray-400 font-normal ml-1">(donde recibirás confirmación)</span>
                </label>
                <input type="tel" wire:model.blur="whatsapp" placeholder="04121234567" inputmode="tel" maxlength="11"
                    class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('whatsapp') border-red-500 ring-1 ring-red-500 @enderror">
                @error('whatsapp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Sección: Solicitud --}}
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-escuque-gold rounded-lg flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94A48.972 48.972 0 0012 2.25c-2.291 0-4.545.16-6.336.468C4.125 3.297 3 4.603 3 6.108v8.142a3 3 0 003 3h1.5V18a.75.75 0 00.75.75h4.5a.75.75 0 000-1.5h-3.75v-.375z" clip-rule="evenodd"/></svg>
            </div>
            <span>Detalles de la Solicitud</span>
        </h3>

        {{-- Tipo de servicio --}}
        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Servicio <span class="text-escuque-red">*</span></label>
            <select wire:model.blur="tipo_solicitud_id"
                class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('tipo_solicitud_id') border-red-500 ring-1 ring-red-500 @enderror">
                <option value="">Seleccione un servicio</option>
                @foreach($tiposSolicitud as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
            @error('tipo_solicitud_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Dirección --}}
        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">Dirección <span class="text-escuque-red">*</span></label>
            <input type="text" wire:model.blur="direccion" placeholder="Calle, sector, urbanización, casa/edificio..."
                class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent @error('direccion') border-red-500 ring-1 ring-red-500 @enderror">
            @error('direccion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742z" clip-rule="evenodd"/></svg>
                Indique su dirección completa para facilitar la atención
            </p>
        </div>

        {{-- Descripción --}}
        <div x-data="{ chars: 0 }">
            <label class="block text-sm font-bold text-gray-700 mb-2">Descripción de tu solicitud <span class="text-escuque-red">*</span></label>
            <textarea wire:model.blur="descripcion"
                rows="5"
                maxlength="2000"
                x-on:input="chars = $event.target.value.length"
                placeholder="Describe tu solicitud con el mayor detalle posible..."
                class="w-full min-h-[120px] sm:min-h-[140px] px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent resize-none text-base @error('descripcion') border-red-500 ring-1 ring-red-500 @enderror"></textarea>
            <div class="flex justify-between mt-1.5">
                @error('descripcion')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @else
                    <p class="text-xs text-gray-500">Mínimo 10 caracteres, máximo 2000</p>
                @enderror
                <p class="text-xs font-semibold" :class="chars > 1900 ? 'text-red-600' : 'text-gray-500'"><span x-text="2000 - chars">2000</span> restantes</p>
            </div>
        </div>
    </div>

    {{-- Términos y condiciones --}}
    <div>
        <label class="flex items-start cursor-pointer group">
            <input type="checkbox" wire:model="acepta_terminos"
                class="mt-1 w-5 h-5 text-red-700 border-gray-300 rounded focus:ring-red-600 focus:ring-2 @error('acepta_terminos') border-red-500 ring-1 ring-red-500 @enderror">
            <span class="ml-3 text-sm text-gray-700">
                Acepto los <a href="#" class="text-escuque-red hover:text-red-800 font-bold">términos y condiciones</a>
                y autorizo el tratamiento de mis datos personales.
            </span>
        </label>
        @error('acepta_terminos') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Botón enviar --}}
    <div class="text-center pt-4 overflow-visible">
        <button type="submit"
            wire:loading.attr="disabled"
            wire:target="enviarSolicitud"
            class="inline-flex items-center justify-center gap-3 px-8 sm:px-12 py-4 min-h-[52px] bg-escuque-red hover:bg-red-800 text-white rounded-xl font-bold text-base sm:text-lg shadow-xl transition-all duration-300 hover:scale-105 whitespace-nowrap w-full sm:w-auto disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100">

            <span wire:loading.remove wire:target="enviarSolicitud" class="inline-flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z"/></svg>
                <span>Enviar Solicitud</span>
            </span>

            <span wire:loading wire:target="enviarSolicitud" class="inline-flex items-center gap-3">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Enviando...</span>
            </span>
        </button>
    </div>
</form>
