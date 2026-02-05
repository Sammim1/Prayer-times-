const GITHUB_USER = "YOUR_USERNAME"; // আপনার GitHub ইউজারনাম দিন
const REPO_NAME = "YOUR_REPO"; // আপনার রিপোজিটরির নাম দিন

async function loadAdminSettings() {
    try {
        const response = await fetch(`https://raw.githubusercontent.com/${GITHUB_USER}/${REPO_NAME}/main/settings.json?t=${Date.now()}`);
        const config = await response.json();

        if(config.dev_name) document.querySelector(".developer-name").innerText = config.dev_name;
        if(config.neon_color) {
            document.documentElement.style.setProperty('--neon', config.neon_color);
        }
        if(config.bg_color) document.body.style.background = config.bg_color;
    } catch (e) {
        console.log("Error loading settings");
    }
}

// আপনার আগের নামাজের সময়সূচীর সব ফাংশন (initApp, syncTime, fetchMonthly ইত্যাদি) এখানে থাকবে।

window.onload = () => {
    loadAdminSettings();
    initApp(); 
};
