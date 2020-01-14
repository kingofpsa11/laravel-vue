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
          <router-link
            :to="{
              name: 'ContractCreate',
              params: {
                id: props.row.id
              }
            }"
            slot="action"
            slot-scope="props"
          >
            <b-button variant="success" class="fa fa-edit"></b-button>
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
        "customer_id",
        "number",
        "price_id",
        "quantity",
        "selling_price",
        "date",
        "deadline",
        "order",
        "status",
        "action"
      ],
      options: {
        headings: {
          customer_id: "ĐVDH",
          number: "Số đơn hàng",
          price_id: "Tên sản phẩm",
          quantity: "Số lượng",
          selling_price: "Đơn giá",
          date: "Ngày lập",
          deadline: "Tiến độ",
          order: "LSX",
          status: "Trạng thái"
        },
        filterByColumn: true,
        templates: {
          date(h, row) {
            return moment(row.date).format("DD-MM-YYYY");
          }
        }
      },
      perPage: 25
    };
  },
  methods: {
    parseDate(date) {
      const dateSet = date.toDateString().split(" ");
      return `${date.toLocaleString("en-us", { month: "long" })} ${
        dateSet[2]
      }, ${dateSet[3]}`;
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
