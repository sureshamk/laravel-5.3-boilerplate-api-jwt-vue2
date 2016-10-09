<template>
    <div>
        <h2>About</h2>
        <div id="app">
            {{$route.matched.length}}
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    import auth from '../auth'
    export default {
        data () {
            return {
                loggedIn: auth.loggedIn()
            }
        },
        created () {
            auth.onChange = loggedIn => {
                this.loggedIn = loggedIn
            }
        }
    }
</script>
