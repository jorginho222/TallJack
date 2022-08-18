<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;

class Edit extends Component
{
    public $name;
    public $selectedAvatarPath;
    public $availableAvatarsPath;
    public $availableAvatars = [1, 2, 3, 4];
    
    /**
     * @param string $name
     * @param string $currentAvatarPath
     */
    public function mount($name, $currentAvatarPath)
    {
        $this->name = $name;
        $this->selectedAvatarPath = $currentAvatarPath;
        // Asignando ubicacion a los avatares disponibles para ser mostrados
        foreach ($this->availableAvatars as $availableAvatar) {
            $this->availableAvatarsPath[] = 'img/card-logos/' . $availableAvatar . '.png';
        }
    }

    public function select($selectedAvatar)
    {
        $this->selectedAvatarPath = $selectedAvatar;
    }

    public function render()
    {
        return view('livewire.player.edit');
    }
}
