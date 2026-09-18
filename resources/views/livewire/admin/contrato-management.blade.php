<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Módulo de Contratos y Ventas</h1>
            <p class="text-sm text-slate-500">Formalización de terrenos, configuración de créditos y tablas de amortización</p>
        </div>

        @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
            || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))->where('crear', true)->exists())
            <button wire:click="abrirModalCrear" 
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer">
                <span>+ Formalizar Contrato</span>
            </button>
        @endif
    </div>

    {{-- Notificaciones --}}
    @if (session()->has('mensaje'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            {{ session('mensaje') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabla de Contratos --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <input wire:model.live.debounce.300ms="search" type="text" 
                placeholder="Buscar por Folio, Cliente o Manzana/Lote..." 
                class="w-full max-w-sm px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 text-sm outline-none">
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                    <th class="py-4 px-6">Folio / Inicio</th>
                    <th class="py-4 px-6">Cliente</th>
                    <th class="py-4 px-6">Lote Asignado</th>
                    <th class="py-4 px-6">Resumen Financiero</th>
                    <th class="py-4 px-6 text-center">Estado</th>
                    <th class="py-4 px-6 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse ($contratos as $c)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-900">{{ $c->folio }}</p>
                            <p class="text-xs text-slate-400">{{ $c->fecha_inicio->format('d/m/Y') }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-semibold text-slate-800">{{ $c->cliente->nombre_completo ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-400">{{ $c->cliente->telefono ?? '' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-medium text-slate-800">Mz. {{ $c->terreno->manzana }} — Lt. {{ $c->terreno->lote }}</p>
                            <p class="text-xs text-slate-400">{{ $c->terreno->superficie }} m²</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-800">${{ number_format($c->precio_total, 2) }}</span></p>
                            <p class="text-xs text-slate-500">Saldo: <span class="font-semibold text-rose-600">${{ number_format($c->saldo_actual, 2) }}</span></p>
                            <p class="text-[11px] text-slate-400">{{ $c->numero_cuotas }} pagos {{ strtolower($c->frecuencia_pago) }}s</p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $c->estado === 'ACTIVO' ? 'bg-emerald-100 text-emerald-800' : ($c->estado === 'LIQUIDADO' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ $c->estado }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-1 sm:space-x-2">
                            <button wire:click="verEstadoCuenta({{ $c->id }})" 
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                                📑 Estado de Cuenta
                            </button>

                            @if($c->estado === 'ACTIVO')
                                @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
                                    || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))->where('editar', true)->exists())
                                    <button wire:click="liquidarContrato({{ $c->id }})" 
                                        wire:confirm="¿Deseas marcar como LIQUIDADO el contrato {{ $c->folio }}? Todas las cuotas pendientes se marcarán como pagadas."
                                        class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                                        ✓ Liquidar
                                    </button>
                                @endif

                                @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
                                    || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))->where('eliminar', true)->exists())
                                    <button wire:click="cancelarContrato({{ $c->id }})" 
                                        wire:confirm="¿Seguro que deseas cancelar el contrato {{ $c->folio }}? El terreno volverá a estar DISPONIBLE."
                                        class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                                        Cancelar
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">No hay contratos formalizados aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $contratos->links() }}
        </div>
    </div>

    {{-- Modal Formalizar Contrato --}}
    @if($modalCrearAbierto)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-slate-800">Formalizar Nuevo Contrato de Venta</h3>

                <form wire:submit.prevent="guardarContrato" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Comprador (Cliente) *</label>
                            <select wire:model="cliente_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">Seleccione un cliente...</option>
                                @foreach($clientesDisponibles as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->nombre_completo }} ({{ $cl->telefono }})</option>
                                @endforeach
                            </select>
                            @error('cliente_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Terreno Disponible *</label>
                            <select wire:model.live="terreno_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">Seleccione un terreno...</option>
                                @foreach($terrenosDisponibles as $ter)
                                    <option value="{{ $ter->id }}">Mz. {{ $ter->manzana }} - Lt. {{ $ter->lote }} (${{ number_format($ter->precio, 2) }})</option>
                                @endforeach
                            </select>
                            @error('terreno_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Precio Total ($) *</label>
                            <input type="number" step="0.01" wire:model.live="precio_total" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('precio_total') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Enganche ($) *</label>
                            <input type="number" step="0.01" wire:model.live="enganche" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-emerald-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('enganche') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Saldo a Financiar ($)</label>
                            <div class="w-full px-3 py-2 bg-slate-200 text-rose-700 font-bold text-sm rounded-lg">
                                ${{ number_format($saldo_inicial, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nº de Cuotas *</label>
                            <input type="number" min="1" max="360" wire:model="numero_cuotas" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('numero_cuotas') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Frecuencia *</label>
                            <select wire:model="frecuencia_pago" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="SEMANAL">Semanal</option>
                                <option value="QUINCENAL">Quincenal</option>
                                <option value="MENSUAL">Mensual</option>
                                <option value="ANUAL">Anual</option>
                            </select>
                            @error('frecuencia_pago') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Fecha de Inicio *</label>
                            <input type="date" wire:model="fecha_inicio" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('fecha_inicio') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="cerrarModalCrear" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-semibold cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-md shadow-emerald-600/20 cursor-pointer">
                            Generar Venta y Tabla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Estado de Cuenta / Tabla de Amortización --}}
    @if($modalDetalleAbierto && $contratoSeleccionado)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 space-y-4 max-h-[90vh] flex flex-col">
                <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Estado de Cuenta — {{ $contratoSeleccionado->folio }}</h3>
                        <p class="text-xs text-slate-500">
                            Cliente: <span class="font-semibold text-slate-800">{{ $contratoSeleccionado->cliente->nombre_completo ?? 'N/A' }}</span> | 
                            Lote: <span class="font-semibold text-slate-800">Mz. {{ $contratoSeleccionado->terreno->manzana ?? '' }} Lt. {{ $contratoSeleccionado->terreno->lote ?? '' }}</span>
                        </p>
                    </div>
                    <button wire:click="cerrarModalDetalle" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">✕</button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs">
                    <div>
                        <span class="text-slate-400 block">Precio Lista:</span>
                        <span class="font-bold text-slate-800">${{ number_format($contratoSeleccionado->precio_total, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Enganche:</span>
                        <span class="font-bold text-emerald-600">${{ number_format($contratoSeleccionado->enganche, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Financiado:</span>
                        <span class="font-bold text-slate-800">${{ number_format($contratoSeleccionado->saldo_inicial, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Saldo Pendiente:</span>
                        <span class="font-bold text-rose-600">${{ number_format($contratoSeleccionado->saldo_actual, 2) }}</span>
                    </div>
                </div>

                {{-- Tabla de Cuotas / Amortización --}}
                <div class="overflow-y-auto flex-1 border border-slate-200 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-slate-100 border-b border-slate-200 font-semibold text-slate-600">
                            <tr>
                                <th class="p-3">#</th>
                                <th class="p-3">Vencimiento</th>
                                <th class="p-3 text-right">Monto Cuota</th>
                                <th class="p-3 text-right">Saldo Anterior</th>
                                <th class="p-3 text-right">Saldo Restante</th>
                                <th class="p-3 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($contratoSeleccionado->cuotas as $cuota)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3 font-semibold">{{ $cuota->numero_cuota }}</td>
                                    <td class="p-3">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                                    <td class="p-3 text-right font-medium text-slate-800">${{ number_format($cuota->monto, 2) }}</td>
                                    <td class="p-3 text-right text-slate-500">${{ number_format($cuota->saldo_anterior, 2) }}</td>
                                    <td class="p-3 text-right font-medium text-slate-800">${{ number_format($cuota->saldo_restante, 2) }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $cuota->estado === 'PAGADA' ? 'bg-emerald-100 text-emerald-800' : ($cuota->estado === 'VENCIDA' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $cuota->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400">No hay cuotas registradas para este contrato.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pie del Modal --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-3 border-t border-slate-100">
                    <div>
                        @if($contratoSeleccionado->estado === 'ACTIVO')
                            @if(auth()->user()->rol && in_array(strtolower(auth()->user()->rol->nombre), ['administrador', 'super admin', 'superadministrador']) 
                                || auth()->user()->permisos()->whereHas('modulo', fn($q) => $q->where('clave', 'contratos'))->where('editar', true)->exists())
                                <button wire:click="liquidarContrato({{ $contratoSeleccionado->id }})" 
                                    wire:confirm="¿Confirmas la liquidación total de la deuda por ${{ number_format($contratoSeleccionado->saldo_actual, 2) }}?"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-md shadow-emerald-600/20 transition cursor-pointer">
                                    ✓ Liquidar Deuda Total
                                </button>
                            @endif
                        @else
                            <span class="text-xs font-semibold text-slate-400 italic">
                                Contrato en estatus {{ $contratoSeleccionado->estado }}
                            </span>
                        @endif
                    </div>

                    <button type="button" wire:click="cerrarModalDetalle" 
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition cursor-pointer">
                        Cerrar Estado de Cuenta
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>