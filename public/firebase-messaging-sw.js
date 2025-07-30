// public/firebase-messaging-sw.js

importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js')
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js')

// ✅ Your Firebase config
firebase.initializeApp({
  apiKey: "AIzaSyD7F1txFw8crG11N5tvT0lLAIaICw9KVhE",
  authDomain: "test-notification-630e4.firebaseapp.com",
  projectId: "test-notification-630e4",
  storageBucket: "test-notification-630e4.firebasestorage.app",
  messagingSenderId: "804023330597",
  appId: "1:804023330597:web:be5ebd77c1b696e2e55ccc"
})

// ✅ Initialize Firebase Messaging
const messaging = firebase.messaging()

// ✅ Handle background messages
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload)

  const notificationTitle = payload.notification.title
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/firebase-logo.png', // optional icon
  }

  self.registration.showNotification(notificationTitle, notificationOptions)
})
