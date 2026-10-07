@if ($modalAbierto && $modoEdicion)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-in fade-in duration-150">
        <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-2xl space-y-5">

            <!-- ENCABEZADO -->
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 text-emerald-600 rounded-xl bg-emerald-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold tracking-tight text-slate-900">Editar Puesto / Rol</h3>
                    <p class="text-xs font-medium text-slate-400">Actualiza la información del puesto.</p>
                </div>
            </div>

            <!-- FORMULARIO -->
            <form wire:submit.prevent="guardar" novalidate class="space-y-4">
                <div>
                    <label class="block mb-1.5 text-xs font-bold tracking-wide uppercase text-slate-600">
                        Nombre del Puesto
                    </label>
                    <input type="text" wire:model="nombre"
                        class="w-full px-3 py-2.5 text-sm font-medium border border-slate-300 rounded-lg placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 focus:outline-none transition">
                    @error('nombre')
                        <span class="block mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- BOTONES -->
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="cerrarModal"
                        class="px-4 py-2.5 text-sm font-semibold border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-4 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm shadow-emerald-600/20 transition cursor-pointer">
                        Actualizar
                    </button>
                </div>
            </form>

        </div>
    </div>
@endif