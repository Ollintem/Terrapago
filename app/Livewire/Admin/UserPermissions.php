<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Modulo;
use App\Models\Permiso;

class UserPermissions extends Component
{
    public $user;
    public $modulos;
    // Estructura: [modulo_id => ['mostrar' => bool, 'crear' => bool, 'editar' => bool, 'eliminar' => bool]]
    public $permisosSeleccionados = [];

    public function mount($id)
    {
        $this->user = User::with('rol')->findOrFail($id);
        $this->modulos = Modulo::all();

        // Cargar permisos existentes desde la base de datos
        foreach ($this->modulos as $modulo) {
            $permiso = Permiso::where('user_id', $this->user->id)
                ->where('modulo_id', $modulo->id)
                ->first();

            $this->permisosSeleccionados[$modulo->id] = [
                'mostrar'  => (bool) ($permiso->mostrar ?? false),
                'crear'     => (bool) ($permiso->crear ?? false),
                'editar'   => (bool) ($permiso->editar ?? false),
                'eliminar' => (bool) ($permiso->eliminar ?? false),
            ];
        }
    }

    // Funciones para los botones de selección masiva
    public function seleccionarTodo()
    {
        foreach ($this->modulos as $modulo) {
            $this->permisosSeleccionados[$modulo->id] = [
                'mostrar'  => true,
                'crear'     => true,
                'editar'   => true,
                'eliminar' => true,
            ];
        }
    }

    public function deseleccionarTodo()
    {
        foreach ($this->modulos as $modulo) {
            $this->permisosSeleccionados[$modulo->id] = [
                'mostrar'  => false,
                'crear'     => false,
                'editar'   => false,
                'eliminar' => false,
            ];
        }
    }

    public function guardar()
    {
        foreach ($this->permisosSeleccionados as $moduloId => $acciones) {
            Permiso::updateOrCreate(
                [
                    'user_id'   => $this->user->id,
                    'modulo_id' => $moduloId,
                ],
                [
                    'mostrar'  => $acciones['mostrar'] ?? false,
                    'crear'     => $acciones['crear'] ?? false,
                    'editar'   => $acciones['editar'] ?? false,
                    'eliminar' => $acciones['eliminar'] ?? false,
                ]
            );
        }

        session()->flash('mensaje', 'Permisos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.user-permissions')
            ->layout('layouts.app');
    }
}