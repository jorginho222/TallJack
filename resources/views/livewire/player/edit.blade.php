<div class="bg-violet-600 pl-3 pr-2 pt-2 pb-3 rounded-md">
    <div class="flex">
        <div>
            <input type="text" wire:model="name" value="{{ $name }}" class="mt-1 px-2 py-1 border-none rounded-md">
            
            {{-- Avatares disponibles --}}
            <div class="mt-2 flex">
                @foreach($availableAvatarsPath as $key => $logo)
                    <img id="{{ $key }}" 
                        style="width: 50px; height: 50px" 
                        src="{{asset($logo)}}" 
                        wire:click="select('{{ $logo }}')" 
                        class="mr-1 
                            @if($logo === $selectedAvatarPath)
                                border-b-4 border-red-600
                                @else
                                hover:border-b-4 border-green-600 
                            @endif 
                            cursor-pointer" 
                        alt="logo">
                @endforeach
            </div>
        </div>

        <button 
            wire:click="$emitUp('editProfile', '{{ $name }}', '{{ $selectedAvatarPath }}')" 
            class="ml-4 my-7 px-3 py-2 bg-green-600 rounded-md border-none text-white"
        >
            Guardar cambios
        </button>
        
        <div wire:click="$emitUp('closeEdit')" class="ml-5 mr-2 hover:cursor-pointer">
            <p class="py-1 text-white font-bold">X</p>
        </div>
    </div>
</div>
