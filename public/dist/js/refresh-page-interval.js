$(document).ready(function () {
    var activityTimer;
    var refreshInterval = 600000; // 10 menit dalam milidetik
    var activityTimeout = 3000; // 3 detik dalam milidetik

    function resetActivityTimer() {
        clearTimeout(activityTimer);
        activityTimer = setTimeout(function () {
            // Tidak ada aktivitas selama 3 detik
            document.isUserInactive = true;
        }, activityTimeout);
        document.isUserInactive = false;
    }

    function refreshPage() {
        if (document.isUserInactive) {
            location.reload(); // Refresh seluruh halaman
        }
    }

    // Mendeteksi aktivitas keyboard
    $(document).on("keypress", resetActivityTimer);

    // Mendeteksi aktivitas mouse
    $(document).on("mousemove click scroll", resetActivityTimer);

    // Mengatur timer aktivitas pertama kali
    resetActivityTimer();

    // Interval untuk memeriksa dan melakukan refresh setiap 10 menit
    setInterval(refreshPage, refreshInterval);
});
