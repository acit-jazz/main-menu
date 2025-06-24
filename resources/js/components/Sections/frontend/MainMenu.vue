

<template>
  <nav class="fixed w-full left-0 top-0 bg-[#0064d2] z-100 shadow-md" >
    <div class="nav-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex  h-18 items-center">
        <div class="logo-wrapper flex aspect-square items-center justify-center rounded-md">
          <AppLogoIcon v-if="!hasSlotContent" />
          <slot />
        </div>
        <!-- Hamburger Button -->
        <button class="hamburger-btn" @click="toggleMobileMenu">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              :d="isMobileMenuOpen
                ? 'M6 18L18 6M6 6l12 12'
                : 'M4 6h16M4 12h16M4 18h16'" />
          </svg>
        </button>
        <!-- Desktop Menu -->
        <ul class="desktop-menu hidden md:flex space-x-6 h-full ml-auto">
          <MenuItem v-for="item in menus" :key="item.id" :item="item" />
        </ul>
      </div>
      <!-- Mobile Menu -->
      <ul v-show="isMobileMenuOpen" class="mobile-menu">
        <MenuItem
          v-for="item in menus"
          :key="item.id"
          :item="item"
          mobile
        />
      </ul>
    </div>
  </nav>
</template>

<script lang="ts" setup>
import { ref, useSlots } from 'vue'
import type { PropType } from 'vue'
import MenuItem from './MenuItem.vue'
import AppLogoIcon from '@/components/AppLogoIcon.vue';


interface MenuItemType {
  id: number | string
  [key: string]: any
}

const props = defineProps({
  menus: {
    type: Array as PropType<MenuItemType[]>,
    required: true,
  },
})

const $slots = useSlots()
const hasSlotContent = !!$slots.default

const isMobileMenuOpen = ref(false)
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}



</script>
