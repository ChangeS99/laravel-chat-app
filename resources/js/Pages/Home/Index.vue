<script setup>
import { ref } from "vue";
import ChatBox from "../../Components/Chat/ChatBox.vue";
// const messages = ref([]);
// const rootUrl = "http://127.0.0.1:8000";

// const webSocketChannel = `channel_for_everyone`;
// const connectWebSocket = () => {
//     window.Echo.private(webSocketChannel)
//         .listen('GotMessage', async (e) => {
//             // e.message
//             await getMessages();
//         });
// }

const props = defineProps({
    user: Object,

});

</script>

<template>
    <div class="flex flex-col flex-1 min-h-full">
        <h1>HI There</h1>
        <!-- <ChatBox :user="props.user" /> -->
    </div>
</template>
