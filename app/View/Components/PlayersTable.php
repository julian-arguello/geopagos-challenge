<?php

namespace App\View\Components;

use App\Models\Gender;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PlayersTable extends Component
{

    public $players;
    public $gender_id;
    public $genderOptions;

    /**
     * Create a new component instance.
     */
    public function __construct($genderId, $players = [])
    {
        $this->players = $players;
        $this->gender_id = $genderId;
        $this->genderOptions = Gender::getGenderOptions();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.players-table');
    }
}
