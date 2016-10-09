<template>
    <div id="app">
        <template v-if="$route.matched.length">
            <router-view></router-view>
        </template>
        <template v-else>
            <p>You are logged {{ loggedIn ? 'in' : 'out' }}</p>
        </template>
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
