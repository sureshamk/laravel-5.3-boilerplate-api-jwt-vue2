<template>
    <div class="content-wrapper">
        <header class="page-head">
            <div class="page-head">
                <h1>Welcome to Passport</h1>
            </div>
        </header>
        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="container">
                <div class="row">
                    <Clients></Clients>
                    <AuthorizedClients></AuthorizedClients>
                    <PersonalAccessTokens></PersonalAccessTokens>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import AuthorizedClients from '../../components/passport/AuthorizedClients.vue'
    import Clients from '../../components/passport/Clients.vue'
    import PersonalAccessTokens from '../../components/passport/PersonalAccessTokens.vue'


    export default {
        components: {
            PersonalAccessTokens,AuthorizedClients,Clients
        }
    }
</script>