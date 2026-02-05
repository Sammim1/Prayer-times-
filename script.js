const GITHUB_USER = "Sammim1";
const REPO_NAME = "All-information-related-to-prayer";

async function loadAdminSettings() {
    try {
        const response = await fetch(`https://raw.githubusercontent.com/${GITHUB_USER}/${REPO_NAME}/main/settings.json?t=${Date.now()}`);
        const config = await response.json();

        if(config.dev_name) document.querySelector(".developer-name").innerText = config.dev_name;
        if(config.neon_color) document.documentElement.style.setProperty('--neon', config.neon_color);
        if(config.bg_color) document.body.style.background = config.bg_color;
    } catch (e) {
        console.log("Settings failed to load.");
    }
}

function updateClock() {
    setInterval(() => {
        const now = new Date();
        document.getElementById("clock").innerText = now.toLocaleTimeString('bn-BD');
        document.getElementById("today").innerText = now.toLocaleDateString('bn-BD', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }, 1000);
}

window.onload = () => {
    loadAdminSettings();
    updateClock();
};
