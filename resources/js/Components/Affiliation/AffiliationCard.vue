<template>
    <div class="col-md-4">
        <div class="well well-inverted rounded-card">
            <div class="row margin-vertical-xs-16">
                <div class="flex-center">
                    <div class="col-lg-4 col-sm-2 col-xs-4">
                        <img class="affiliation-logo" :alt="name" :src="imgSrc">
                    </div>
                    <div class="col-sm-8 padding-left-xs-4">
                        <div class="pull-left full-width truncate margin-bottom-xs-4">
                            <a :href="link" class="color-inherit">{{ name }}</a>
                        </div>
                        <div class="pull-left full-width">
                            <span v-if="medals" class="label label-default">{{ medals }}</span>
                            <small>{{ stats }}</small>
                            <div class="muted stats-members">{{ athletes }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mute-more">
        </div>
    </div>
</template>


<script setup>
defineProps({
    title: String,
    imgSrc: String,
    name: String,
    stats: String,
    athletes: String,
    medals: { type: String, default: null },
    link: { type: String, default: "#" },
});
</script>

<style scoped>
.well-inverted {
    background: rgba(231, 234, 255, 0.1);
    color: #f4f6fb;
    border-color: transparent;
    padding: 20px; /* Added padding for spacing */
    border-radius: 7px; /* Rounded corners */
}

.rounded-card {
    border-radius: 7px; /* Apply rounded corners */
    overflow: hidden; /* Ensures child elements respect border-radius */
}

.flex-center {
    display: flex;
    align-items: center;
    gap: 16px; /* Added gap between the image and text */
}

.affiliation-logo {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    display: block;
}

.pull-left {
    display: block;
    width: 100%;
}

.full-width {
    width: 100%;
}

.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.margin-bottom-xs-4 {
    margin-bottom: 4px;
}

.muted {
    color: #6c757d;
}

.mute-more {
    border-top: 1px solid #6c757d;
}

.stats-members {
    font-size: 0.9rem;
    color: #9e9e9e;
}

.padding-left-xs-4 {
    padding-left: 1rem;
}

.color-inherit {
    color: inherit;
}

.label-default {
    background-color: #4CAF50;
    color: white;
    padding: 3px 8px;
    border-radius: 5px;
}
</style>
