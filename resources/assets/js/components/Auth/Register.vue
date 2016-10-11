<template>
    <div>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="http://localhost:8000">
                        My Application
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <li>
                            <router-link to="/login">
                                Login
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/register">
                                Register
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Registration</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" @submit.prevent="register">
                                <p v-if="$route.query.redirect">
                                    You need to login first.
                                </p>
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Name</label>

                                    <div class="col-md-6">
                                        <input id="name" v-model="name" type="text" class="form-control" name="name"
                                               value="" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" v-model="email" type="email" class="form-control" name="email"
                                               value="" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-md-4 control-label">Password </label>

                                    <div class="col-md-6">
                                        <input id="password" v-model="password" type="password" class="form-control"
                                               name="password" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="col-md-4 control-label">Confirm
                                        Password </label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" v-model="passwordConfirm" type="password"
                                               class="form-control"
                                               name="password-confirm" required>
                                    </div>
                                </div>
                                <p v-if="error" class="error">Bad login information</p>
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../../auth'
    export default {
        data () {
            return {
                email: '',
                password: '',
                name: '',
                passwordConfirm: '',
                error: false
            }
        },
        methods: {
            register () {
                auth.register(this, registration => {
                    if (!registration) {
                        this.error = true
                    } else {
                        console.log('registration done');
                    }
                })
            }
        }
    }
</script>

<style>
    .error {
        color: red;
    }
</style>