<?php

namespace App\Http\Livewire\Player;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Player extends Component
{
    public $name;
    public $currentAvatarPath;
    public $balance;
    public $showEditIcon = false;
    public $showEdit = false;

    protected $listeners = ['editProfile', 'closeEdit', 'makeBet', 'makeInsuranceBet', 'payHalf', 'blackJack', 'won', 'tied', 'doubleBet', 'splitBet'];

    public function mount()
    {
        // Si hay jugador registrado en la sesion, obtenemos su data, sino guardamos info de nuevo jugador en la sesion
        if(session()->has('name')) {
            $this->name = session()->get('name');
            $this->balance = session()->get('balance');
            $this->currentAvatarPath = session()->get('avatar');
        } else {
            $this->name = 'Nuevo jugador';
            $this->balance = 2500;
            $this->currentAvatarPath = 'img/card-logos/' . rand(1, 4) . '.png';
            session()->put('name', $this->name);
            session()->put('balance', $this->balance);
            session()->put('avatar', $this->currentAvatarPath);
        }
    }

    // ***** Opciones de mostrar/ocultar iconos/ventana de editar perfil
  
    public function showEditIcon()
    {
        $this->showEditIcon = true;
    }

    public function hideEditIcon()
    {
        $this->showEditIcon = false;
    }

    public function showEdit()
    {
        $this->showEdit = true;
        $this->showEditIcon = false;
    }

    public function closeEdit()
    {
        $this->showEdit = false;
    }
    
    /**
     * Editando perfil de jugador y guardando en la sesion
     * @param string $name
     * @param string $selectedAvatarPath
     */
    public function editProfile($name, $selectedAvatarPath)
    {
        $this->name = $name;
        $this->currentAvatarPath = $selectedAvatarPath;
        session()->put('name', $this->name);
        session()->put('avatar', $this->currentAvatarPath);
        $this->showEdit = false;
    }

    /**
     * Deshabilitamos las opciones de apuesta e inicamos la mano
     * @param float $bet
     */
    public function makeBet($bet)
    {
        if (!$this->checkIfEnoughMoney($bet)) {
            return $this->emitTo('player.bet', 'noMoney');
        }
        $this->emit('disableBetOptions'); // Al javascript

        $this->emitTo('player.bet', 'disableBet');

        $this->emitTo('table.table', 'initHand', $bet);
    }

    /**
     * Realizando la apuesta de seguro     
     * @param float $bet
     */
    public function makeInsuranceBet($bet)
    {
        if (!$this->checkIfEnoughMoney($bet)) {
            $this->emitTo('table.player-notifications', 'noMoney');
        } else {
            $this->emitTo('player.hand', 'insuranceBetMade');
        }
        $this->emitTo('player.hand', 'start');
    }

    /**
     * Permitiendo a la mano duplicar la apuesta y hacer la jugada correspondiente
     * @param float $bet
     * @param int $hand
     */
    public function doubleBet($bet, $hand)
    {
        if (!$this->checkIfEnoughMoney($bet)) {
            $this->emitTo('table.player-notifications', 'noMoney');
        } else {
            $this->emitTo('player.hand', 'double', $hand);
        }
    }

    /**
     * Permitiendo a la mano duplicar la apuesta y hacer la jugada correspondiente
     * @param float $bet
     * @param int $hand
     */
    public function splitBet($bet, $hand)
    {
        if (!$this->checkIfEnoughMoney($bet)) {
            $this->emitTo('table.player-notifications', 'noMoney');
        } else {
            $this->emitTo('player.hand', 'split', $hand);
        }
    }

    /**
     * Chequeando si hay suficiente dinero, y descontando del balance
     * @param float $bet
     * @return boolean
     */
    public function checkIfEnoughMoney($bet)
    {
        if ($this->balance >= $bet) {
            $this->discountBet($bet);
            return true;
        }
    }

    /**
     * Descontando del balance la apuesta
     * @param float $bet
     */
    public function discountBet($bet)
    {
        $this->balance -= $bet;
    }

    // ***** Pagos recompensa, de acuerdo al resultado de la/s manos

    /**
     * @param float $bet
     */
    public function blackJack($bet)
    {
        $this->balance += $bet * 2.5;
    }

    /**
     * @param float $bet
     */
    public function won($bet)
    {
        $this->balance += $bet * 2; 
    }

    /**
     * @param float $bet
     */
    public function tied($bet)
    {
        $this->balance += $bet;
    }

    /**
     * @param float $bet
     */
    public function payHalf($bet)
    {
        $this->balance += $bet / 2;
    }

    /**
     * Actualizando el balance en la sesion cada vez que haya cambios en él
     */
    public function dehydrateBalance()
    {
        session()->put('balance', $this->balance);
    }

    public function render()
    {
        return view('livewire.player.player');
    }
}
