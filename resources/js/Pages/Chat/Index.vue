<script setup>
import ChatBox from "../../Components/Chat/ChatBox.vue";
import { chat_types } from "../../Components/Chat/chat_types";
const props = defineProps({
    user: Object,
    groups: Object,
    friend: Object,
    conversation: Object
})



</script>

<template>
    <div class="flex flex-col flex-1 min-h-full">
        <div class="p-4 text-xl font-medium">{{ props.friend.name }}</div>
        <ChatBox :user="props.user" :from="chat_types.chat" :friend="props.friend" :conversation="props.conversation" />
    </div>
</template>