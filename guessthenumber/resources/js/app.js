import './bootstrap';

function initCountdown() {
    const countdownElement = document.getElementById('countdown');
    if (!countdownElement) return;
    
    let localTime = parseInt(countdownElement.textContent) || 0;
    let syncCounter = 0;

    setInterval(() => {
        if (syncCounter % 10 === 0) {
            ajax('/countdown', 'GET', {}, function(data) {
                localTime = data.time_remaining;
                countdownElement.textContent = localTime;
            });
        }
        
        if (localTime > 0) {
            localTime--;
            countdownElement.textContent = localTime;
        }
        
        syncCounter++;
    }, 1000);
}

function ajax(url, method, data, success) {
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: method !== 'GET' ? JSON.stringify(data) : undefined
    })
    .then(r => r.json())
    .then(success);
}

document.addEventListener('DOMContentLoaded', initCountdown);