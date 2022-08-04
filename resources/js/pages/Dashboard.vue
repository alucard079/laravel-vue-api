<template>
    <div>
        <h1>Good day {{user.name}} !</h1>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                user: this.auth.user
            }
        },
        created() {
        }
    }
</script>