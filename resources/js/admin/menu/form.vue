<script setup>
import { useForm } from "@inertiajs/vue3";
import MainMenu from '@/components/Sections/MainMenu.vue'
import { onMounted, ref } from "vue";

let props = defineProps({
  menus: Array,
  menu: Object,
  title:String,
  type: [String, Boolean],
  location: String,
});

const tab = ref("content");
const changeTab = (newtab) => {
  tab.value = newtab;
};
const showPreview = ref(false);
</script>

<template>
    <Head :title="title" />
    <AppLayout>
        <div>
            <div class="w-full">
                <div class="relative flex flex-col min-w-0 break-words w-full mb-6 px-5 rounded" >
                    <div class="block w-full">
                        <div class="rounded-t mb-5">
                            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-blueGray-300">
                                <ul class="flex flex-wrap -mb-px">
                                    <li class="mr-2">
                                        <a @click="changeTab('content')" class="inline-block cursor-pointer font-bold p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-800 hover:border-gray-600 dark:hover:text-gray-600"
                                        :class="{'!border-blue-600  text-blue-600 active dark:text-blue-500 dark:border-blue-500' : tab == 'content'}"
                                        aria-current="product">{{location}} Menu</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="block w-full lg:px-4" :class="{hidden : tab != 'content'}">
                            <div class="block mt-4 mb-10">
                             <InputNavigation :menus="menus" :menu="menu"></InputNavigation>
                            </div>
                            <div v-if="showPreview" class="fixed left-0 top-0 w-full flex items-center justify-center h-full bg-white z-100">
                                 <MainMenu :menus="menus" class="fixed top-0 left-0 w-full"></MainMenu>
                                 <PrimaryButton @click="showPreview = false">Exit Preview</PrimaryButton>
                            </div>
                            <PrimaryButton @click="showPreview = true">Preview</PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
    </div>
  </AppLayout>
</template>
