importScripts('https://www.gstatic.com/firebasejs/10.12.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.1/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyBMoAB5a3FIUZz_ciFCnBJ6WIotd2jxVtA",
  authDomain: "todo-fcm-notify.firebaseapp.com",
  projectId: "todo-fcm-notify",
  messagingSenderId: "679014377974",
  appId: "1:679014377974:web:cb02723a81f82184823fd7",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  console.log('[firebase-messaging-sw.js] Background message received:', payload);
  
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/icon.png',
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
