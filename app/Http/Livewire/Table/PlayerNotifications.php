<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;

class PlayerNotifications extends Component
{
    public $status;

    protected $listeners = ['noMoney', 'blackJack', 'bust', 'surrendered', 'won', 'lost', 'tied', 'clean'];

    // **** Todas las notificaciones que apareceran en la mesa ****
    
    public function noMoney()
    {
        $this->status = 'noMoney';
        $this->emit('showNotification'); // Al javascript
    }

    public function blackJack()
    {
        $this->status = 'blackJack';
        $this->emit('showNotification'); // Al javascript
    }

    public function bust()
    {
        $this->status = 'bust';
        $this->emit('showNotification'); // Al javascript
    }

    public function surrendered()
    {
        $this->status = 'surrendered';
        $this->emit('showNotification'); // Al javascript
    }

    public function won()
    {
        $this->status = 'won';
        $this->emit('showNotification'); // Al javascript
    }

    public function lost()
    {
        $this->status = 'lost';
        $this->emit('showNotification'); // Al javascript
    }

    public function tied()
    {
        $this->status = 'tied';
        $this->emit('showNotification'); // Al javascript
    }

    public function render()
    {
        return view('livewire.table.player-notifications');
    }

    public function clean()
    {
        $this->reset([
            'status',
        ]);
    }
}
