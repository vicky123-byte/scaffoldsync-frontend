<script setup>
defineProps({ compact: { type: Boolean, default: false } })
</script>
<template>
  <div class="brand-lockup" :class="{ compact }">
    <img src="/assets/logo-scaffoldsync.png" alt="ScaffoldSync — Construction Connectivity" />
  </div>
</template>
