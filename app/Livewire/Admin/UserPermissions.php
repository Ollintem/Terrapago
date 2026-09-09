<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Modulo;
use App\Models\Permiso;
use Livewire\Component;

class UserPermissions extends Component
{
    public User $usuario;
    public $permisosMatriz = [];

    public function mount(User $user)
    {
        $this->usuario = $user;
        $this->cargarMatriz();
    }

    public function cargarMatriz()
    {
        $modulos = Modulo::all();
        
        foreach ($modulos as $modulo) {
            $permiso = Permiso::firstOrNew([
                'user_id' => $this->usuario->id,
                'modulo_id' => $modulo->id
            ]);

            $this->permisosMatriz[$modulo->id] = [
                'mostrar' => (bool) $permiso->mostrar,
                'crear' => (bool) $permiso->crear,
                'editar' => (bool) $permiso->editar,
                'eliminar' => (bool) $permiso->eliminar,
                'gestionar' => (bool) $permiso->gestionar,
            ];
        }
    }

    public function actualizarPermiso($moduloId, $accion)
    {
        $estadoActual = $this->permisosMatriz[$moduloId][$accion];

        Permiso::updateOrCreate(
            [
                'user_id' => $this->usuario->id,
                'modulo_id' => $moduloId
            ],
            [
                $accion => $estadoActual
            ]
        );

        session()->flash('message', 'Permiso actualizado.');
    }

    public function render()
    {
        return view('livewire.admin.user-permissions', [
            'modulos' => Modulo::all()
        ]);
    }
}