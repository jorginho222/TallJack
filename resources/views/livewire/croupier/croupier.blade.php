<div>
    @if(!$playing)
        @include('components.logo-card')
        @else
        {{-- Notificaciones individuales --}}
        <div class="mb-2">
            @switch($result)
                @case('blackJack')
                    <div class="bg-yellow-200 uppercase">
                        <p class="py-1 text-orange-500 text-center text-md font-bold tracking-wider">Black Jack!</p> 
                    </div>
                @break
                @case('bust')
                    <div class="bg-gray-300 uppercase">
                        <p class="py-1 text-gray-500 text-center text-md font-bold tracking-wider">Superó los 21!</p> 
                    </div>
                @break
            
                @default
                    
            @endswitch
        </div>

        {{-- Cartas del croupier --}}
        <div class="flex">
            @foreach ($cards as $card)
                @include('components.card')
            @endforeach
        </div>

        <div class="flex">
            {{-- Sumas Totales --}}
            @include('components.total')
            @if($playerInsuranceBet)
                {{-- Apuesta de seguro del jugador --}}
                <div style="width: 130px; " class="mt-3 ml-2 px-2 py-1 bg-gray-300 flex justify-center">
                    <p class="pt-1 text-center text-green-700">Seguro: <span class="font-bold">${{ $playerInsuranceBet }}</span></p>
                </div>
            @endif
        </div>
    @endif
</div>
