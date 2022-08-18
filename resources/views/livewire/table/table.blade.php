<div>
    <div style="height:800px; width:1000px" class="bg-gray-200 opacity-90">
        {{-- Sector del croupier --}}
        <p class="pt-5 font-bold text-xl text-center text-violet-700">Croupier</p>
        <div class="flex justify-center">
            <div class="mt-4">
                @livewire('croupier.croupier')
            </div> 
        </div>
        
        {{-- Area de notificaciones --}}
        <div style="position:absolute; left:35%; top:38%;" class="flex">
            @livewire('table.player-notifications') 
        </div>

        {{-- Componente para las apuestas --}}
        <div id="bet" style="position:absolute; left:10%; top:57%;" class="">
            @livewire('player.bet') 
        </div>
    
        {{-- Mano/s del jugador --}}
        <div style="position:absolute; left:10%; top:47%;" class="flex">
            @foreach($hands as $hand => $handData)
                @livewire('player.hand', ['hand' => $hand, 'handData' => $handData], key($hand)) 
            @endforeach
        </div>
    
        {{-- Boton para reiniciar partida --}}
        <div style="position:absolute; left:45%; top:92%;" class="">
            @if($showRestartButton)
                <x-main-options-button wire:click="cleanAll" class="">Jugar otra vez</x-main-options-button>
            @endif
        </div>
        
        {{-- Info del jugador --}}
        <div style="position:absolute; left:10%; top:90%;" class="">
            @livewire('player.player')
        </div>
        
        {{-- Enlace a las reglas del juego --}}
        <div style="position:absolute; left:78%; top:95%;" class="">
            <a  href="https://www.pokerstars.com/es-419/casino/how-to-play/blackjack/rules/" 
                target="_blank" rel="noopener noreferrer" 
                class="bg-green-700 py-2 px-3 text-white text-sm font-bold uppercase rounded-md hover:bg-green-600"
            >
                Reglas del Black Jack
            </a>
        </div>
    </div>
    
    {{-- Modal de bienvenida --}}
    @if($showWelcomeModal)
        <div id="welcomeModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                    <div class="inline-block align-bottom bg-violet-700 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-violet-700 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <div class="flex align-items-center">
                                        <img src="{{ asset('img/logo.png') }}" style="width: 50px; height: 50px;" alt="logo app">
                                        <h3 class="ml-3 mt-3 text-lg leading-6 font-bold text-pink-400" id="modal-title">
                                            Bienvenido!
                                        </h3>
                                    </div>
                                <div class="mt-2">
                                    <p class="text-md text-white">
                                        Disfrute del juego. Si no sabe jugar, simplemente aprete todos los botones y vaya viendo :P. Si lo desea puede cambiar el nombre e icono del jugador. Muchas gracias!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-violet-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="disableWelcome" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-sm font-bold uppercase text-white hover:bg-red-800 sm:ml-3 sm:w-auto sm:text-md">
                            Comenzar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de pago de seguro --}}
    @if($showInsuranceModal)
        <div id="insuranceModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                    <div class="inline-block align-bottom bg-violet-700 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-violet-700 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <div class="flex align-items-center">
                                        <img src="{{ asset('img/As.png') }}" style="width: 40px; height: 50px;" alt="logo app">
                                        <h3 class="ml-3 mt-3 text-lg leading-6 font-bold text-pink-400" id="modal-title">
                                            La primer carta del croupier es un As!
                                        </h3>
                                    </div>
                                <div class="mt-2">
                                    <p class="text-md text-white">
                                        Es posible que el croupier logre sacar Black Jack. Tienes la opcion de realizar una apuesta de seguro (50% de tu apuesta original). 
                                        Si el crupier obtiene Blackjack, paga al jugador dos veces la apuesta del seguro ¿Desea realizar la misma?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-violet-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="refuseInsuranceBet" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-1 bg-green-600 font-medium text-white uppercase hover:bg-green-700 sm:ml-3 sm:w-auto ">
                            No
                        </button>
                        <button type="button" wire:click="makeInsuranceBet" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-1 bg-green-600 font-medium text-white uppercase hover:bg-green-700 sm:ml-3 sm:w-auto ">
                            Si
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
    
