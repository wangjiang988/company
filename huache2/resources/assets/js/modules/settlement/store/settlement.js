export default {
    state: {
        settlement  : {}
    },
    initialize() {
        let string = localStorage.getItem('settlement')
        this.state.settlement  =  JSON.parse(string)
    },
    set(settlement) {
        let string =  JSON.stringify(settlement)
        localStorage.setItem('settlement', string)
        this.initialize()
    },
    remove() {
        localStorage.removeItem('settlement')
        this.initialize()
    }
}