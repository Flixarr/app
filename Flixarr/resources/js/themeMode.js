Alpine.data("themeMode", () => ({
    changeThemeMode() {
        switch (localStorage.theme) {
            case "light":
                console.log('Switching theme mode to "dark".');
                document.documentElement.classList.add("dark");
                localStorage.theme = "dark";
                break;
            case "dark":
                console.log('Switching theme mode to "light".');
                document.documentElement.classList.remove("dark");
                localStorage.theme = "light";
                break;
        }
    },
}));
