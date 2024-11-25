document.addEventListener("DOMContentLoaded", () => {
    const links = document.querySelectorAll(".nav-link");
    const mainContent = document.getElementById("main-content");

    links.forEach(link => {
        link.addEventListener("click", async (e) => {
            e.preventDefault();
            const contentName = link.getAttribute("data-content");

            // Fetch the corresponding Twig template (rendered server-side)
            const response = await fetch(`/content/${contentName}`);
            if (response.ok) {
                const html = await response.text();
                mainContent.innerHTML = html;
            } else {
                mainContent.innerHTML = `<p class="text-red-500">Error loading content.</p>`;
            }
        });
    });
});
