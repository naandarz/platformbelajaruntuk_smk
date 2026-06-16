(function () {
    function applyTheme() {
        var theme = localStorage.getItem("lmsTheme") || "light";
        if (theme === "dark") {
            document.body.classList.add("dark-mode");
        } else {
            document.body.classList.remove("dark-mode");
        }
    }

    window.toggleLmsTheme = function () {
        var current = localStorage.getItem("lmsTheme") || "light";
        var next = current === "dark" ? "light" : "dark";
        localStorage.setItem("lmsTheme", next);
        applyTheme();

        if (window.showGooeyToast) {
            showGooeyToast({
                type: "info",
                title: "Tema Diubah",
                message: next === "dark" ? "Mode gelap aktif." : "Mode terang aktif."
            });
        }
    };

    document.addEventListener("DOMContentLoaded", applyTheme);
})();