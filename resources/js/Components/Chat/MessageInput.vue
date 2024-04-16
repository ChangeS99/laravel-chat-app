<script setup>
import { ref } from "vue";
import { chat_types } from "./chat_types";
const rootUrl = "http://127.0.0.1:8000";
const message = ref("");

const props = defineProps({
    group: Object,
    from: {
        type: String
    },
    friend: {
        type: Object,
        nullable: true
    }
})


const messageRequest = async (text) => {
    try {
        if (props.from === chat_types.group) {
            const result = await axios.post(`${rootUrl}/message/store`, {
                text,
                group_id: props.group.id
            });

        } else {
            const result = await axios.post(`${rootUrl}/chat/store`, {
                text,
                friend_id: props.friend.id
            });

        }

    } catch (err) {
        // console.log(err.message);
    }
};

const sendMessage = () => {
    if (message.value.trim() === "") {
        alert("Please enter a message!");
        return;
    }

    messageRequest(message.value);
    message.value = "";
};
</script>

<template>
    <div className="flex min-w-full py-1">
        <div class="flex-1 px-2">
            <input v-model="message" autoComplete="off" type="text" class="w-full border-gray-400 rounded-lg"
                placeholder="Message..." />
        </div>
        <div class="px-2">
            <button @click.prevent="sendMessage"
                class="w-full h-full px-10 text-white bg-indigo-700 rounded-lg hover:bg-indigo-600" type="button">
                Send
            </button>
        </div>
    </div>
</template>
