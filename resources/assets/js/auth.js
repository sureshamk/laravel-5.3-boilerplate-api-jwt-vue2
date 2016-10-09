/* globals localStorage */

export default {
    login(email, pass, cb) {
        cb = arguments[arguments.length - 1]
        if (localStorage.token) {
            if (cb) cb(true)
            this.onChange(true)
            return
        }
        pretendRequest(email, pass, (res) => {
            if (res.authenticated) {
                localStorage.token = res.token
                if (cb) cb(true)
                this.onChange(true)
            } else {
                if (cb) cb(false)
                this.onChange(false)
            }
        })
    },

    getToken() {
        return localStorage.token
    },

    logout (cb) {
        delete localStorage.token
        if (cb) cb()
        this.onChange(false)
    },

    loggedIn() {
        return !!localStorage.token
    },

    onChange () {
    }
}

function pretendRequest(email, pass, cb) {
    setTimeout(() => {
        Vue.http.post('/api/auth/login', {
            email: email,
            password: pass
        }).then((response) => {
            if (response.data.token) {
                cb({
                    authenticated: true,
                    token: response.data.token
                })
            } else {
                console.error('token not retrived')
            }
        }, (response) => {
            console.error('token not retrived')
            cb({authenticated: false})
        });
    }, 0)
}