<div id="notification" style="width: 300px;" class="scale-0 transform transition-all ease-in duration-150">
    @switch($status)
        @case('noMoney')
            <div class="bg-red-200 uppercase">
                <p class="py-2 text-red-500 text-center text-md font-bold tracking-wider">No tiene suficiente dinero para realizar la apuesta!</p> 
            </div>
        @break

        @case('blackJack')
            <div class="bg-yellow-200 uppercase">
                <p class="py-5 text-orange-500 text-center text-lg font-bold tracking-wider">Black Jack!</p> 
            </div>
        @break

        @case('bust')
            <div class="bg-red-200 uppercase">
                <p class="py-5 text-red-500 text-center text-lg font-bold tracking-wider">Superaste los 21!</p> 
            </div>
            @break
    
        @case('surrendered')
            <div class="bg-blue-200 uppercase">
                <p class="py-5 text-blue-500 text-center text-lg font-bold tracking-wider">Te has retirado!</p> 
            </div>
            @break

        
        @case('won')
            <div class="bg-green-300 uppercase">
                <p class="py-5 text-green-600 text-center text-lg font-bold tracking-wider">Ganaste!</p> 
            </div>
            @break
        
        @case('lost')
            <div class="bg-red-200 uppercase">
                <p class="py-5 text-red-500 text-center text-lg font-bold tracking-wider">Perdiste!</p> 
            </div>
            @break
        
        @case('tied')
            <div class="bg-yellow-200 uppercase">
                <p class="py-5 text-orange-500 text-center text-lg font-bold tracking-wider">Empate!</p> 
            </div>
            @break
    
        @default
    @endswitch
</div>
