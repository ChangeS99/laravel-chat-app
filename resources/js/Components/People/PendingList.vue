<script setup>

import FriendCard from './FriendCard.vue';

const props = defineProps({
    pending_requests: Object
})


</script>

<template>
    <div class="relative flex-1">
        <div v-if="props.pending_requests.length === 0"
            class="flex items-center justify-center flex-1 min-w-full min-h-full">
            <h1 class="z-20 text-5xl text-center text-black ">Nothing to Show</h1>
        </div>
        <div class="absolute top-0 bottom-0 left-0 right-0 overflow-auto">
            <div class="grid min-w-full grid-cols-4 p-4">
                <FriendCard from="pending" v-for="pending in props.pending_requests" :key="pending.id"
                    :user="pending" />
            </div>
        </div>
    </div>
</template>