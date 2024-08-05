import "./bootstrap";
import "./themeMode";

/**
 * Livewire (& AlpineJS)
*/
import {
    Alpine,
    Livewire,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
// Tall Toasts
import ToastComponent from "../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts";
Alpine.plugin(ToastComponent);
// Start Livewire
Livewire.start();

// Import the custom javascript that required livewire / alpine
import "./livewire/pageExpired"

// Console log from Livewire
window.addEventListener("console.log", (event) => {
    console.log(event.detail[0]);
});

