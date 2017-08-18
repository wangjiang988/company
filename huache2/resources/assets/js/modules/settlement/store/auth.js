export default {
    state: {
        user_phone  : '',
    },
    initialize() {
        this.login         = true;
    },
    set(user_id) {
        localStorage.setItem('user_id', user_id)
        this.initialize()
    },
    remove(user_id) {
        // localStorage.removeItem('api_token')
        localStorage.removeItem('user_id')
        this.initialize()
    }
}