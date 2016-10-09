<template>
    <header class="main-header">

        <nav class="navbar navbar-default navbar-fixed-top">
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            suresh <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <router-link to="/logout"><i class="fa fa-desktop"></i> Logout</router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

    </header>

</template>

<script>
    export default {
        ready() {
            console.log('Component ready.')
        },
        data(){
            return {
                message: "Hey buddy !! we started"
            }

        },
        computed: {
            demo: function () {
                return {
                    displayName: "smaple",
                    avatar: "smaple",
                    email: "smaple",
                    randomCard: "smaple",
                }
            }
        }
    }
</script>
