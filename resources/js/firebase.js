import { initializeApp } from 'firebase/app'
import { getMessaging, getToken, onMessage } from 'firebase/messaging'

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBMoAB5a3FIUZz_ciFCnBJ6WIotd2jxVtA",
    authDomain: "todo-fcm-notify.firebaseapp.com",
    projectId: "todo-fcm-notify",
    storageBucket: "todo-fcm-notify.firebasestorage.app",
    messagingSenderId: "679014377974",
    appId: "1:679014377974:web:cb02723a81f82184823fd7",
    vapidKey: "BBANH6xa064NLHMBAGQs6Y2-dahc67cmBXN68hRs1Qy8Kaeq841wuxd3w-XsO4EXZYysxzlNLkt5cm5kVdPQFek" // From Firebase Web Push Certificates

};

const firebaseApp = initializeApp(firebaseConfig);
const messaging = getMessaging(firebaseApp);

export { messaging, getToken, onMessage };

