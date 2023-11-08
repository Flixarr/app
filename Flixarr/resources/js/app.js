import "./bootstrap";

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
// Theme Mode
import "./themeMode";
// Start Livewire
Livewire.start();

window.addEventListener("log", (event) => {
    console.log(event.detail[0]);
});

// Custom Page Expiration Behavior
function startCountdown(seconds, callback) {
    const interval = setInterval(() => {
        seconds--;
        document.getElementById("session_expired_timer").innerHTML = seconds;

        if (seconds <= 0) {
            clearInterval(interval);
            callback();
        }
    }, 1000);
}

Livewire.hook("request", ({ fail }) => {
    fail(({ status, preventDefault }) => {
        if (status === 419) {
            Toast.danger(
                "Your session has expired. The page will automatically refresh in <span id='session_expired_timer'>5</span> seconds.",
                "Session Expired",
                0
            );

            startCountdown(5, () => {
                location.reload();
            });

            preventDefault();
        }
    });
});
