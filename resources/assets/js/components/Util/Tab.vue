<template>
    <div role="tabpanel" class="tab-pane" :class="{'active':active}" :id="name">
        <slot></slot>
    </div>
</template>
<script>
    export default{
        props: {
            title:{type:String,required: true},
            active:{type:[Boolean,String],default:false},
            name:{type:String,required: true}
        }
    }
</script>