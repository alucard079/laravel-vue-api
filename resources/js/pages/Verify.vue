<template>
    <div>
        
    </div>
</template>

<script>
export default {
    created() {
        this.onVerify();
    },
    methods: {
        onVerify() {
            this.axios.post(`/api/verify/${this.$route.params.email}`)
            .then(response => {
                let data = response.data;
                if(response.status === 200) {
                    this.$swal({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                    }).then((res) => {
                        if(res.isConfirmed) {
                            this.$router.push('/');
                        }
                    });
                }
            })
            .catch(error => {
                console.log(error)
            });
        }
    },
}
</script>