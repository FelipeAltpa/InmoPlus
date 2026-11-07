<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $user->exists ? 'Editar usuario' : 'Crear usuario' }}</h2></x-slot>
    <div class="py-10"><div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8"><form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" class="bg-white shadow rounded-xl p-6 space-y-5">
        @csrf @if($user->exists) @method('PUT') @endif
        <div><label class="block text-sm font-medium text-gray-700">Nombre</label><input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300"></div>
        <div><label class="block text-sm font-medium text-gray-700">Correo</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300"></div>
        <div><label class="block text-sm font-medium text-gray-700">Teléfono</label><input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-md border-gray-300"></div>
        <div><label class="block text-sm font-medium text-gray-700">Rol</label><select name="role" required class="mt-1 block w-full rounded-md border-gray-300">@foreach(['client'=>'Cliente','agent'=>'Agente','admin'=>'Administrador'] as $role=>$label)<option value="{{ $role }}" @selected(old('role',$user->role)===$role)>{{ $label }}</option>@endforeach</select></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$user->exists ? $user->is_active : true)) class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Cuenta activa</span></label>
        @if($errors->any())<div class="text-sm text-red-700">{{ $errors->first() }}</div>@endif
        <div class="flex gap-3"><button class="px-4 py-2 rounded-md bg-indigo-600 text-white">Guardar</button><a href="{{ route('users.index') }}" class="px-4 py-2 rounded-md border">Cancelar</a></div>
    </form></div></div>
</x-app-layout>
