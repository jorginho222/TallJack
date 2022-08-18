<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;

class Table extends Component
{
    public $hands = [];
    public $handData = [];
    public $showWelcomeModal = false;
    public $showInsuranceModal = false;
    public $showRestartButton = false;

    protected $listeners = ['welcome', 'initHand', 'enableInsuranceOffer', 'split', 'handFinished', 'gameFinished', 'cleanAll'];

    public function mount()
    {
        // Si no hay nombre cargado en la sesión, damos la bienvenida con el modal 
        if(!session()->has('name')) {
            $this->showWelcomeModal = true;
        }
    }

    public function disableWelcome()
    {
        $this->showWelcomeModal = false;
    }

    /**
     * Inicializando componente de la mano (Hand). Dando la orden al croupier para que inicie su juego
     * @param float $bet
     */
    public function initHand($bet)
    {
        $this->handData = [
            'bet' => $bet,
        ];
        $this->newHand();
        $this->emitTo('croupier.croupier', 'start');
    }

    public function enableInsuranceOffer()
    {
        $this->showInsuranceModal = true;
    }

    // **** Aceptar/rechazar apuesta de seguro ***** 
    
    public function makeInsuranceBet()
    {
        $this->disableInsuranceOffer();
        $this->emitTo('player.hand', 'makeInsuranceBet');
    }

    public function refuseInsuranceBet()
    {
        $this->disableInsuranceOffer();
        $this->emitTo('player.hand', 'start');
    }

    public function disableInsuranceOffer()
    {
        $this->showInsuranceModal = false;
    }

    /**
     * Pasando la carta al nuevo componente Hand
     * @param array $handData
     */
    public function split($handData)
    {
        $this->handData = [];
        $this->handData = $handData;

        $this->newHand();
    }

    /**
     * Creando nueva mano. Si el jugador decidio dividir mano, se pasa la carta de split 
     */
    public function newHand()
    {
        $this->hands[] = $this->handData;
    }

    /**
     * Funcion para mano dividida. Se habilita la siguiente mano (si la hay).   
     * Si no hay mas manos por jugar, se emite que el jugador finalizo.
     * @param int $hand
     */
    public function handFinished($hand)
    {
        $handKeys = array_keys($this->hands);

        // Analizo si la mano es la ultima en el array de hands.
        if($hand === end($handKeys)) {
            $this->playerFinished();
        } else {
            $nextHand = $hand + 1;
            $this->emitTo('player.hand', 'enableNextHand', $nextHand);
        }
    }

    /**
     * Cuando el jugador finaliza todas las manos, se emite al croupier para que juegue y se hagan las comparaciones
     */
    public function playerFinished()
    {
        $this->emitTo('croupier.croupier', 'splitStand');
    }

    public function gameFinished()
    {
        $this->showRestartButton = true;
    }

    public function cleanAll()
    {
        $this->emit('hideNotification'); // Al javascript
        $this->reset([
            'hands',
            'handData',
            'showRestartButton',
        ]);
        $this->emit('clean');
    }

    public function render()
    {
        return view('livewire.table.table');
    }
}
