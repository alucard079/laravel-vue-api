<template>
    <div>
        <h1>Good day {{ user ? user.name : "" }} !</h1>
        <b-card title="Google Map">
            <GmapMap
                :center="{ lat: 10, lng: 10 }"
                :zoom="7"
                map-type-id="terrain"
                style="width: 100%; height: 700px"
            >
                <GmapMarker
                    v-for="(m, index) in markers"
                    :key="index"
                    :position="m.position"
                    :clickable="true"
                    :draggable="true"
                    @click="center = m.position"
                />
            </GmapMap>
        </b-card>
    </div>
</template>
<script>
export default {
    data() {
        return {
            user: this.auth.user,
            markers: [],
        };
    },
    created() {},
};
</script>
