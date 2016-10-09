<template>
    <div class="content-wrapper">
        <header class="page-head">
            <div class="page-head">
                <h1>Welcome to dashboard</h1>
            </div>
        </header>
        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="info-box-content">
                            <div class="info-box-text">CPU Traffic
                                <br> 90
                                <small>%</small>
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="info-box-content">
                            <div class="info-box-text">CPU Traffic
                                <br> 90
                                <small>%</small>
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="info-box-content">
                            <div class="info-box-text">CPU Traffic
                                <br> 90
                                <small>%</small>
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="info-box-content">
                            <div class="info-box-text">CPU Traffic
                                <br> 90
                                <small>%</small>
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>
        </section>
        <tabs>
            <tab name="t1" title="Tab 1" active=true>
                Hello World
            </tab>
            <tab name="t2" title="Tab 2">
                Hello World 2
            </tab>
            <tab name="t3" title="Tab 3">
                Hello World 3
            </tab>
        </tabs>
    </div>
</template>
<script>
    import tabs from '../components/Util/Tabs.vue'
    import tab from '../components/Util/Tab.vue'


    export default {
        components: {
             tabs,tab
        }
    }
</script>