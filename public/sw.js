self.addEventListener('push', function(event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    let data = {};
    if (event.data) {
        data = event.data.json();
    }

    const title = data.title || "PresGo Notification";
    const options = {
        body: data.body || "Anda mendapatkan pesan baru.",
        icon: data.icon || "/favicon.ico",
        badge: "/favicon.ico",
        data: data.data || {},
        vibrate: [200, 100, 200, 100, 200, 100, 200],
        actions: data.actions || []
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    if (event.action === 'presensi_action' && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    } else if (event.action === 'history_action') {
        event.waitUntil(
            clients.openWindow('/history')
        );
    } else {
        event.waitUntil(
            clients.openWindow('/notifikasi')
        );
    }
});
