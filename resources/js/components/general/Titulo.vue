<template>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor" v-text="titulo"></h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" v-text="titulo"></li>
                </ol>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'titulo'
        ]
    }
</script>