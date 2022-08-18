<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;

class Hand extends Component
{
    /**
     * Numero identificador de mano
     * @var int
     */
    public $hand;

    public $bet;
    public $insuranceBet;
    public $enableHand = false;
    public $figures = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'J', 'Q', 'K'];
    public $cards = [];
    public $showPrimaryTotal = true;
    public $showSecondaryTotal = false;
    
    /**
     * Sumatoria con A=11
     * @var int
     */
    public $primaryTotal;

    /**
     * Sumatoria con A=1
     * @var int
     */
    public $secondaryTotal;

    /**
     * Suma total de la mano
     * @var int
     */
    public $handSum;

    /**
     * Cuando se elige la opcion de dividir la mano
     */
    public $splitMode = false;

    /**
     * Cuando se elige la opcion de hacer la apuesta de seguro
     */
    public $insuranceMode = false;
    
    public $showSplit = false;
    public $showDouble = true;
    public $showSurrender = true;
    public $bust = false;

    /**
     * Resultado de cada mano (en caso de haber dividido la misma)
     */
    public $result;

    protected $listeners = ['noMoney', 'betMade', 'makeInsuranceBet', 'insuranceBetMade', 'start', 'double', 'split', 'enableNextHand', 'croupierBlackJack', 'won', 'lost', 'tied', 'clean'];

    /**
     * @param int $hand
     * @param array $handData
     */
    public function mount($hand, $handData)
    {
        $this->hand = $hand;
        
        // Chequeando si se recibio una carta (value), en caso que se haya didivido la mano 
        if (isset($handData['value'])) {
            $this->cards[] = $handData;
            $this->partialSum($handData['value']);
            $this->splitMode = true;
        }
            $this->bet = $handData['bet'];  // Pasando la apuesta realizada en el componente Bet
    }

    // ****** Apuestas iniciales y de seguro ******

    /**
     * Asignando a la mano la apuesta realizada en el componente Bet 
     * @param float $bet
     */
    public function betMade($bet)
    {
        $this->bet = $bet;
    }

    /**
     * Le pasamos el valor de la apuesta de seguro al comp Player para chequear si hay dinero suficiente
     */
    public function makeInsuranceBet()
    {
        $this->insuranceBet = ($this->bet) / 2;
        $this->emitTo('player.player', 'makeInsuranceBet', $this->insuranceBet);
    }

    public function insuranceBetMade()
    {
        $this->insuranceMode = true;
        $this->emitTo('croupier.croupier', 'insuranceBetMade', $this->insuranceBet);
    }

    /**
     * Iniciando partida. Generando primeras 2 cartas. Revisando si hay BlackJack, dos cartas iguales o si se paso de 21.
     */
    public function start()
    {
        $this->enableHand = true;
        $this->generate();
        $this->generate();
        $this->checkIfBust();
        $this->checkIfEqualCards();
        $this->checkIfBlackJack();
    }

    // *********  Opciones del jugador ***********

    /** 
     * Dividir la mano en caso que toquen dos cartas del mismo valor
     * @param int $hand
     */
    public function split($hand)
    {
        if ($this->hand === $hand) {
            $this->showSplit = false;
            
            unset($this->cards[1]);
    
            $this->updatedSum();
    
            $this->splitMode = true;
    
            $handData = [
                'value' => $this->cards[0]['value'],
                'figure' => $this->cards[0]['figure'],
                'logo' => $this->cards[0]['logo'],
                'bet' => $this->bet,
            ];
            
            $this->emitTo('table.table', 'split', $handData);   
        }
    }
    
    /**
     * Duplicar la apuesta, jugar una carta y plantarse frente al croupier
     * @param int $hand
     */
    public function double($hand)
    {
        if ($this->hand === $hand) {
            $this->enableHand = false;
            $this->bet = $this->bet * 2;
    
            $this->generate();
            $this->checkIfBust();   // Solo se pasaria de 21 si fueran dos A=11, pero en ese caso se habilitaria la suma secundaria
            $this->checkIfTwentyOne();  
    
            if(!$this->bust) {
                $this->stand();
            }
        }
    }

    /**
     * Pedir mas cartas. Se generan nuevas y se va realizando la suma.
     */
    public function hit()
    {
        $this->showSplit = false;
        $this->showDouble = false;
        $this->showSurrender = false;

        $this->generate();
        $this->checkIfBust();
        $this->checkIfTwentyOne();
    }

    /**
     * Plantarse frente al croupier. Pasándole la suma total
     */
    public function stand()
    {
        $this->enableHand = false;

        if ($this->showPrimaryTotal) {
            $this->handSum = $this->primaryTotal;
        } else {
            $this->handSum = $this->secondaryTotal;
        }

        if($this->splitMode) {
            $handData = [
                'hand' => $this->hand,
                'handSum' => $this->handSum,
            ];
            $this->emitTo('croupier.croupier', 'passHandSum', $handData); 
            $this->handFinished();
        } else {
            $this->emitTo('croupier.croupier', 'stand', $this->handSum);
        }
    }

    /**
     * Retirarse del juego. Se devolvera la mitad de la apuesta
     */
    public function surrender()
    {
        $this->enableHand = false;
        $this->emitTo('player.player', 'payHalf', $this->bet);
        if($this->splitMode) {
            $this->result = 'surrendered';
            $this->handFinished();
        } else {
            $this->emitTo('table.player-notifications', 'surrendered'); 
            $this->emitTo('croupier.croupier', 'croupierPlay'); 
            $this->emitUp('gameFinished');
        }
    }

    // ***** Analizando el juego que toca *****

    public function checkIfEqualCards()
    {
        if ($this->cards[0]['value'] === $this->cards[1]['value']) {
            $this->showSplit = true;
        }
    }

    public function checkIfBlackJack()
    {
        if ($this->primaryTotal === 21) {
            $this->blackJack();
        }
    }

    /**
     * Chequeando si la mano supero los 21
     */
    public function checkIfBust()
    {
        if ($this->primaryTotal > 21) {
            // Si hay suma secundaria habilitada, deshabilitamos la suma primaria y continuamos la suma con la secundaria
            if ($this->showSecondaryTotal) {
                $this->disablePrimaryTotal();
            } else {
                $this->bust();
            }
        }
        if ($this->secondaryTotal > 21) {
            $this->bust();
        }
    }

    /**
     * Chequeando si se llego justo a 21. En ese caso, la mano deja automaticamente de jugar y se pasa ese valor al croupier
     */
    public function checkIfTwentyOne()
    {
        if ($this->primaryTotal === 21 || $this->secondaryTotal === 21) {
            $this->stand();
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
     * @return int $value
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

            // Si hay un As, habilitamos la suma secundaria (con A= 1)
            if ($rand === 1) {
            $this->primaryTotal += 11;
            $this->enableSecondaryTotal();
            } else {
                $this->primaryTotal += $rand;
            }
        }

        return $value;
    }

    /**
     * Actualizando la suma de la mano si el jugador eligio dividirla
     */
    public function updatedSum()
    {
        $value = $this->cards[0]['value'];

        $this->secondaryTotal -= $value;

        if ($value === 1) {
            $this->primaryTotal -= 11;
        } else {
            $this->primaryTotal -= $value;
        }
    }

    // **** Habilitando/deshabilitando sumas primarias y secundarias (A=1 ó A=11)

    public function enableSecondaryTotal()
    {
        $this->showSecondaryTotal = true;
    }

    public function disablePrimaryTotal()
    {
        $this->showPrimaryTotal = false;
    }

    public function disableSecondaryTotal()
    {
        $this->showSecondaryTotal = false;
    }

    // **** Resultados de la mano. La var result es para mostrar notificaciones individuales en cada mano en caso de dividirla

    public function blackJack()
    {
        $this->enableHand = false;
        $this->disableSecondaryTotal();

        if($this->splitMode) {
            $this->result = 'blackJack';
            // $this->emit('individualNotification'); // Al javascript
            $this->handFinished();
        } else {
            $this->emitTo('table.player-notifications', 'blackJack');
            $this->emitTo('player.player', 'blackJack', $this->bet);
            $this->emitTo('croupier.croupier', 'croupierPlay'); 
            $this->gameFinished();
        }
    }

    /**
     * BlackJack emitido desde el Croupier. Si se hizo la apuesta de seguro se pagara el doble de la misma
     */
    public function croupierBlackJack()
    {
        if ($this->insuranceMode) {
            $this->emitTo('player.player', 'won', $this->insuranceBet);
        }
    }

    /**
     * En caso de que se supere los 21, se da por finalizada la mano
     */
    public function bust()
    {
        $this->enableHand = false;
        $this->bust = true;

        if($this->splitMode) {
            $this->result = 'bust';
            // $this->emit('individualNotification'); // Al javascript
            $this->handFinished();
        } else {
            $this->emitTo('table.player-notifications', 'bust');
            $this->emitTo('croupier.croupier', 'croupierPlay'); 
            $this->gameFinished();
        }
    }

    /**
     * @param int $numberOfHand
     */
    public function won($numberOfHand)
    {
        if($numberOfHand === $this->hand) {
            $this->result = 'won';
            // $this->emit('individualNotification'); // Al javascript
            $this->emitTo('player.player', 'won', $this->bet);
        }
        if($numberOfHand === null) {
            $this->emitTo('player.player', 'won', $this->bet);
        }
    }

    /**
     * @param int $numberOfHand
     */
    public function lost($numberOfHand)
    {
        if($numberOfHand === $this->hand) {
            $this->result = 'lost';
            // $this->emit('individualNotification'); // Al javascript
        }
    }
    
    /**
     * @param int $numberOfHand
     */
    public function tied($numberOfHand)
    {
        if($numberOfHand === $this->hand) {
            $this->result = 'tied';
            $this->emitTo('player.player', 'tied', $this->bet);
        }
        if($numberOfHand === null) {
            $this->emitTo('player.player', 'tied', $this->bet);
        }  
    }

    /**
     * En caso de que haya manos multiples. Avisando al componente Table que esta mano termino.
     */
    public function handFinished()
    {
        $this->emitUp('handFinished', $this->hand);   
    }

    /**
     * Habilitando la mano siguiente. Si es esta, se habilitará para jugar
     * @param int $nextHand
     */
    public function enableNextHand($nextHand)
    {
        if ($this->hand === $nextHand) {
            $this->enableHand = true;
        }
    }

    /**
     * Finalizando el juego. Avisando al componente Table
     */
    public function gameFinished()
    {
        $this->emitUp('gameFinished');
    }

    public function render()
    {
        return view('livewire.player.hand');
    }
}
