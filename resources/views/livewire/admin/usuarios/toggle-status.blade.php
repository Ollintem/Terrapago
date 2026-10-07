@if($usuarioAAccionar)
    <!-- FONDO OSCURO CON BLUR (BACKDROP) -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-opacity">
        
        <!-- TARJETA DEL MODAL -->
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 space-y-6 animate-in fade-in zoom-in-95 duration-150">
            
            <!-- HEADER -->
            <div class="flex items-center gap-3.5">
                <div class="h-10 w-10 rounded-2xl {{ $usuarioAAccionar->activo ? 'bg-amber-50 text-amber-500 border-amber-100' : 'bg-emerald-50 text-emerald-500 border-emerald-100' }} flex items-center justify-center flex-shrink-0 border">
                    @if($usuarioAAccionar->activo)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">
                        {{ $usuarioAAccionar->activo ? 'Desactivar Cuenta' : 'Activar Cuenta' }}
                    </h3>
                    <p class="text-xs text-slate-400">Control de acceso al sistema</p>
                </div>
            </div>

            <!-- DATOS DEL USUARIO -->
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nombre del Usuario</label>
                    <div class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700">
                        {{ $usuarioAAccionar->nombre ?? $usuarioAAccionar->name }}
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Correo Electrónico</label>
                    <div class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">
                        {{ $usuarioAAccionar->email }}
                    </div>
                </div>
            </div>

            <!-- ADVERTENCIA / MENSAJE EXPLICATIVO -->
            <div class="p-3 {{ $usuarioAAccionar->activo ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }} border rounded-xl flex items-start gap-2.5">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-[11px] leading-relaxed">
                    @if($usuarioAAccionar->activo)
                        Al desactivar este usuario, <strong>se cerrará su sesión y no podrá acceder</strong> a ninguno de los módulos hasta que sea reactivado.
                    @else
                        Al reactivar al usuario, <strong>podrá volver a iniciar sesión</strong> y utilizar sus permisos asignados con normalidad.
                    @endif
                </p>
            </div>

            <!-- BOTONES -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button 
                    type="button" 
                    wire:click="cancelarAccionUsuario" 
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition cursor-pointer">
                    Cancelar
                </button>

                <button 
                    type="button" 
                    wire:click="ejecutarToggleEstado" 
                    class="px-5 py-2.5 rounded-xl {{ $usuarioAAccionar->activo ? 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/20' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20' }} text-white font-semibold text-sm shadow-sm transition cursor-pointer">
                    {{ $usuarioAAccionar->activo ? 'Sí, desactivar' : 'Sí, activar' }}
                </button>
            </div>

        </div>
    </div>
@endif