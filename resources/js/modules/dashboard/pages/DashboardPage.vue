<script setup>
import { onMounted } from 'vue';
import { useUserStore } from '../../../shared/store/useUserStore';

const UserStore = useUserStore();

onMounted(() => {
    UserStore.getSession();
});
</script>
<template>
    <div className="card">
        <div class="font-semibold text-xl mb-4">Dashboard Page</div>
        <p>Use this page to start from scratch and place your custom content.</p>
    </div>
</template>
