<script setup>
import { onBeforeMount } from 'vue';
import { useLayout } from '@/shared/store/useLayout';
import { storeToRefs } from 'pinia';

const { isDarkTheme } = storeToRefs(useLayout());

onBeforeMount(() => {
    if (isDarkTheme.value) {
        document.documentElement.classList.add('app-dark');
    }
});
</script>

<template>
    <router-view />
</template>

<style scoped></style>
