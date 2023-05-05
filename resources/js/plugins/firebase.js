import { initializeApp } from "firebase/app";
// import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional

const firebaseConfig = {
    apiKey: "AIzaSyDd5lk1EM5CskAfHC7XxJk9qmhwxRyTaXk",
    authDomain: "patradb-3e397.firebaseapp.com",
    projectId: "patradb-3e397",
    storageBucket: "patradb-3e397.appspot.com",
    messagingSenderId: "781348451228",
    appId: "1:781348451228:web:f61b462901dde4c9c88e59",
    measurementId: "G-88EC7B5LX9"
};
const firebase = initializeApp(firebaseConfig);

export default firebase
