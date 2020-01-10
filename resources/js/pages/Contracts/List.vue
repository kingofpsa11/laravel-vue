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
        <v-client-table :columns="columns" :data="items" :options="options" />
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Vue from "vue";

export default {
  name: "Tables",
  created() {
    this.getContractList();
  },
  data() {
    return {
      currentPage: 1,
      totalRows: 1,
      perPage: 10,
      pageOptions: [10, 20],
      // columns: [
      // { key: "customer", label: "ĐVĐH" },
      // { key: "number", label: "Số đơn hàng" },
      // { key: "price_id", label: "tên sản phẩm" },
      // { key: "quantity", label: "Số lượng" },
      // { key: "selling_price", label: "Đơn giá" },
      // { key: "date", label: "Ngày lập" },
      // { key: "deadline", label: "Tiến độ" },
      // { key: "order", label: "LXH" },
      // { key: "status", label: "Trạng thái" },
      // { key: "action", label: "xem" }
      // ],
      columns: [
        "customer",
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
          customer: "ĐVDH",
          number: "Số đơn hàng",
          price_id: "View Record"
        },
        editableColumns: ["name"],
        sortable: ["customer", "number"],
        filterable: ["name", "code"]
      },
      items: [],
      errors: [],
      isBusy: false
    };
  },
  methods: {
    parseDate(date) {
      const dateSet = date.toDateString().split(" ");
      return `${date.toLocaleString("en-us", { month: "long" })} ${
        dateSet[2]
      }, ${dateSet[3]}`;
    },
    getContractList() {
      axios
        .get("/api/contracts")
        .then(response => {
          this.items = response.data.data;
          this.totalRows = this.items.length;
          this.isBusy = true;
        })
        .catch(error => {
          this.errors = error.response.data.errors.name;
          console.log(this.errors);
        });
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
