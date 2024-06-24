<?php

namespace App\Http\Pages\Setup;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class SetupIndex extends Component
{
    use WireToast;

    /**
     * Page step (str) | "auth", "server", "services"
     *
     * The step in which the setup process is in.
     */
    public $step;

    #[Layout('layouts.minimal', ['title' => 'Setup'])]
    public function render()
    {
        return view('pages.setup.setup-index');
    }

    /**
     * Initialize the setup page by setting the setup step
     *
     * @return void
     */
    function load(): void
    {
        // Set the correct setup step
        $this->setSetupStep();

        (!$this->step) && $this->nextStep();
    }

    /**
     * Set Setup Step
     * Sets the correct setup step
     *
     * @return void
     */
    function setSetupStep(): void
    {
        $this->step = settings('setup_step');
    }

    /**
     * Next Step
     *
     * Go to the next step
     *
     * @return void
     */
    #[On('setup-next-step')]
    function nextStep(): void
    {
        $this->step++;

        settings(['setup_step' => $this->step]);
    }

    /**
     * Previous Step
     *
     * Go to the previous step
     *
     * @return void
     */
    #[On('setup-prev-step')]
    function prevStep(): void
    {
        $this->step--;

        settings(['setup_step' => $this->step]);
    }
}
