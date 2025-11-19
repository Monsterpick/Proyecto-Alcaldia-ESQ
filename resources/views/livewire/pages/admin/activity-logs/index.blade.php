<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $logName = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 25;

    public function mount()
    {
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLogName()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'logName', 'dateFrom', 'dateTo']);
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Activity::query()
            ->with('causer')
            ->latest();

        // Filtro por nombre de log
        if ($this->logName) {
            $query->where('log_name', $this->logName);
        }

        // Filtro por búsqueda en descripción
        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        // Filtro por rango de fechas
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $activities = $query->paginate($this->perPage);

        // Obtener tipos de logs únicos
        $logTypes = Activity::select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        // Estadísticas
        $stats = [
            'total' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'week' => Activity::whereBetween('created_at', [now()->subDays(7), now()])->count(),
            'telegram' => Activity::where('log_name', 'telegram')->count(),
            'system' => Activity::where('log_name', 'system')->count(),
            'errors' => Activity::where('log_name', 'error')->count(),
        ];

        return [
            'activities' => $activities,
            'logTypes' => $logTypes,
            'stats' => $stats,
        ];
    }
}; ?>

<div class="min-h-screen bg-gray-950">
    <x-container class="w-full px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Registro de Actividades</h1>
                <p class="text-gray-400 text-sm mt-1">Historial completo de acciones del sistema y bot de Telegram</p>
            </div>
            <div class="text-gray-400 text-sm">
                <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-gray-900 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-xs font-medium">Total</h3>
                    <i class="fas fa-list text-blue-500"></i>
                </div>
                <div class="text-2xl font-bold text-blue-400">{{ number_format($stats['total']) }}</div>
            </div>

            <div class="bg-gray-900 border-l-4 border-green-500 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-xs font-medium">Hoy</h3>
                    <i class="fas fa-calendar-day text-green-500"></i>
                </div>
                <div class="text-2xl font-bold text-green-400">{{ number_format($stats['today']) }}</div>
            </div>

            <div class="bg-gray-900 border-l-4 border-purple-500 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-xs font-medium">Esta Semana</h3>
                    <i class="fas fa-calendar-week text-purple-500"></i>
                </div>
                <div class="text-2xl font-bold text-purple-400">{{ number_format($stats['week']) }}</div>
            </div>

            <div class="bg-gray-900 border-l-4 border-cyan-500 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-xs font-medium">Telegram</h3>
                    <i class="fab fa-telegram text-cyan-500"></i>
                </div>
                <div class="text-2xl font-bold text-cyan-400">{{ number_format($stats['telegram']) }}</div>
            </div>

            <div class="bg-gray-900 border-l-4 border-yellow-500 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-xs font-medium">Sistema</h3>
                    <i class="fas fa-server text-yellow-500"></i>
                </div>
                <div class="text-2xl font-bold text-yellow-400">{{ number_format($stats['system']) }}</div>
            </div>

            <div class="bg-gray-900 border-l-4 border-red-500 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 text-xs font-medium">Errores</h3>
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="text-2xl font-bold text-red-400">{{ number_format($stats['errors']) }}</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Búsqueda -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar en descripción..."
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500"
                    >
                </div>

                <!-- Tipo de Log -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        <i class="fas fa-filter mr-2"></i>Tipo de Log
                    </label>
                    <select 
                        wire:model.live="logName"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
                    >
                        <option value="">Todos</option>
                        @foreach($logTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        <i class="fas fa-calendar-alt mr-2"></i>Desde
                    </label>
                    <input 
                        type="date" 
                        wire:model.live="dateFrom"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
                    >
                </div>

                <!-- Fecha Hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        <i class="fas fa-calendar-alt mr-2"></i>Hasta
                    </label>
                    <input 
                        type="date" 
                        wire:model.live="dateTo"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
                    >
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-800">
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg transition-colors"
                >
                    <i class="fas fa-redo mr-2"></i>Restablecer Filtros
                </button>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-400">Mostrar:</label>
                    <select 
                        wire:model.live="perPage"
                        class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1 text-white focus:outline-none focus:border-blue-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabla de Actividades -->
        <div class="bg-gray-900 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Fecha/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-gray-850 transition-colors" x-data="{ open: false }">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $activity->created_at->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $activity->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColors = [
                                            'telegram' => 'bg-cyan-900/50 text-cyan-400 border-cyan-500/50',
                                            'system' => 'bg-yellow-900/50 text-yellow-400 border-yellow-500/50',
                                            'model' => 'bg-blue-900/50 text-blue-400 border-blue-500/50',
                                            'auth' => 'bg-green-900/50 text-green-400 border-green-500/50',
                                            'error' => 'bg-red-900/50 text-red-400 border-red-500/50',
                                            'default' => 'bg-gray-800 text-gray-400 border-gray-600'
                                        ];
                                        $color = $badgeColors[$activity->log_name] ?? $badgeColors['default'];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $color }}">
                                        {{ ucfirst($activity->log_name) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    <div class="max-w-md">
                                        {{ $activity->description }}
                                        @if($activity->log_name === 'telegram' && isset($activity->properties['telegram_user']))
                                            <div class="text-xs text-gray-500 mt-1">
                                                <i class="fab fa-telegram mr-1"></i>
                                                @if(isset($activity->properties['telegram_user']['username']))
                                                    @{{ $activity->properties['telegram_user']['username'] }}
                                                @else
                                                    {{ $activity->properties['telegram_user']['first_name'] ?? 'Usuario' }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    @if($activity->causer)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold mr-2">
                                                {{ substr($activity->causer->name ?? 'S', 0, 1) }}
                                            </div>
                                            <span>{{ $activity->causer->name ?? 'Sistema' }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-500 italic">Sistema</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($activity->properties->isNotEmpty())
                                        <button 
                                            @click="open = !open"
                                            class="text-blue-400 hover:text-blue-300 transition-colors"
                                        >
                                            <i class="fas fa-eye" x-show="!open"></i>
                                            <i class="fas fa-eye-slash" x-show="open" style="display: none;"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-600">-</span>
                                    @endif
                                </td>
                            </tr>
                            @if($activity->properties->isNotEmpty())
                                <tr x-show="open" x-transition style="display: none;" class="bg-gray-850">
                                    <td colspan="5" class="px-6 py-4">
                                        <div class="bg-gray-800 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-white mb-3 flex items-center">
                                                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                                                Detalles de la Actividad
                                            </h4>
                                            <pre class="text-xs text-gray-300 overflow-x-auto bg-gray-900 p-3 rounded"><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-600 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">No se encontraron actividades</p>
                                    <p class="text-gray-600 text-sm mt-2">Intenta ajustar los filtros de búsqueda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($activities->hasPages())
                <div class="bg-gray-800 px-6 py-4">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </x-container>
</div>
