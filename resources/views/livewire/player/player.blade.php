<div>
    @if($showEdit)
        @livewire('player.edit', ['name' => $name, 'currentAvatarPath' => $currentAvatarPath])
        @else
            <div 
                class="flex align-items-center hover:cursor-pointer"
                wire:mouseover="showEditIcon"
                wire:mouseout="hideEditIcon"
                wire:click="showEdit"
            >
                <img style="width: 30px; height: 30px" src="{{asset($currentAvatarPath)}}" class="mr-2 rounded-md" alt="Player logo">
                <p class="font-bold pt-1 text-gray-700">{{ $name }}</p> 
                
                @if($showEditIcon)
                    {{-- Icono de edicion --}}
                    <div class="mt-1 ml-4 hover:cursor-pointer">
                        <img src="{{ asset('img/edit.png') }}" style="width: 20px; height: 20px;" alt="Editar perfil">
                    </div>
                @endif
            </div>
            
            <div style="width: 140px" class="mt-2 bg-gray-300 px-2 py-1">
                <p class="text-violet-700 text-center">Cuenta: <span class="text-violet-700 font-bold">${{ $balance }}</span></p> 
            </div>
    @endif
</div>


