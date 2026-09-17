<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Modulo;
use App\Models\Permiso;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $nombre, $email, $password, $rol_id, $user_id;
    public $isModalOpen = false;

    // Resetear la paginación al escribir en el buscador
    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $this->user_id,
            'rol_id'   => 'required|exists:roles,id',
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function render()
    {
        $usuarios = User::with('rol')
            ->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })

            // El Super Administrador siempre aparece primero
            ->orderByRaw("CASE WHEN email = 'admin@terrapago.com' THEN 0 ELSE 1 END")

            // Después, los demás usuarios se ordenan por nombre
            ->orderBy('nombre')

            ->paginate(10);

        return view('livewire.admin.user-management', [
            'usuarios' => $usuarios,
            'roles'    => Role::all(),
        ])->layout('layouts.app');
    }

    public function abrirModal()
    {
        $this->reset(['nombre', 'email', 'password', 'rol_id', 'user_id']);
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function cerrarModal()
    {
        $this->resetValidation();
        $this->isModalOpen = false;
    }

    public function editar($id)
    {
        $this->resetValidation();

        $user = User::findOrFail($id);

        // Protección del Super Administrador principal
        if ($user->email === 'admin@terrapago.com') {
            session()->flash(
                'message',
                'El Super Administrador principal no puede ser editado.'
            );

            return;
        }

        $this->user_id = $user->id;
        $this->nombre  = $user->nombre;
        $this->email   = $user->email;
        $this->rol_id  = $user->rol_id;
        $this->password = '';

        $this->isModalOpen = true;
    }

    public function guardar()
    {
        // Si estamos editando un usuario existente,
        // verificar primero que no sea el Super Administrador.
        if (!empty($this->user_id)) {
            $usuarioExistente = User::findOrFail($this->user_id);

            if ($usuarioExistente->email === 'admin@terrapago.com') {
                session()->flash(
                    'message',
                    'El Super Administrador principal no puede ser editado.'
                );

                return;
            }
        }

        $this->validate();

        $esNuevo = empty($this->user_id);

        $datos = [
            'nombre' => $this->nombre,
            'email'  => $this->email,
            'rol_id' => $this->rol_id,
        ];

        // Solo actualiza la contraseña si se ingresó una nueva
        if ($this->password) {
            $datos['password'] = bcrypt($this->password);
        }

        $usuario = User::updateOrCreate(
            ['id' => $this->user_id],
            $datos
        );

        // Si es un usuario nuevo, inicializarle sus registros en la tabla de permisos
        if ($esNuevo) {
            $modulos = Modulo::all();

            foreach ($modulos as $modulo) {
                Permiso::firstOrCreate(
                    [
                        'user_id'   => $usuario->id,
                        'modulo_id' => $modulo->id,
                    ],
                    [
                        'mostrar'  => false,
                        'crear'    => false,
                        'editar'   => false,
                        'eliminar' => false,
                    ]
                );
            }
        }

        $this->isModalOpen = false;

        session()->flash(
            'mensaje',
            'Usuario guardado correctamente.'
        );
    }

    public function irAPermisos($id)
    {
        return redirect()->route('admin.usuarios.permisos', $id);
    }

    public function eliminar($id)
    {
        // 1. Evitar que un usuario se elimine a sí mismo
        if (auth()->id() == $id) {
            session()->flash(
                'message',
                'No puedes eliminar tu propia cuenta en sesión.'
            );

            return;
        }

        $usuario = User::findOrFail($id);

        // 2. Proteger al Super Administrador principal
        if ($usuario->email === 'admin@terrapago.com') {
            session()->flash(
                'message',
                'El Super Administrador principal no puede ser eliminado.'
            );

            return;
        }

        // 3. Eliminar usuario
        // Sus permisos se eliminan en cascada por la foreign key
        $usuario->delete();

        session()->flash(
            'mensaje',
            'Usuario eliminado correctamente.'
        );
    }
}