<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Gerenciar Usuários</h1>

    <!-- Mensagens de Sucesso/Erro -->
    @if($successMessage)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ $successMessage }}
        </div>
    @endif
    @if($errorMessage)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ $errorMessage }}
        </div>
    @endif

    <!-- Botão para abrir/fechar formulário -->
    <div class="flex justify-center mb-6">
        <button wire:click="toggleForm" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ $showForm ? 'Fechar Formulário' : 'Criar Novo Usuário' }}
        </button>
    </div>

    <!-- Formulário de criação (exibido condicionalmente) -->
    @if($showForm)
        <form wire:submit.prevent="saveUser" class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" wire:model="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Permissão</label>
                    <select wire:model="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">Selecione uma permissão</option>
                        <option value="admin">Admin</option>
                        <option value="operator">Operator</option>
                    </select>
                    @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Botão de Enviar -->
            <div class="mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Criar Usuário
                </button>
            </div>
        </form>
    @endif

    <!-- Lista de Usuários -->
    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissão</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(strtolower($user->role)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if(!Auth::user())
                                <span class="text-gray-500 text-xs">Usuário não autenticado</span>
                            @elseif(Auth::user()->id === $user->id)
                                <span class="text-gray-500 text-xs">Não pode excluir a si mesmo</span>
                            @elseif(!Auth::user()->isAdmin())
                                <span class="text-gray-500 text-xs">Apenas administradores podem excluir</span>
                            @else
                                <button wire:click="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V3a1 1 0 011-1zm-3 5h8"></path>
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
