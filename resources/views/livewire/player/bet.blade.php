<div>
    @if($betting)
        <div style="width: 25%; position: absolute; top: 0; left: 20%;" class="bg-gray-300 py-2">
            
            {{-- Validando la apuesta --}}
            @switch($betNotification)
                @case('makeBet')
                    <p class="px-2 text-center text-red-500 text-md font-bold">Debe realizar una apuesta!</p> 
                    @break

                @case('noMoney')
                    <p class="px-2 text-center text-red-500 text-md font-bold mt-2">No tiene dinero suficiente!</p> 

                    @break
            
                @default
                    <p class="py-4 text-center text-gray-700 text-xl font-bold">${{ $bet }}</p> 
            @endswitch
        </div>

        {{-- Fichas de apuesta --}}
        <div class="mt-28 flex">
            <div wire:click="addFive" class="bg-violet-500 rounded-full px-5 py-3 border-4 border-violet-400 hover:cursor-pointer hover:bg-violet-600">
                <p class="mt-1 text-white">5</p> 
            </div>
            <div wire:click="addTen" class="ml-3 bg-green-500 rounded-full px-4 py-3 border-4 border-green-400 hover:cursor-pointer hover:bg-green-600">
                <p class="mt-1 text-white">10</p>
            </div>
            <div wire:click="addTwentyFive" class="ml-3 bg-red-500 rounded-full px-4 py-3 border-4 border-red-400 hover:cursor-pointer hover:bg-red-600">
                <p class="mt-1 text-white">25</p>
            </div>
            <div wire:click="addFifty" class="ml-3 bg-blue-500 rounded-full px-4 py-3 border-4 border-blue-400 hover:cursor-pointer hover:bg-blue-600">
                <p class="mt-1 text-white">50</p>
            </div>
        </div>

        {{-- Opciones de apuesta --}}
        <div id="betOptions" class="flex mt-6 transform transition-all ease-in duration-150">
            <x-main-options-button wire:click="betMade">Apostar</x-main-options-button>
            <x-main-options-button wire:click="repeatBet">Repetir apuesta</x-main-options-button>
            <x-main-options-button wire:click="resetBet">Reiniciar apuesta</x-main-options-button>
        </div>
    @endif
</div>
