<div class="mr-4">
    {{-- Notificaciones individuales en caso de dividir la mano --}}
    <div class="my-6">
        @switch($result)
            @case('surrendered')
                <div class="bg-blue-200 uppercase">
                    <p class="py-2 text-blue-500 text-center text-md font-bold tracking-wider">Mano retirada!</p> 
                </div>
            @break

            @case('blackJack')
                <div class="bg-yellow-200 uppercase">
                    <p class="py-2 text-orange-500 text-center text-md font-bold tracking-wider">Black Jack!</p> 
                </div>
            @break
    
            @case('bust')
                <div class="bg-red-200 uppercase">
                    <p class="py-2 text-red-500 text-center text-md font-bold tracking-wider">Superaste los 21!</p> 
                </div>
            @break

            @case('won')
                <div class="bg-green-300 uppercase">
                    <p class="py-2 text-green-600 text-center text-md font-bold tracking-wider">Ganaste!</p> 
                </div>
            @break
            @break
    
            @case('lost')
                <div class="bg-red-200 uppercase">
                    <p class="py-2 text-red-500 text-center text-md font-bold tracking-wider">Perdiste!</p> 
                </div>
                @break
    
            @case('tied')
                <div class="bg-yellow-200 uppercase">
                    <p class="py-2 text-orange-500 text-center text-md font-bold tracking-wider">Empate!</p> 
                </div>
                @break
            @break
    
            @default
    
        @endswitch
    </div>

    {{-- Cartas --}}
    <div class="flex">
        @foreach ($cards as $card)
            @include('components.card')
        @endforeach
    </div>

    <div class="flex">
        {{-- Sumas totales --}}
        @include('components.total')
        
        @if($showSplit)
            {{-- Opcion de dividir la mano --}}
            <x-hand-options-button
                wire:click="$emitTo('player.player', 'splitBet', {{ $bet }}, {{ $hand }})"
                class="mt-3 ml-3 bg-blue-300 text-blue-800"
            >
                Dividir
            </x-hand-options-button>
        @endif
    </div>

    <div class="flex">
        {{-- Apuesta de la mano. Opcion de retirada --}}
        <div style="width: 130px; " class="mt-6 px-2 py-1 bg-gray-300 flex justify-center">
            <p class="text-center text-green-700">Apuesta: <span class="font-bold">${{ $bet }}</span></p>
        </div>

        @if($enableHand && $showSurrender)
            {{-- Opcion de rendirse --}}
            <button 
                wire:click="surrender" 
                class="mt-7 ml-4 text-blue-400 font-bold tracking-wider hover:border-b-2 border-blue-400 cursor-pointer"
            >
                Rendirse
            </button>
        @endif
    </div>

    {{-- Opciones de juego --}}
    @if($enableHand)
        <div style="width: 270px" class="mt-6 grid grid-cols-3">
            <div>
                @if($showDouble)
                    <x-hand-options-button
                        wire:click="$emitTo('player.player', 'doubleBet', {{ $bet }}, {{ $hand }})"
                        class="bg-violet-400 text-violet-800"
                    >
                        Doblar
                    </x-hand-options-button>
                @endif
            </div>
            <div>
                <x-hand-options-button
                    wire:click="hit"
                    class="ml-1 bg-green-400 text-green-800"
                >
                    Seguir
                </x-hand-options-button>
            </div>
            <div>
                <x-hand-options-button
                    wire:click="stand"
                    class="bg-orange-400 text-orange-800"
                >
                    Plantarse
                </x-hand-options-button>
            </div>
        </div>
    @endif
</div>
