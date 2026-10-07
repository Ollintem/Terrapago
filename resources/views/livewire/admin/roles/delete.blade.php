@if($rolAEliminar)
    <!-- FONDO OSCURO CON BLUR (BACKDROP) -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-opacity">
        
        <!-- TARJETA DEL MODAL (ESTILO EXACTO AL DE EDITAR) -->
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 space-y-6 animate-in fade-in zoom-in-95 duration-150">
            
            <!-- HEADER -->
            <div class="flex items-center gap-3.5">
                <div class="h-10 w-10 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0 border border-rose-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Eliminar Puesto / Rol</h3>
                    <p class="text-xs text-slate-400">Esta acción no se puede deshacer.</p>
                </div>
            </div>

            <!-- CAMPO CON EL NOMBRE DEL PUESTO -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nombre del puesto</label>
                <div class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700">
                    {{ $rolAEliminar->nombre }}
                </div>
            </div>

            <!-- ADVERTENCIA SI TIENE USUARIOS ASIGNADOS -->
            @if($rolAEliminar->users_count > 0)
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Este puesto tiene <strong>{{ $rolAEliminar->users_count }} usuario(s) asignado(s)</strong>. Para poder eliminarlo primero debes reasignarlos.
                    </p>
                </div>
            @endif

            <!-- BOTONES -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button 
                    type="button" 
                    wire:click="cancelarEliminar" 
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition cursor-pointer">
                    Cancelar
                </button>

                @if($rolAEliminar->users_count == 0)
                    <button 
                        type="button" 
                        wire:click="ejecutarEliminar" 
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm shadow-sm transition cursor-pointer">
                        Eliminar
                    </button>
                @endif
            </div>

        </div>
    </div>
@endif