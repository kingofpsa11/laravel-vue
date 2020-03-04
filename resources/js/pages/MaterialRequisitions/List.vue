<template>
  <b-container fluid>
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Đơn hàng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Đơn hàng - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-row>
      <b-col>
        <v-server-table
          :columns="columns"
          url="/api/material-requisitions"
          :options="options"
        >
          <template v-slot:action="props">
            <b-button-group>
              <router-link
                :to="`/material-requisitions/${props.row.id}`"
                class="btn btn-info"
                >Xem</router-link
              >
              <router-link
                :to="`/material-requisitions/${props.row.id}/edit`"
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
import status from "../../components/Status/Status.vue";

export default {
  data() {
    return {
      columns: [
        "number",
        "date",
        "product_name",
        "quantity",
        "status",
        "action"
      ],
      options: {
        headings: {
          number: "Số phiếu",
          product_name: "Tên sản phẩm",
          quantity: "Số lượng",
          date: "Ngày lập",
          status: "Trạng thái"
        },
        filterByColumn: true,
        templates: {
          status
        }
      },
      perPage: 25
    };
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
