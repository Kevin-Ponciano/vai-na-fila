<?php

namespace App\Livewire\Supermarket;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Users extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';
    public $supermarket_id = null;
    public $successMessage = '';
    public $errorMessage = '';
    public $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'role' => 'required|in:admin,operator',
        'supermarket_id' => 'nullable|exists:supermarkets,id',
    ];

    public function getRolesProperty()
    {
        return UserRole::options();
    }

    public function getUsersProperty()
    {
        if (!Auth::user()) {
            return collect([]);
        }


        return User::where('supermarket_id', Auth::user()->supermarket_id)
            ->select('id', 'name', 'email', 'role')
            ->get();
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        $this->resetForm();
    }

    public function saveUser()
    {

        $this->validate();

        if (!Auth::user()) {
            $this->errorMessage = 'Usuário não autenticado.';
            return;
        }

        try {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'supermarket_id' => Auth::user()->supermarket_id,
            ]);

            $this->successMessage = 'Usuário criado com sucesso!';
            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao criar usuário: ' . $e->getMessage();
        }
    }

    public function deleteUser($userId)
    {
        if (!Auth::user()) {
            $this->errorMessage = 'Usuário não autenticado.';
            return;
        }

        \Log::info('isAdmin: ' . (Auth::user()->isAdmin() ? 'true' : 'false') . ', Role: ' . Auth::user()->role);

        if (!Auth::user()->isAdmin()) {
            $this->errorMessage = 'Apenas administradores podem excluir usuários.';
            return;
        }

        if ($userId === Auth::user()->id) {
            $this->errorMessage = 'Você não pode excluir a si mesmo.';
            return;
        }

        try {
            $user = User::findOrFail($userId);
            $user->delete();
            $this->successMessage = 'Usuário excluído com sucesso!';
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao excluir usuário: ' . $e->getMessage();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->supermarket_id = null;
        $this->errorMessage = '';
        $this->successMessage = '';
    }

    public function render()
    {
        return view('livewire.users', [
            'roles' => $this->roles,
            'users' => $this->users,
        ]);
    }
}
