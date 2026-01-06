// Temporary measure to mitigate broken tabs.
document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener('popstate', function (event) {
        if (event.state) {
            Livewire.navigate(window.location.pathname);
        }
    });
});
