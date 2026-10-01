<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    endTime: {
        type: String,
        required: true,
    },
});

const hours = ref('00');
const minutes = ref('00');
const seconds = ref('00');
let timer = null;

const calculateTime = () => {
    const diff = new Date(props.endTime) - new Date();
    if (diff <= 0) {
        hours.value = '00';
        minutes.value = '00';
        seconds.value = '00';
        return;
    }

    const h = Math.floor((diff / (1000 * 60 * 60)));
    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const s = Math.floor((diff % (1000 * 60)) / 1000);

    hours.value = String(h).padStart(2, '0');
    minutes.value = String(m).padStart(2, '0');
    seconds.value = String(s).padStart(2, '0');
};

onMounted(() => {
    calculateTime();
    timer = setInterval(calculateTime, 1000);
});

onUnmounted(() => {
    clearInterval(timer);
});
</script>

<template>
    <div class="flex items-center space-x-1 font-mono text-sm font-bold">
        <span class="px-2 py-1 bg-white text-gray-900 rounded shadow-sm">{{ hours }}</span>
        <span class="text-white">:</span>
        <span class="px-2 py-1 bg-white text-gray-900 rounded shadow-sm">{{ minutes }}</span>
        <span class="text-white">:</span>
        <span class="px-2 py-1 bg-white text-gray-900 rounded shadow-sm">{{ seconds }}</span>
    </div>
</template>
