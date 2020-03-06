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
          url="/api/contracts"
          :options="options"
        >
          <template v-slot:action="props">
            <b-button-group>
              <router-link
                :to="`/contracts/${props.row.id}`"
                class="btn btn-info"
                >Xem</router-link
              >
              <router-link
                :to="`/contracts/${props.row.id}/edit`"
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
        "customer_name",
        "number",
        "price_id",
        "quantity",
        "selling_price",
        "date",
        "deadline",
        "manufacturer_order_number",
        "status",
        "action"
      ],
      options: {
        headings: {
          customer_name: "ĐVDH",
          number: "Số đơn hàng",
          price_id: "Tên sản phẩm",
          quantity: "Số lượng",
          selling_price: "Đơn giá",
          date: "Ngày lập",
          deadline: "Tiến độ",
          manufacturer_order_number: "LSX",
          status: "Trạng thái"
        },
        filterByColumn: true,
        perPage: 10,
        perPageValues: [10, 25, 100],
        templates: { status }
      }
    };
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
