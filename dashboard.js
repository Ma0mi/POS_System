function updateTime() {
    var now = new Date();
    var date = now.toLocaleDateString('th-TH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    var time = now.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('currentDateTime').innerHTML = date + ' ' + time;
}

updateTime();
setInterval(updateTime, 1000);

