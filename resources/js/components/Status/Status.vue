<template>
  <b-button variant="warning" v-if="data.status === 10">Đang chờ</b-button>
  <b-button variant="success" v-else-if="data.status === 0"
    >Hoàn thành</b-button
  >
</template>

<script>
export default {
  props: ["data", "index"]
};
</script>
