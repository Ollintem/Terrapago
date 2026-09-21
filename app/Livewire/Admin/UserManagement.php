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
            'rol_id'   => 'required|exists:roles,id',
            'password' => $this->user_id
                ? 'nullable|min:6'
                : 'required|min:6',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MENSAJES DE VALIDACIÓN EN ESPAÑOL
    |--------------------------------------------------------------------------
    */

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.string'   => 'El nombre debe contener texto válido.',
            'nombre.max'      => 'El nombre no puede tener más de 100 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'Ingresa un correo electrónico válido.',
            'email.unique'   => 'Este correo electrónico ya está registrado.',

            'rol_id.required' => 'Debes seleccionar un rol.',
            'rol_id.exists'   => 'El rol seleccionado no es válido.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | REINICIAR PAGINACIÓN AL BUSCAR
    |--------------------------------------------------------------------------
    */

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
        /*
        |--------------------------------------------------------------------------
        | USUARIOS DE LA TABLA
        |--------------------------------------------------------------------------
        */

        $usuarios = User::with('rol')
            ->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })

            // El Super Administrador siempre aparece primero
            ->orderByRaw(
                "CASE WHEN email = 'admin@terrapago.com' THEN 0 ELSE 1 END"
            )

            // Los demás usuarios se ordenan por nombre
            ->orderBy('nombre')

            ->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | TARJETAS DE RESUMEN
        |--------------------------------------------------------------------------
        */

        $usuariosActivos = User::where('estado', true)->count();

        $usuariosInactivos = User::where('estado', false)->count();

        $rolesConfigurados = Role::count();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMO USUARIO ACTUALIZADO
        |--------------------------------------------------------------------------
        |
        | Se utiliza updated_at como referencia para mostrar
        | el último usuario registrado o actualizado.
        |
        */

        $ultimoUsuario = User::with('rol')
            ->latest('updated_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $roles = Role::all();


        /*
        |--------------------------------------------------------------------------
        | RETORNAR VISTA
        |--------------------------------------------------------------------------
        */

        return view('livewire.admin.user-management', [

            // Tabla
            'usuarios' => $usuarios,

            // Roles
            'roles' => $roles,

            // Tarjetas superiores
            'usuariosActivos'    => $usuariosActivos,
            'usuariosInactivos'  => $usuariosInactivos,
            'rolesConfigurados'  => $rolesConfigurados,
            'ultimoUsuario'      => $ultimoUsuario,

        ])->layout('layouts.app');
    }


    /*
    |--------------------------------------------------------------------------
    | ABRIR MODAL PARA CREAR
    |--------------------------------------------------------------------------
    */

    public function abrirModal()
    {
        $this->reset([
            'nombre',
            'email',
            'password',
            'rol_id',
            'user_id'
        ]);

        $this->resetValidation();

        $this->isModalOpen = true;
    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR MODAL
    |--------------------------------------------------------------------------
    */

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


        // Proteger Super Administrador principal
        if ($user->email === 'admin@terrapago.com') {

            session()->flash(
                'message',
                'El Super Administrador principal no puede ser editado.'
            );

            return;
        }


        $this->user_id = $user->id;
        $this->nombre = $user->nombre;
        $this->email = $user->email;
        $this->rol_id = $user->rol_id;
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
        /*
        |--------------------------------------------------------------------------
        | PROTEGER SUPER ADMINISTRADOR
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | VALIDAR
        |--------------------------------------------------------------------------
        */

        $this->validate();


        /*
        |--------------------------------------------------------------------------
        | DETERMINAR SI ES NUEVO
        |--------------------------------------------------------------------------
        */

        $esNuevo = empty($this->user_id);


        /*
        |--------------------------------------------------------------------------
        | DATOS DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $datos = [
            'nombre' => trim($this->nombre),
            'email'  => trim($this->email),
            'rol_id' => $this->rol_id,
        ];


        /*
        |--------------------------------------------------------------------------
        | CONTRASEÑA
        |--------------------------------------------------------------------------
        */

        if ($this->password) {

            $datos['password'] = bcrypt($this->password);
        }


        /*
        |--------------------------------------------------------------------------
        | CREAR / ACTUALIZAR
        |--------------------------------------------------------------------------
        */

        $usuario = User::updateOrCreate(
            ['id' => $this->user_id],
            $datos
        );


        /*
        |--------------------------------------------------------------------------
        | CREAR PERMISOS PARA USUARIO NUEVO
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | CERRAR MODAL
        |--------------------------------------------------------------------------
        */

        $this->isModalOpen = false;


        /*
        |--------------------------------------------------------------------------
        | MENSAJE
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'mensaje',
            $esNuevo
                ? 'Usuario registrado correctamente.'
                : 'Usuario actualizado correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | IR A PERMISOS
    |--------------------------------------------------------------------------
    */

    public function irAPermisos($id)
    {
        return redirect()->route(
            'admin.usuarios.permisos',
            $id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR USUARIO
    |--------------------------------------------------------------------------
    */

    public function eliminar($id)
    {
        /*
        |--------------------------------------------------------------------------
        | NO PERMITIR ELIMINARSE A SÍ MISMO
        |--------------------------------------------------------------------------
        */

        if (auth()->id() == $id) {

            session()->flash(
                'message',
                'No puedes eliminar tu propia cuenta en sesión.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | BUSCAR USUARIO
        |--------------------------------------------------------------------------
        */

        $usuario = User::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | PROTEGER SUPER ADMINISTRADOR
        |--------------------------------------------------------------------------
        */

        if ($usuario->email === 'admin@terrapago.com') {

            session()->flash(
                'message',
                'El Super Administrador principal no puede ser eliminado.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR
        |--------------------------------------------------------------------------
        */

        $usuario->delete();


        /*
        |--------------------------------------------------------------------------
        | MENSAJE
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'mensaje',
            'Usuario eliminado correctamente.'
        );
    }
}