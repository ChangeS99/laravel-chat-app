<script setup>
import { ref } from 'vue';

import PeopleList from "../../../Components/People/PeopleList.vue";
const rootUrl = "http://127.0.0.1:8000";
const availableLists = {
    friends: "FRIENDS",
    pending: "PENDING",
    requests: "REQUESTS"
}
const listToShow = ref(availableLists.friends);

const props = defineProps({
    friends: Object,
    pending_requests: Object,
    requests_list: Object,
    user: Object
})



// refs related to search and adding people
const showAddDialog = ref(false);
const search = ref('');
const usersSearchList = ref([]);

const addDialogHandler = () => {
    showAddDialog.value = !showAddDialog.value;
}




// set up client refs 
const friends_client = ref(props.friends);
const pending_requests_client = ref(props.pending_requests);
const requests_list_client = ref(props.requests_list);



const updateList = (id, type) => {
    if (type === availableLists.friends) {
        const filtered_list = friends_client.value.filter(user => user.id !== id);
        friends_client.value = [...filtered_list];
    }

    if (type === 'cancel') {
        const filtered_list = pending_requests_client.value.filter(user => user.id !== id);
        pending_requests_client.value = [...filtered_list];
    }

    if (type === 'accept') {
        const user = requests_list_client.value.find(user => user.id === id);
        const filtered_list = requests_list_client.value.filter(user => user.id !== id);

        friends_client.value = [...friends_client.value, user];
        requests_list_client.value = [...filtered_list];
    }
}

const handleShowList = (value) => {
    listToShow.value = value

}
const searchClickHandler = async () => {
    try {
        const result = await axios.get(`${rootUrl}/user/${search.value}`);
        usersSearchList.value = result.data.users;

        search.value = "";
    } catch (err) {
        search.value = "";
        // console.log(err.message);
    }
};

const addFriendHandler = async (id) => {
    try {
        const result = await axios.post(`${rootUrl}/user/friend/add`, {
            friend_id: id,
        });

        if (result.data.success) {
            const newFriendList = usersSearchList.value.map((d) => {
                if (d.id === id) {
                    return { ...d, already_friends: true }
                } else {
                    return d
                }
            })
            const newPendingList = [...pending_requests_client.value, { ...result.data.friend }];


            pending_requests_client.value = [...newPendingList];

            usersSearchList.value = [...newFriendList];
        }
    } catch (err) {
        // console.log(err);
    }
};



</script>

<template>
    <div class="flex flex-col flex-1 min-h-full border-2 border-blue-200">
        <div class="min-h-[3rem] flex ">
            <div class="flex justify-center flex-1 p-2">
                <button @click.prevent="() => handleShowList(availableLists.friends)"
                    class="min-w-full min-h-full py-3 text-gray-100 bg-gray-700 rounded-lg "
                    :class="listToShow === availableLists.friends ? 'bg-gray-900' : ''">
                    Friends
                </button>
            </div>
            <div class="flex justify-center flex-1 p-2 ">
                <button @click.prevent="() => handleShowList(availableLists.pending)"
                    :class="listToShow === availableLists.pending ? 'bg-gray-900' : ''"
                    class="min-w-full py-3 text-gray-100 bg-gray-700 rounded-lg">
                    Pending
                </button>
            </div>
            <div class="flex justify-center flex-1 p-2 ">
                <button @click.prevent="() => handleShowList(availableLists.requests)"
                    :class="listToShow === availableLists.requests ? 'bg-gray-900' : ''"
                    class="min-w-full py-3 text-gray-100 bg-gray-700 rounded-lg">
                    Requests
                </button>
            </div>
            <div class="flex items-center">
                <!-- add friend dialog section -->
                <section class="relative">
                    <div>
                        <button class="p-2 rounded-full hover:bg-gray-200" @click.prevent="addDialogHandler">
                            <v-icon name="io-person-add-sharp" class="w-8 h-8" />
                        </button>
                    </div>

                    <div v-if="showAddDialog"
                        class="min-w-[20rem] min-h-[20rem] absolute right-0 z-20 bg-slate-500 top-[105%] rounded-lg">
                        <div class="flex p-2">
                            <div class="flex-1 pl-2 pr-2">
                                <input v-model="search" class="min-w-full p-2 rounded-lg" />
                            </div>
                            <button @click.prevent="searchClickHandler" class="px-2 rounded-full hover:bg-gray-400">
                                <v-icon name="io-search-sharp" class="min-h-[2rem] min-w-[2rem]" />
                            </button>
                        </div>
                        <div class="px-4 pt-2">
                            <div v-for="searchedUser in usersSearchList"
                                class="flex px-2 py-3 bg-gray-300 rounded-full shadow-lg hover:bg-slate-300">
                                <div class="flex items-center flex-1">
                                    <h3>{{ searchedUser.name }}</h3>
                                </div>
                                <div class="pr-2">
                                    <button
                                        class="px-3 py-2 text-gray-200 bg-gray-700 rounded-lg shadow-lg hover:bg-gray-900"
                                        v-if="
                                            !searchedUser.already_friends &&
                                            user.id !== searchedUser.id
                                        " @click.prevent="() => addFriendHandler(searchedUser.id)
                                            ">
                                        add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

                <!-- end add friend dialog section -->
            </div>
        </div>
        <!-- List sections -->

        <section class="relative flex flex-1" v-if="listToShow === availableLists.friends">
            <PeopleList :from="availableLists.friends" :list="friends_client"
                @update-list="(id, type) => updateList(id, type)" />
        </section>

        <section class="relative flex flex-1" v-if="listToShow === availableLists.pending">
            <PeopleList :from="availableLists.pending" :list="pending_requests_client"
                @update-list="(id, type) => updateList(id, type)" />
        </section>

        <section class="relative flex flex-1" v-if="listToShow === availableLists.requests">
            <PeopleList :from="availableLists.requests" :list="requests_list_client"
                @update-list="(id, type) => updateList(id, type)" />
        </section>
        <!-- List section -->

    </div>
</template>