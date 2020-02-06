<template>
  <b-container fluid>
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Định mức</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Định mức - <span class="fw-semi-bold">Danh sách</span>
    </h2>
    <b-row>
      <b-col>
        <v-server-table :columns="columns" url="/api/boms" :options="options">
          <template v-slot:action="props">
            <b-button-group>
              <router-link :to="`/boms/${props.row.id}`" class="btn btn-info"
                >Xem</router-link
              >
              <router-link
                :to="`/boms/${props.row.id}/edit`"
                class="btn btn-warning"
                >Sửa</router-link
              >
            </b-button-group>
          </template>
        </v-server-table>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Vue from "vue";

export default {
  data() {
    return {
      columns: ["product_code", "product_name", "status", "action"],
      options: {
        headings: {
          product_code: "Mã sản phẩm",
          product_name: "Tên sản phẩm",
          status: "Trạng thái"
        },
        filterByColumn: true,
        templates: {
          // date(h, row) {
          //   return moment(row.date).format("DD-MM-YYYY");
          // }
        }
      },
      perPage: 25
    };
  },
  methods: {}
};
</script>

<style src="./Create.scss" lang="scss" scoped />
