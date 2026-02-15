{{-- Formulario --}}
<form action="{{ route('web.page.atencion_ciudadana.store') }}" method="POST" class="space-y-4 sm:space-y-6">
    @csrf

    {{-- Sección: Datos Personales --}}
    <div class="border-b-2 border-gray-200 pb-6 sm:pb-8 md:pb-10">
        <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-6 sm:mb-8 flex items-center gap-2 sm:gap-3">
            <div class="p-2 sm:p-2.5 bg-blue-100 rounded-lg flex-shrink-0">
                <i class="fa-solid fa-user-circle text-primary text-base sm:text-lg"></i>
            </div>
            <span>Datos Personales</span>
        </h3>

        {{-- Fila 1: Cédula y Nombre --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 md:gap-6 lg:gap-7 mb-4 sm:mb-5 md:mb-6">
            {{-- Cédula --}}
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-id-card w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Cédula *</span>
                </label>
                <input type="text" name="cedula" maxlength="8" placeholder="Ej: 12345678" value="{{ old('cedula') }}"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 group-hover:scale-[1.01] placeholder-gray-400">
            </div>

            {{-- Nombre --}}
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-user w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Nombre *</span>
                </label>
                <input type="text" name="nombre" maxlength="100" placeholder="Ej: Juan" value="{{ old('nombre') }}"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 group-hover:scale-[1.01] placeholder-gray-400">
            </div>
        </div>

        {{-- Fila 2: Apellido y Email --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 md:gap-6 lg:gap-7 mb-4 sm:mb-5 md:mb-6">
            {{-- Apellido --}}
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-user w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Apellido *</span>
                </label>
                <input type="text" name="apellido" maxlength="100" placeholder="Ej: Pérez" value="{{ old('apellido') }}"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 group-hover:scale-[1.01] placeholder-gray-400">
            </div>

            {{-- Email --}}
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-envelope w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Email *</span>
                </label>
                <input type="email" name="email" placeholder="Ej: juan@example.com" value="{{ old('email') }}"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 group-hover:scale-[1.01] placeholder-gray-400">
            </div>
        </div>

        {{-- Fila 3: Teléfono y WhatsApp --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 md:gap-6 lg:gap-7">
            {{-- Teléfono Móvil --}}
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-phone w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Teléfono Móvil *</span>
                </label>
                <input type="tel" name="telefono_movil" maxlength="11" placeholder="Ej: 4241234567" value="{{ old('telefono_movil') }}"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 group-hover:scale-[1.01] placeholder-gray-400">
            </div>

            {{-- WhatsApp --}}
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-brands fa-whatsapp w-3 h-3 sm:w-4 sm:h-4 text-success flex-shrink-0"></i>
                    <span>WhatsApp *</span>
                </label>
                <input type="tel" name="whatsapp" maxlength="11" placeholder="Ej: 4241234567" value="{{ old('whatsapp') }}"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 group-hover:scale-[1.01] placeholder-gray-400">
            </div>
        </div>
    </div>

    {{-- Sección: Tipo de Solicitud --}}
    <div class="border-b-2 border-gray-200 pb-6 sm:pb-8 md:pb-10">
        <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-6 sm:mb-8 flex items-center gap-2 sm:gap-3">
            <div class="p-2 sm:p-2.5 bg-blue-100 rounded-lg flex-shrink-0">
                <i class="fa-solid fa-list-check text-primary text-base sm:text-lg"></i>
            </div>
            <span>Tipo de Solicitud</span>
        </h3>

        <div class="group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-3 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                <i class="fa-solid fa-arrow-right w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                <span>¿Qué solicitas? *</span>
            </label>
            <select id="tipo_solicitud" name="tipo_solicitud" onchange="mostrarSeccion()"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <option value="">-- Selecciona una opción --</option>
                <option value="derecho_palabra" {{ old('tipo_solicitud') == 'derecho_palabra' ? 'selected' : '' }}>Solicitar Derecho de Palabra</option>
                <option value="atencion" {{ old('tipo_solicitud') == 'atencion' ? 'selected' : '' }}>Solicitar Atención Inmediata</option>
            </select>
        </div>
    </div>

    {{-- Sección: Derecho de Palabra --}}
    <div id="seccion_derecho_palabra" class="border-b-2 border-gray-200 pb-6 sm:pb-8 md:pb-10 hidden animate-fade-in">
        <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-6 sm:mb-8 flex items-center gap-2 sm:gap-3">
            <div class="p-2 sm:p-2.5 bg-blue-100 rounded-lg flex-shrink-0">
                <i class="fa-solid fa-microphone text-primary text-base sm:text-lg"></i>
            </div>
            <span>Información - Derecho de Palabra</span>
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 md:gap-6 lg:gap-7 mb-4 sm:mb-5 md:mb-6">
            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-calendar-check w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Sesión Municipal *</span>
                </label>
                <select name="sesion_municipal_id"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                    <option value="">-- Selecciona una sesión --</option>
                    @forelse($sesionesProximas as $sesion)
                        <option value="{{ $sesion['id'] }}" {{ old('sesion_municipal_id') == $sesion['id'] ? 'selected' : '' }}>
                            {{ $sesion['titulo'] }} ({{ $sesion['fecha_hora'] }})
                        </option>
                    @empty
                        <option value="" disabled>No hay sesiones disponibles</option>
                    @endforelse
                </select>
            </div>

            <div class="group">
                <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                    <i class="fa-solid fa-users w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                    <span>Comisión (Opcional)</span>
                </label>
                <select name="comision_id"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                    <option value="">-- Sin comisión --</option>
                    @if(isset($comisiones))
                        @foreach($comisiones as $comision)
                            <option value="{{ $comision->id }}" {{ old('comision_id') == $comision->id ? 'selected' : '' }}>
                                {{ $comision->nombre }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                <i class="fa-solid fa-pen-to-square w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                <span>Motivo de la Solicitud *</span>
            </label>
            <textarea name="motivo_solicitud" id="motivo_solicitud" maxlength="1000" rows="4"
                placeholder="Explica brevemente por qué solicitas derecho de palabra..."
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl resize-none focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 placeholder-gray-400">{{ old('motivo_solicitud') }}</textarea>
            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-2 mt-2 sm:mt-3 text-[10px] sm:text-xs md:text-sm text-gray-500">
                <p class="flex items-center gap-1"><i class="fa-solid fa-info-circle text-primary"></i> <span>Máximo 1000 caracteres</span></p>
                <p class="font-semibold text-primary"><span id="contador_motivo">1000</span> restantes</p>
            </div>
        </div>
    </div>

    {{-- Sección: Atención Inmediata --}}
    <div id="seccion_atencion_inmediata" class="border-b-2 border-gray-200 pb-6 sm:pb-8 md:pb-10 hidden animate-fade-in">
        <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-6 sm:mb-8 flex items-center gap-2 sm:gap-3">
            <div class="p-2 sm:p-2.5 bg-blue-100 rounded-lg flex-shrink-0">
                <i class="fa-solid fa-headset text-primary text-base sm:text-lg"></i>
            </div>
            <span>Información - Atención Inmediata</span>
        </h3>

        <div class="mb-4 sm:mb-5 md:mb-6 group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                <i class="fa-solid fa-folder w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                <span>Tipo de Solicitud *</span>
            </label>
            <select name="tipo_solicitud_id"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <option value="">-- Selecciona un tipo de solicitud --</option>
                @if(isset($tiposSolicitud))
                    @foreach($tiposSolicitud as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_solicitud_id') == $tipo->id ? 'selected' : '' }}
                            title="{{ $tipo->descripcion }}">
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
            <p class="mt-2 text-xs text-gray-500">
                <i class="fa-solid fa-info-circle text-primary"></i>
                Selecciona el tipo de solicitud que mejor describa tu necesidad
            </p>
        </div>

        <div class="group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-2.5 text-xs sm:text-sm md:text-base font-semibold text-gray-700">
                <i class="fa-solid fa-pen-to-square w-3 h-3 sm:w-4 sm:h-4 text-primary flex-shrink-0"></i>
                <span>Descripción de la Solicitud *</span>
            </label>
            <textarea name="descripcion" id="descripcion" maxlength="2000" rows="6"
                placeholder="Describe detalladamente tu solicitud..."
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 bg-gray-50 border border-gray-300 rounded-xl sm:rounded-2xl resize-none focus:ring-2 focus:ring-primary focus:border-primary shadow-sm hover:shadow-md transition-all duration-300 placeholder-gray-400">{{ old('descripcion') }}</textarea>
            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-2 mt-2 sm:mt-3 text-[10px] sm:text-xs md:text-sm text-gray-500">
                <p class="flex items-center gap-1"><i class="fa-solid fa-info-circle text-primary"></i> <span>Máximo 2000 caracteres</span></p>
                <p class="font-semibold text-primary"><span id="contador_descripcion">2000</span> restantes</p>
            </div>
        </div>
    </div>

    {{-- Términos y Condiciones --}}
    <div class="pb-4 sm:pb-6 md:pb-8">
        <div class="flex items-start gap-2 sm:gap-3 p-3 sm:p-4 md:p-5 bg-blue-50 border-l-4 border-primary rounded-lg sm:rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
            <input type="checkbox" name="acepta_terminos" id="terminos" value="1" {{ old('acepta_terminos') ? 'checked' : '' }}
                class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-primary rounded focus:ring-primary cursor-pointer flex-shrink-0 mt-0.5 sm:mt-1 accent-primary">
            <label for="terminos" class="text-[10px] sm:text-xs md:text-sm text-gray-700 cursor-pointer">
                Acepto los <span class="font-semibold text-primary">términos y condiciones</span> y confirmo que la información proporcionada es correcta y verídica.
            </label>
        </div>
    </div>

    {{-- Botones --}}
    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 pt-6 sm:pt-8 border-t-2 border-gray-200">

        {{-- ===== BOTÓN ENVIAR ===== --}}
        <button type="submit" id="btnEnviar" class="w-full py-3 sm:py-4 rounded-lg font-semibold text-sm sm:text-lg flex items-center justify-center gap-2 hover:opacity-90 transition-all duration-300 transform hover:scale-105 hover:shadow-lg text-white" style="background: var(--button-color, #4f46e5);">
             <i class="fas fa-paper-plane text-white animate-bounce"></i>
            <span>Enviar Solicitud</span>
        </button>
    </div>
</form>

@include('web.page.participacion_ciudadana.js.sweetalert')



<!-- Botón animation -->
<style>
    @keyframes floatIcon {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-8px) rotate(5deg);
        }
    }

    #btnEnviar i:first-child {
        animation: floatIcon 2s ease-in-out infinite;
    }
</style>



<script>
function mostrarSeccion() {
    const tipoSolicitud = document.getElementById('tipo_solicitud').value;
    const seccionDerechoPalabra = document.getElementById('seccion_derecho_palabra');
    const seccionAtencion = document.getElementById('seccion_atencion_inmediata');

    seccionDerechoPalabra.classList.add('hidden');
    seccionAtencion.classList.add('hidden');

    if (tipoSolicitud === 'derecho_palabra') {
        seccionDerechoPalabra.classList.remove('hidden');
    } else if (tipoSolicitud === 'atencion') {
        seccionAtencion.classList.remove('hidden');
    }
}

// Contador de caracteres para motivo
const motivo = document.getElementById('motivo_solicitud');
if (motivo) {
    motivo.addEventListener('input', function() {
        const contador = document.getElementById('contador_motivo');
        if (contador) {
            contador.textContent = 1000 - this.value.length;
        }
    });
}

// Contador de caracteres para descripción
const descripcion = document.getElementById('descripcion');
if (descripcion) {
    descripcion.addEventListener('input', function() {
        const contador = document.getElementById('contador_descripcion');
        if (contador) {
            contador.textContent = 2000 - this.value.length;
        }
    });
}

// Inicializar al cargar el documento
document.addEventListener('DOMContentLoaded', function() {
    mostrarSeccion();

    // Inicializar contadores si hay valores antiguos
    if (motivo && motivo.value) {
        document.getElementById('contador_motivo').textContent = 1000 - motivo.value.length;
    }
    if (descripcion && descripcion.value) {
        document.getElementById('contador_descripcion').textContent = 2000 - descripcion.value.length;
    }
});
</script>
