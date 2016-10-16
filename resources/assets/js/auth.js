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
    register(data, cb){
        Vue.http.post('/api/auth/signup', {
            name: data.name,
            email: data.email,
            password: data.password,
            password_confirmation: data.passwordConfirm
        }).then((response) => {
            if (response.data.token) {
                cb({
                    registration: true,
                    token: response.data.token
                })
            } else {
                console.error('token not retrived')
            }
        }, (response) => {
            console.error('token not retrived')
            cb({registration: false})
        });
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
        Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
        // Vue.http.post('/api/auth/me').then((response) => {
        //     console.log(1);
        //     return true;
        // }, (response) => {
        //     console.log(2);
        //     return false;
        // });
        return localStorage.getItem('token')
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