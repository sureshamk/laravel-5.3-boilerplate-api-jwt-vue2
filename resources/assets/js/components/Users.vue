<template>
    <div class="content-wrapper">
        <header class="page-head">
            <div class="page-head">
                <h1>Users</h1>
            </div>
        </header>
        <!-- Main content -->
        <section class="main-content">
            <tabs>
                <tab name="Users" title="Users" active=true>
                    <div id="users">
                        <v-server-table url="/api/users" :columns="columns" :options="options"></v-server-table>
                    </div>
                </tab>
                <tab name="Roles" title="Roles">
                    <div id="roles">
                        <v-server-table url="/api/roles" :columns="columns" :options="options"></v-server-table>
                    </div>
                </tab>
                <tab name="Permissions" title="Permissions">
                    <div id="permissions">
                        <v-server-table url="/api/permissions" :columns="columns" :options="options"></v-server-table>
                    </div>
                </tab>
            </tabs>
        </section>
    </div>
</template>
<script>
    import tabs from '../components/Util/Tabs.vue'
    import tab from '../components/Util/Tab.vue'
    import React from 'react'
    export default {
        data() {
            return {
                columns: ['id', 'name', 'email', 'erase'],
                options: {
                    templates: {
                        erase: function (h, row) {
                            return <delete id={row.id}></delete>
                        }
                    }
                }
            }
        },
        components: {
            tabs, tab
        }
    }
</script>