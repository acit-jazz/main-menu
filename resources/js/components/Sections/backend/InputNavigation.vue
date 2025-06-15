<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from "@inertiajs/vue3";
import axios from 'axios'

// Mendeklarasikan props
const props = defineProps({
  modelValue: String,
  menu: Object,
  menus: Array,
})

// Mendeklarasikan emits
const emit = defineEmits(['update:modelValue'])

// Referensi dan variabel reaktif
const tree = ref(null)
const rows = ref(props.menus)
const buttons = ref(null)
const pages = ref([])
const types = [
  { title: 'Self', value: '_self' },
  { title: 'New Tab', value: '_blank' },
  { title: 'Click', value: 'click' },
 // { title: 'Title', value: 'title' },
]

const form = ref(useForm({menus : props.menus}));

const submit = () => {
  form.value.post(route('menu.store'), {
    preserveScroll: false,
    onFinish: () => {},
    onSuccess: (res) => {
        rows.value = res.props.menus;
        form.value.menus = res.props.menus;
    },
  });
};

// Inisialisasi data saat komponen dimuat
onMounted(() => {
  rows.value = form.value.menus
    ? form.value.menus
    : [{
        id:null,
        title: null,
        url: null,
        model: null,
        style: null,
        is_active: true,
        type: '_self'
      }]
  getPage()
})

// Fungsi untuk menambahkan baris baru
function addRow() {
  tree.value.add({
    id: null,
    title: null,
    url: null,
    model: null,
    style: null,
    is_active: true,
    type: '_self'
  })
}

// Fungsi untuk menghapus baris
function removeRow(stat,node) {
  if (rows.value.length > 0) {
    form.value.post(route('menu.delete',{menu: node}), {
      preserveScroll: false,
      onFinish: () => {},
      onSuccess: (res) => {
        tree.value.remove(stat)
      },
    });
  }
}

// Fungsi untuk menyimpan perubahan
function save() {
  form.value.menus = tree.value.getData().filter(obj => obj.title != null)
  emit('update:modelValue', JSON.stringify(form.value.menus ))
  submit();
}

// Fungsi untuk mengambil data halaman
function getPage() {
  axios.get(route('menu.page')).then(res => {
    pages.value = res.data;
  }).catch(error => {
    console.error(error)
  })
}

</script>

<template>
  <div id="input-buttons">
    <Draggable v-model="form.menus" ref="tree" :maxLevel="4" :watermark="false">
      <template #default="{ node, stat }">
        <div class="flex w-full mb-2 max-h-[55px]">
          <a class="block text-center py-3 px-3 justify-center">
            <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
          </a>
          <div class="flex w-full mr-4">
            <input
              type="text"
              class="w-full m-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md mt-1 block"
              placeholder="Title"
              v-model="node.title"
              
            />
            <InputSelectMenu
              v-if="node.type === '_self'"
              classname="inputButtonSelect w-full m-2 !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md mt-1 block"
              placeholder="Page"
              v-model="node.model"
              :options="pages"
              label="title"
            />
            <input
              v-else
              type="text"
              class="w-full m-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md mt-1 block"
              placeholder="Url"
              v-model="node.url"
              
            />
            <input
              v-if="stat.level === 1"
              type="text"
              class="w-full m-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md mt-1 block"
              placeholder="Style"
              v-model="node.style"
              
            />
            <InputSelect
              @change="e => e === 'click' ? (node.url = '#') : null"
              classname="inputButtonSelect w-full m-2 !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md mt-1 block"
              placeholder="Type Button"
              v-model="node.type"
              :is_string="true"
              :options="types"
              label="title"
              store="value"
            />
            <div class="flex items-center">
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  type="checkbox"
                  
                  v-model="node.is_active"
                  class="sr-only peer"
                />
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
              </label>
            </div>
          </div>
          <a  @click="node.id ? removeRow(stat,node.id) : null" class="block text-center py-3 px-3 mb-auto cursor-pointer justify-center">
            <i class="fas text-gray-400 text-sm fa-trash" :class="{'text-red-400' : node.id}"></i>
          </a>
        </div>
      </template>
    </Draggable>
      <div class="flex gap-3">
        <SecondaryButton @click="addRow" type="button" class="justify-center !capitalize">
           <i class="fas text-white-400 text-sm fa-plus"></i> Add New
        </SecondaryButton>

        <SecondaryButton @click="save" type="button" class="justify-center !capitalize bg-lime-700 hover:bg-lime-800">
           <i class="fas text-white-400 text-sm fa-check"></i> Save
        </SecondaryButton>
    </div>
  </div>
</template>

<style scoped>
#input-buttons {
  .vtlist.he-tree {
    overflow: unset !important;
  }
  .inputButtonSelect .vs__dropdown-toggle {
    height: 100%;
  }
}
</style>
