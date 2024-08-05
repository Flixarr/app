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
