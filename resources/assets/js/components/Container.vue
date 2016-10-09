<template>
    <div id="app">
        <AppHeader></AppHeader>
        <AppSidebar></AppSidebar>
        <router-view></router-view>
        <AppFooter></AppFooter>

    </div>
</template>
<script>
    import AppHeader from '../components/AppHeader.vue'
    import AppFooter from '../components/AppFooter.vue'
    import AppSidebar from '../components/AppSidebar.vue'

    export default {
        components: {
            AppHeader, AppFooter, AppSidebar
        }
    }
</script>