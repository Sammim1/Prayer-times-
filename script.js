const GITHUB_USER = "Sammim1";
const REPO_NAME = "All-information-related-to-prayer";

// ১. অ্যাডমিন সেটিংস লোড করা
async function loadAdminSettings() {
    try {
        const response = await fetch(`https://raw.githubusercontent.com/${GITHUB_USER}/${REPO_NAME}/main/settings.json?t=${Date.now()}`);
        const config = await response.json();
        document.querySelector(".developer-name").innerText = config.dev_name;
        document.documentElement.style.setProperty('--neon', config.neon_color);
        document.body.style.background = config.bg_color;
    } catch (e) { console.log("Settings Load Error"); }
}

// ২. ঘড়ি চালানো
function updateClock() {
    setInterval(() => {
        const now = new Date();
        document.getElementById("clock").innerText = now.toLocaleTimeString('bn-BD');
        document.getElementById("today").innerText = now.toLocaleDateString('bn-BD', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }, 1000);
}

// ৩. নামাজের ডাটা (সর্বপ্রথম যা ছিল)
async function fetchPrayerTimes() {
    // এখানে আপনার সেই আগের নামাজের এপিআই ফাংশনটি বসবে
    // উদাহরণস্বরূপ:
    console.log("নামাজের ডাটা লোড হচ্ছে...");
}

window.onload = () => {
    loadAdminSettings();
    updateClock();
    fetchPrayerTimes();
};
