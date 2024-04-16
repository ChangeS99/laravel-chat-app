<script setup>
import { Link } from "@inertiajs/vue3"
import axios from "axios";
const rootUrl = "http://127.0.0.1:8000";

import { availableLists } from "./list_types.js";

const props = defineProps({
    user: Object,
    from: {
        type: String,
        nullable: true
    }
});



const emits = defineEmits(
    ['update-list']
)


const cancelFriendRequestHandler = async () => {
    try {
        const result = await axios.post(`${rootUrl}/friends/cancel`, {
            request_id: props.user.id
        });
        emits('update-list', props.user.id, 'cancel')

    } catch (error) {
        // console.log(error)
    }
}

const acceptFriendRequestHandler = async () => {
    try {
        const result = await axios.post(`${rootUrl}/friends/accept`, {
            request_id: props.user.id
        });

        emits('update-list', props.user.id, 'accept')


    } catch (error) {
        // console.log(error)
    }
}


</script>

<template>
    <Link v-if="from === availableLists.friends" :href="route('chat.index', props.user.id)"
        class="flex p-4 bg-gray-200 rounded-md shadow-lg">
    <div class="flex-1">
        {{ props.user.name }}
    </div>
    <div>
        <v-icon name="md-message" />
    </div>
    </Link>

    <div class="flex items-center p-4 bg-gray-200 rounded-md shadow-lg" v-if="from === availableLists.pending">
        <h2 class="flex-1">
            {{ props.user.name }}
        </h2>
        <div>
            <button @click.prevent="cancelFriendRequestHandler"
                class="p-2 text-gray-100 bg-gray-500 rounded-lg hover:bg-gray-400">
                Cancel
            </button>

        </div>
    </div>

    <div class="flex items-center p-4 bg-gray-200 rounded-md shadow-lg" v-if="from === availableLists.requests">
        <h2 class="flex-1">
            {{ props.user.name }}
        </h2>
        <button @click.prevent="acceptFriendRequestHandler"
            class="p-2 text-gray-100 bg-gray-500 rounded-lg hover:bg-gray-400">
            Accept
        </button>
    </div>
</template>