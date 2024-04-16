<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { Toaster } from "@steveyuowo/vue-hot-toast";
import "@steveyuowo/vue-hot-toast/vue-hot-toast.css";
import GroupSidebar from "../Components/Chat/GroupSidebar.vue";
const rootUrl = "http://127.0.0.1:8000";

const page = usePage();
const user = computed(() => page.props.user);

const showAddDialog = ref(false);

const usersSearchList = ref([]);

const props = defineProps({
    groups: Array,
    friend_requests: Array,
});

const addDialogHandler = () => {
    showAddDialog.value = !showAddDialog.value;
};

const search = ref("");

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
                if (d.id === friend_id) {
                    return { ...d, already_friends: true }
                } else {
                    return d
                }
            })


            usersSearchList.value = [...newFriendList];
        }
    } catch (err) {
        // console.log(err);
    }
};
</script>

<template>
    <Toaster />
    <main class="container flex flex-col flex-1 min-h-screen p-10 mx-auto">

        <nav v-if="user" class="flex gap-4">
            <section class="flex flex-1 gap-4">
                <div class="flex flex-1 gap-4">
                    <div>
                        <Link
                            class="px-4 py-2 text-gray-800 bg-gray-100 border-2 border-gray-400 rounded-lg hover:bg-gray-200"
                            :href="route('home')">
                        {{ user.name }}
                        </Link>

                    </div>


                    <div class="relative">
                        <Link
                            class="px-4 py-2 text-gray-800 bg-gray-100 border-2 border-gray-400 rounded-lg hover:bg-gray-200 "
                            :href="route('friends.index')">Friends</Link>
                    </div>
                </div>
                <div>
                    <div>
                        <Link class="px-4 py-2 text-white bg-gray-700 rounded-lg" :href="route('logout')" as="button">
                        Logout
                        </Link>
                    </div>
                </div>
            </section>
            <!-- add friend dialog section -->
            <!-- <section class="relative">
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
                            class="px-2 py-3 bg-gray-300 rounded-full shadow-lg hover:bg-slate-300">
                            <h3>{{ searchedUser.name }}</h3>

                            <button v-if="
                                !searchedUser.already_friends &&
                                user.id !== searchedUser.id
                            " @click.prevent="() => addFriendHandler(searchedUser.id)
                                ">
                                add
                            </button>
                        </div>
                    </div>
                </div>

            </section> -->

            <!-- end add friend dialog section -->
        </nav>
        <nav v-else class="flex gap-4">
            <div>
                <Link :href="route('signin')">Sign in</Link>
            </div>
        </nav>
        <section class="flex flex-1 min-w-full p-2 bg-white border-yellow-400 rounded-lg shadow-2xl 4">
            <!-- right group section -->
            <section class="min-w-[5rem]">
                <GroupSidebar :groups="props.groups" />
            </section>

            <!-- left chat box section -->
            <section class="flex flex-1 min-h-full pl-4 ">
                <slot />
            </section>
            <!-- slot will be replaced by the pages in its place -->
        </section>
    </main>
</template>

<style scoped>
.success {
    background-color: green;
    color: white;
}
</style>
