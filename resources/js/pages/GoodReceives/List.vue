<template>
  <b-container fluid>
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu nhập kho</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu nhập kho - <span class="fw-semi-bold">Danh sách</span>
    </h2>
    <b-row>
      <b-col>
        <v-server-table
          :columns="columns"
          url="/api/good-receive"
          :options="options"
        >
          <template v-slot:action="props">
            <b-button-group>
              <router-link
                :to="`/good-receive/${props.row.id}`"
                class="btn btn-info"
                >Xem</router-link
              >
              <router-link
                :to="`/good-receive/${props.row.id}/edit`"
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
      columns: [
        "supplier_name",
        "number",
        "product_code",
        "product_name",
        "quantity",
        "date",
        "store_name",
        "action"
      ],
      options: {
        headings: {
          supplier_name: "Đơn vị",
          number: "Số phiếu",
          product_name: "Tên sản phẩm",
          product_code: "Mã sản phẩm",
          quantity: "Số lượng",
          date: "Ngày lập",
          store_name: "Kho"
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
