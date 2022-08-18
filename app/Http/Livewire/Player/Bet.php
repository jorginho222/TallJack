<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;

class Bet extends Component
{
    /**
     * Habilitar/deshabilitar sector para hacer apuestas
     */
    public $betting = true;
    public $bet = 0;
    public $oldBet = 0;

    /**
     * Tipo de notificacion, en caso de que haya error al validar la apuesta
     * @var string
     */
    public $betNotification;

    protected $listeners = ['noMoney', 'disableBet', 'clean'];

    
    // ***** Opciones de apuesta *****

    public function addFive()
    {
        $this->bet += 5;
    }

    public function addTen()
    {
        $this->bet += 10;
    }
    
    public function addTwentyFive()
    {
        $this->bet += 25;
    }
    
    public function addFifty()
    {
        $this->bet += 50;
    }

    public function repeatBet()
    {
        $this->bet = $this->oldBet;
    }

    public function resetBet()
    {
        $this->bet = 0;
    }
    
    /**
     * Enviando apuesta para ser descontada al balance del jugador. 
     */
    public function betMade()
    {
        // La apuesta no puede ser nula
        if ($this->bet === 0) {
            return $this->betNotification = 'makeBet';
        }
        
        $this->oldBet = $this->bet;

        $this->emitTo('player.player', 'makeBet', $this->bet);
    }

    /**
     * Activando notificacion de dinero insuficiente
     */
    public function noMoney()
    {
        $this->betNotification = 'noMoney';
    }

    /**
     * Cada vez que actualizo (agrego dinero) la apuesta
     */
    public function dehydrateBet()
    {
        $this->makingBet();
    }

    /**
     * Deshabilitando la notificacion de validacion para poder realizar la apuesta
     */
    public function makingBet()
    {
        $this->betNotification = '';
    }

    public function disableBet() 
    {
        $this->betting = false;
    }

    public function clean()
    {
        $this->reset([
            'betting',
            'bet',
            'betNotification',
        ]);
    }

    public function render()
    {
        return view('livewire.player.bet');
    }
}
