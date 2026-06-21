function openSmartLearnSidebar() {
    document.body.classList.add("sidebar-open");
}

function closeSmartLearnSidebar() {
    document.body.classList.remove("sidebar-open");
}

function toggleSmartLearnSidebar() {
    document.body.classList.toggle("sidebar-open");
}

document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".sidebar .menu a");
    links.forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 920) {
                closeSmartLearnSidebar();
            }
        });
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeSmartLearnSidebar();
        }
    });
});
