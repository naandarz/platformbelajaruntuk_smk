(function () {
    function ensureRoot() {
        var root = document.getElementById("toast-root");
        if (!root) {
            root = document.createElement("div");
            root.id = "toast-root";
            document.body.appendChild(root);
        }

        if (!document.getElementById("gooey-toast-svg-filter")) {
            var svg = document.createElement("div");
            svg.id = "gooey-toast-svg-filter";
            svg.innerHTML = `
                <svg width="0" height="0" style="position:absolute">
                    <defs>
                        <filter id="gooey-toast-filter">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />
                            <feColorMatrix in="blur" mode="matrix"
                                values="1 0 0 0 0
                                        0 1 0 0 0
                                        0 0 1 0 0
                                        0 0 0 18 -7"
                                result="gooey" />
                            <feComposite in="SourceGraphic" in2="gooey" operator="atop"/>
                        </filter>
                    </defs>
                </svg>
            `;
            document.body.appendChild(svg);
        }

        return root;
    }

    function iconFor(type) {
        if (type === "success") return "✓";
        if (type === "error") return "!";
        if (type === "warning") return "⚠";
        if (type === "game") return "🎮";
        return "i";
    }

    function titleFor(type) {
        if (type === "success") return "Berhasil";
        if (type === "error") return "Gagal";
        if (type === "warning") return "Perhatian";
        if (type === "game") return "Game Coding";
        return "Informasi";
    }

    window.showGooeyToast = function (options) {
        var root = ensureRoot();
        var type = options.type || "info";
        var title = options.title || titleFor(type);
        var message = options.message || "";
        var duration = options.duration || 3800;

        var toast = document.createElement("div");
        toast.className = "gooey-toast gooey-toast-" + type;

        toast.innerHTML = `
            <div class="gooey-orb orb-one"></div>
            <div class="gooey-orb orb-two"></div>
            <div class="gooey-toast-content">
                <div class="gooey-toast-icon">${iconFor(type)}</div>
                <div class="gooey-toast-text">
                    <strong>${title}</strong>
                    <span>${message}</span>
                </div>
                <button class="gooey-toast-close" type="button" aria-label="Tutup">×</button>
            </div>
            <div class="gooey-toast-progress"></div>
        `;

        root.appendChild(toast);

        var progress = toast.querySelector(".gooey-toast-progress");
        progress.style.animationDuration = duration + "ms";

        var close = function () {
            toast.classList.add("hide");
            setTimeout(function () {
                if (toast && toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 420);
        };

        toast.querySelector(".gooey-toast-close").addEventListener("click", close);
        setTimeout(close, duration);
    };

    function readToastFromUrl() {
        var params = new URLSearchParams(window.location.search);

        if (params.get("login") === "1") {
            showGooeyToast({
                type: "success",
                title: "Login Berhasil",
                message: "Selamat datang di Platform Belajar untuk SMK."
            });
        }

        if (params.get("saved") === "1") {
            showGooeyToast({
                type: "success",
                title: "Data Tersimpan",
                message: "Data berhasil disimpan ke sistem."
            });
        }

        if (params.get("status")) {
            var status = params.get("status");
            var messageMap = {
                "ditambah": "Data berhasil ditambahkan.",
                "diupdate": "Data berhasil diperbarui.",
                "dihapus": "Data berhasil dihapus.",
                "setting": "Setting berhasil diperbarui.",
                "toggle": "Status berhasil diubah.",
                "selesai": "Materi berhasil ditandai selesai.",
                "gagalhapus": "Data tidak dapat dihapus."
            };

            showGooeyToast({
                type: status === "gagalhapus" ? "warning" : "success",
                title: status === "gagalhapus" ? "Perhatian" : "Berhasil",
                message: messageMap[status] || "Aksi berhasil diproses."
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        ensureRoot();
        readToastFromUrl();
    });
})();