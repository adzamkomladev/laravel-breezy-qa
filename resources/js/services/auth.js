export default class Auth {
    static currentUser() {
        return window.authState.user || {};
    }

    static isLoggedIn() {
        return window.authState.isLoggedIn;
    }
}
