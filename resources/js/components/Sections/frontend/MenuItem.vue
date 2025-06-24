<template>
  <li class="relative group lg:flex items-center">
    <!-- Main Item -->
    <component
      :is="getComponentType(item)"
      v-bind="getComponentProps(item)"
      class="menu-item  text-white font-bold"
    >
      {{ item.title }} 
    </component>

    <!-- Desktop Dropdown -->
    <ul
      v-if="item.children.data?.length && !mobile"
      class="absolute left-0 top-full w-48 bg-white shadow-lg rounded-b-xl border border-gray-100 opacity-0 group-hover:opacity-100 invisible group-hover:visible pointer-events-none group-hover:pointer-events-auto transition-all duration-200 z-50"
    >
      <li v-for="child in item.children.data" :key="child.id">
        <component
          :is="getComponentType(child)"
          v-bind="getComponentProps(child)"
          class="sub block px-4 py-3 w-full text-left text-black "
        >
          {{ child.title }}
        </component>
      </li>
    </ul>

    <!-- Mobile Dropdown -->
    <ul
      v-if="item.children?.length && mobile"
      class="absolute left-0 top-full w-48 bg-white shadow-lg rounded-b border border-gray-100 opacity-0 group-hover:opacity-100 invisible group-hover:visible pointer-events-none group-hover:pointer-events-auto transition-all duration-200 z-50"
    >
      <li v-for="child in item.children" :key="child.id">
        <component
          :is="getComponentType(child)"
          v-bind="getComponentProps(child)"
          class="sub-block px-4 py-2 w-full text-left"
        >
          {{ child.title }}
        </component>
      </li>
    </ul>
  </li>
</template>

<script setup>
import { Link,usePage } from '@inertiajs/vue3'
import { ref, useSlots,onMounted,watch } from 'vue'

const props = defineProps({
  item: Object,
  mobile: {
    type: Boolean,
    default: false,
  }
})

function resolveUrl(item) {
  if (item.url && item.url !== '#') return item.url
  if (item.model && typeof item.model === 'object' && item.model.slug) {
    return `/${item.model.slug}`
  }

  try {
    const parsedModel = JSON.parse(item.model)
    if (parsedModel.slug) return `/${parsedModel.slug}`
  } catch (e) {}

  return '#'
}

function getComponentType(item) {
  if (item.type === '_self') return Link
  if (item.type === '_blank') return 'a'
  if (item.type === 'click') return 'button'
  return 'a'
}

function getComponentProps(item) {
  const url = resolveUrl(item)

  if (item.type === '_self') {
    return { href: url }
  }

  if (item.type === '_blank') {
    return { href: url, target: '_blank' }
  }

  if (item.type === 'click') {
    return {
      type: 'button',
      onClick: () => {
        console.log(`Clicked: ${item.title}`)
        // Emit event, call method, or emit to parent, etc
      }
    }
  }

  return { href: '#' }
}
</script>
