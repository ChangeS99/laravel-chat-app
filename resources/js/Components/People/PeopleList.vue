<script setup>
import { ref, computed } from "vue";
import FriendCard from './FriendCard.vue';

const emits = defineEmits([
    'update-list'
])


const props = defineProps({
    list: Object,
    from: String
})

const updateList = (id, type) => {
    emits('update-list', id, type);
}

const list_length = computed(() => props.list.length)
</script>

<template>
    <div class="relative flex-1">
        <div v-if="list_length === 0" class="flex items-center justify-center flex-1 min-w-full min-h-full">
            <h1 class="z-20 text-5xl text-center text-black ">Nothing to Show</h1>
        </div>
        <div class="absolute top-0 bottom-0 left-0 right-0 overflow-auto">
            <div class="grid min-w-full grid-cols-1 p-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <FriendCard @update-list="(id, type) => updateList(id, type)" :from="props.from"
                    v-for="user in props.list" :key="user.id" :user="user" />
            </div>
        </div>
    </div>
</template>