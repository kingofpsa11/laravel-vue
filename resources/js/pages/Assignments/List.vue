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
          url="/api/assignments"
          :options="options"
        >
          <template v-slot:action="props">
            <b-button-group>
              <router-link
                :to="`/assignments/${props.row.id}`"
                class="btn btn-info"
                >Xem</router-link
              >
              <router-link
                :to="`/assignments/${props.row.id}/edit`"
                class="btn btn-warning"
                >Sửa</router-link
              >
            </b-button-group>
          </template>
          </router-link>
        </v-server-table>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Vue from "vue";

export default {
  name: "Tables",
  data() {
    return {
      columns: [
        "factory_name",
        "number",
        "date",
        'manufacturer_order_number',
        'product_code',
        'product_name',
        "quantity",
        "deadline",
        "status",
        "action"
      ],
      options: {
        headings: {
          factory_name: "Đơn vị",
          number: "Số phiếu",
          manufacturer_order_number: "LSX",
          product_code: 'Mã SP',
          product_name: "Tên sản phẩm",
          quantity: "Số lượng",
          date: "Ngày lập",
          deadline: "Tiến độ",
          status: "Trạng thái"
        },
        filterByColumn: true,
      },
      perPage: 10
    };
  },
};
</script>

<style src="./Create.scss" lang="scss" scoped />
