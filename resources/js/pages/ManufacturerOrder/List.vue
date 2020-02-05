<template>
  <b-container fluid>
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Lệnh sản xuất</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Lệnh sản xuất - <span class="fw-semi-bold">Danh sách</span>
    </h2>
    <b-row>
      <b-col>
        <v-server-table
          :columns="columns"
          url="/api/manufacturer-orders"
          :options="options"
        >
          <template v-slot:action="props">
            <router-link
              :to="`/manufacturer-orders/${props.row.id}`"
              class="btn btn-info"
              >Xem</router-link
            >
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
      columns: [
        "date",
        "manufacturer_order_number",
        "contract_number",
        "product_name",
        "quantity",
        "deadline",
        "status",
        "action"
      ],
      options: {
        headings: {
          date: "Ngày lập",
          manufacturer_order_number: "Số LSX",
          contract_number: "Số đơn hàng",
          product_name: "Tên sản phẩm",
          quantity: "Số lượng",
          deadline: "Tiến độ",
          status: "Trạng thái"
        },
        filterByColumn: true,
        templates: {}
      },
      perPage: 25
    };
  },
  methods: {}
};
</script>

<style src="./Create.scss" lang="scss" scoped />
