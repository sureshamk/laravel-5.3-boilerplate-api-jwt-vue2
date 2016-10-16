<template>
    <div>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <li class="header">TOOLS</li>
                    <li>
                        <router-link to="/"><i class="fa fa-desktop"></i> Dashboard</router-link>
                    </li>
                    <li>
                        <router-link to="/users"><i class="fa fa-desktop"></i> Users </router-link>
                    </li>
                    <li>
                        <router-link to="/passport"><i class="fa fa-desktop"></i> Passport </router-link>
                    </li>

                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
    </div>
</template>

<script>
    export default {
        ready() {
            console.log('Component ready.')
        },
        data(){
            return {
                message: "side Bar "
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
