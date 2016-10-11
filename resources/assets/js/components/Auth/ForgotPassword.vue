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
                        <div class="panel-heading"> Retrieve password</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" @submit.prevent="login">
                                <p v-if="$route.query.redirect">
                                    You need to login first.
                                </p>
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" v-model="email" type="email" class="form-control" name="email"
                                               value="" required autofocus>

                                    </div>
                                </div>
                                <p v-if="error" class="error">Bad login information</p>
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Send Password reset link
                                        </button>
                                        <router-link to="/login">
                                            Login
                                        </router-link>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--<form @submit.prevent="login">-->
        <!--<label><input v-model="email" placeholder="email"></label>-->
        <!--<label><input v-model="pass" placeholder="password" type="password"></label> (hint: password1)<br>-->
        <!--<button type="submit">login</button>-->
        <!--<p v-if="error" class="error">Bad login information</p>-->
        <!--</form>-->
    </div>
</template>

<script>
    import auth from '../../auth'
    export default {
        data () {
            return {
                email: 'joe@example.com',
                pass: '',
                error: false
            }
        },
        methods: {
            login () {
                auth.login(this.email, this.pass, loggedIn => {
                    if (!loggedIn) {
                        this.error = true
                    } else {
                        this.$router.replace(this.$route.query.redirect || '/')
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