<template>
    <b-row class="page-titles">
        <b-col cols="5" class="align-self-center"><h4 class="text-themecolor" v-text="titulo"></h4></b-col>
        <b-col cols="7">
            <div class="d-flex justify-content-end align-items-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" v-text="titulo"></li>
                </ol>
            </div>                    
        </b-col>
    </b-row>
</template>

<script>
    export default {
        props: [
            'titulo'
        ]
    }
</script>