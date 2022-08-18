<?php

namespace App\Http\Livewire\Croupier;

use Livewire\Component;

class Croupier extends Component
{
    public $playing = false;
    public $figures = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'J', 'Q', 'K'];
    public $cards = [];
    public $showPrimaryTotal = true;
    public $showSecondaryTotal = false;
    public $primaryTotal;
    public $secondaryTotal;
    public $croupierSum;
    public $playerInsuranceBet;
    public $blackJack = false;
    public $croupierBust = false;
    public $result;
    public $handsData = [];
    
    protected $listeners = ['start', 'insuranceBetMade', 'croupierPlay', 'passHandSum', 'stand', 'splitStand', 'clean'];
    
    /**
     * El croupier inicia su juego generando la primer carta. Luego, da la orden al jugador para que empiece
     */
    public function start()
    {
        $this->playing = true;
        $this->generate();
        if(!$this->checkIfAs()) {
            $this->emitTo('player.hand', 'start');
        }
    }

    /**
     * Pasando al croupier la apuesta de seguro realizada por el jugador
     * @param float $insuranceBet
     */
    public function insuranceBetMade($insuranceBet)
    {
        $this->playerInsuranceBet = $insuranceBet;
    }

    /**
     * En caso de que haya multiples manos, se van recibiendo las sumas de cada mano (las que no superaron los 21)
     * @param array $handData
     */
    public function passHandSum($handData)
    {
        $this->handsData[] = $handData;
    }

    /**
     * Cuando el jugador se planta, el croupier continua su juego, y luego se compara la suma total de ambos
     * @param int $handSum
     */
    public function stand($handSum)
    {
        $this->croupierPlay();

        if ($this->croupierBust) {
            $this->emitTo('table.player-notifications', 'won');
            $this->emitTo('player.hand', 'won', null);
        } else {
            if ($handSum > $this->croupierSum) {
                $this->emitTo('table.player-notifications', 'won');
                $this->emitTo('player.hand', 'won', null);
            }
            if ($handSum < $this->croupierSum) {
                $this->emitTo('table.player-notifications', 'lost');
            }
            if ($handSum === $this->croupierSum) {
                $this->emitTo('table.player-notifications', 'tied');
                $this->emitTo('player.hand', 'tied', null);
            }
        }
        
        $this->gameFinished();
    }

    /**
     * Igual que el met stand() pero con multiples manos
     */
    public function splitStand()
    {
        $this->croupierPlay();

        foreach ($this->handsData as $handData) {

            if ($this->croupierBust) {
                $this->emitTo('player.hand', 'won', $handData['hand']);
            } else {
                if ($handData['handSum'] > $this->croupierSum) {
                    $this->emitTo('player.hand', 'won', $handData['hand']);
                }
                if ($handData['handSum'] < $this->croupierSum) {
                    $this->emitTo('player.hand', 'lost', $handData['hand']);
                }
                if ($handData['handSum'] === $this->croupierSum) {
                    $this->emitTo('player.hand', 'tied', $handData['hand']);
                }
            }
        }

        $this->gameFinished();
    }

    /**
     * El croupier genera cartas hasta que la suma de mayor o igual a 17
     */
    public function croupierPlay()
    {
        // Pendiente solucionar problema con do while
        while ($this->primaryTotal < 17) {
            $this->generate();
            $this->checkIfBlackJack();
        } 

        if($this->checkIfPrimaryBust()) {
            if ($this->showSecondaryTotal) {
                $this->disablePrimaryTotal();
                while ($this->secondaryTotal < 17) {
                    $this->generate();
                } 
                $this->checkIfSecondaryBust();
            } else {
                $this->croupierBust();
            }
        }

        if ($this->showPrimaryTotal) {
            $this->croupierSum = $this->primaryTotal;
        } else {
            $this->croupierSum = $this->secondaryTotal;
        }
    }

    /**
     * Generando nuevas cartas para ser mostradas en la mesa
     */
    public function generate()
    {
        $rand = rand(1, 12);
        
        $value = $this->partialSum($rand);
        
        $cardFigure = $this->figures[$rand - 1];

        $logoNumber = rand(1, 4);
        $logoPath = 'img/card-logos/' . $logoNumber . '.png';

        $this->cards[] = [
            'value' => $value,
            'figure' => $cardFigure,
            'logo' => $logoPath,
        ];
    }

    /**
     * Obteniendo los valores numericos de las cartas y haciendo sumas parciales 
     * @param int $rand
     * @return int 
     */
    public function partialSum($rand)
    {
        if ($rand > 9) {
            $value = 10;
            $this->primaryTotal += 10;
            $this->secondaryTotal += 10;
        } else {
            $this->secondaryTotal += $rand;
            $value = $rand;

            if ($rand === 1) {
            $this->primaryTotal += 11;
            $this->enableSecondaryTotal();
            } else {
                $this->primaryTotal += $rand;
            }
        }
        
        return $value;
    }
    
    // **** Chequeo de cartas y habilitacion de sumas primarias y secundarias (dependiendo si A vale 1 u 11) 

    /**
     * Chequeando si la 1er carta del croupier es un As
     * @return boolean
     */
    public function checkIfAs()
    {
        if ($this->cards[0]['figure'] === 'A') {
            $this->emitTo('table.table', 'enableInsuranceOffer');
            return true; 
        }
    }

    public function checkIfBlackJack()
    {
        if ($this->primaryTotal === 21 && !isset($this->cards[2])) {
            $this->blackJack = true;
            $this->result = 'blackJack';
            $this->emitTo('player.hand', 'croupierBlackJack');
        }
    }

    /**
     * @return boolean
     */
    public function checkIfPrimaryBust()
    {
        if ($this->primaryTotal > 21) {
            return true;
        }
        return false;
    }
    
    public function checkIfSecondaryBust()
    {
        if ($this->secondaryTotal > 21) {
            $this->croupierBust();
        }
    }

    public function enableSecondaryTotal()
    {
        $this->showSecondaryTotal = true;
    }

    public function disablePrimaryTotal()
    {
        $this->showPrimaryTotal = false;
    }

    public function croupierBust()
    {
        $this->croupierBust = true;
        $this->result = 'bust';
    }

    /**
     * Finalizando el juego 
     */
    public function gameFinished()
    {
        $this->emitTo('table.table', 'gameFinished');
    }

    public function clean()
    {
        $this->reset([
            'playing',
            'figures',
            'cards',
            'showPrimaryTotal',
            'showSecondaryTotal',
            'primaryTotal',
            'secondaryTotal',
            'croupierSum',
            'playerInsuranceBet',
            'blackJack',
            'result',
            'handsData',
            'croupierBust',
        ]);
    }

    public function render()
    {
        return view('livewire.croupier.croupier');
    }
}
