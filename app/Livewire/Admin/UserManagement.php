<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Rol; // o Role si tu modelo se llama Role
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

    /*
    |--------------------------------------------------------------------------
    | VALIDACIONES
    |--------------------------------------------------------------------------
    */
    protected function rules()
    {
        return [
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $this->user_id,
            'rol_id'   => 'required',
            'password' => $this->user_id
                ? 'nullable|min:6'
                : 'required|min:6',
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required'   => 'El nombre completo es obligatorio.',
            'nombre.string'     => 'El nombre debe contener texto válido.',
            'nombre.max'        => 'El nombre no puede tener más de 100 caracteres.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Ingresa un correo electrónico válido.',
            'email.unique'      => 'Este correo electrónico ya está registrado.',
            'rol_id.required'   => 'Debes seleccionar un rol.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | VISTA PRINCIPAL
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        // 1. Usuarios para la tabla
        $usuarios = User::with('rol')
            ->where(function ($query) {
                // Compatible si tu columna es 'name' o 'nombre'
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            // El Super Administrador siempre aparece primero
            ->orderByRaw("CASE WHEN email = 'admin@terrapago.com' THEN 0 ELSE 1 END")
            ->orderBy('nombre') // o 'name' según tu columna
            ->paginate(10);

        // 2. Tarjetas de resumen (usando la columna 'activo')
        $usuariosActivos   = User::where('activo', true)->count();
        $usuariosInactivos = User::where('activo', false)->count();

        // Obtener conteo de roles
        $rolesModel = class_exists('\App\Models\Rol') ? \App\Models\Rol::class : \App\Models\Role::class;
        $rolesConfigurados = $rolesModel::count();
        $roles             = $rolesModel::all();

        // 3. Último acceso registrado
        $ultimoUsuario = User::with('rol')
            ->whereNotNull('ultimo_acceso')
            ->latest('ultimo_acceso')
            ->first();

        // Respaldo si nadie ha iniciado sesión aún con el nuevo campo
        if (!$ultimoUsuario) {
            $ultimoUsuario = User::with('rol')->latest('updated_at')->first();
        }

        return view('livewire.admin.usuarios.index', [
            'usuarios'          => $usuarios,
            'roles'             => $roles,
            'usuariosActivos'   => $usuariosActivos,
            'usuariosInactivos' => $usuariosInactivos,
            'rolesConfigurados' => $rolesConfigurados,
            'ultimoUsuario'     => $ultimoUsuario,
        ])->layout('layouts.app');
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROL DE MODAL
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | EDITAR USUARIO
    |--------------------------------------------------------------------------
    */
    public function editar($id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);

        if ($user->email === 'admin@terrapago.com') {
            session()->flash('error', 'El Super Administrador principal no puede ser editado.');
            return;
        }

        $this->user_id  = $user->id;
        $this->nombre   = $user->name ?? $user->nombre;
        $this->email    = $user->email;
        $this->rol_id   = $user->rol_id;
        $this->password = '';

        $this->isModalOpen = true;
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR / ACTUALIZAR USUARIO
    |--------------------------------------------------------------------------
    */
    public function guardar()
    {
        if (!empty($this->user_id)) {
            $usuarioExistente = User::findOrFail($this->user_id);
            if ($usuarioExistente->email === 'admin@terrapago.com') {
                session()->flash('error', 'El Super Administrador principal no puede ser modificado.');
                return;
            }
        }

        $this->validate();

        $esNuevo = empty($this->user_id);

        $datos = [
            'nombre'   => trim($this->nombre),
            'email'  => trim($this->email),
            'rol_id' => $this->rol_id,
        ];

        if ($this->password) {
            $datos['password'] = bcrypt($this->password);
        }

        if ($esNuevo) {
            $datos['activo'] = true;
        }

        $usuario = User::updateOrCreate(
            ['id' => $this->user_id],
            $datos
        );

        // Crear matriz de permisos iniciales si es nuevo
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
            $esNuevo ? 'Usuario registrado correctamente.' : 'Usuario actualizado correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVAR / DESACTIVAR USUARIO (PRESERVANDO HISTÓRICO Y AUDITORÍA)
    |--------------------------------------------------------------------------
    */
    public function toggleEstado($userId)
    {
        $usuario = User::findOrFail($userId);

        if ($usuario->id === auth()->id()) {
            session()->flash('error', 'No puedes desactivar tu propia cuenta en sesión.');
            return;
        }

        if ($usuario->email === 'admin@terrapago.com') {
            session()->flash('error', 'El Super Administrador no puede ser desactivado.');
            return;
        }

        $usuario->activo = !$usuario->activo;
        $usuario->save();

        $nombreUsuario = $usuario->nombre;
        $estadoTexto   = $usuario->activo ? 'activado' : 'desactivado';
        session()->flash('mensaje', "El usuario {$nombreUsuario} fue {$estadoTexto} exitosamente.");
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECCIÓN A GESTIÓN DE PERMISOS
    |--------------------------------------------------------------------------
    */
    public function irAPermisos($id)
    {
        return redirect()->route('admin.usuarios.permisos', $id);
    }
}