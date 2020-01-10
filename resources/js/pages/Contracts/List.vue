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
      <b-col sm="5" md="6" class="my-1">
        <b-form-group
          label="Per page"
          label-cols-sm="4"
          label-cols-md="3"
          label-cols-lg="2"
          label-align-sm="right"
          label-size="sm"
          label-for="perPageSelect"
          class="mb-0"
        >
          <b-form-select
            v-model="perPage"
            id="perPageSelect"
            size="md"
            :options="pageOptions"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col sm="7" md="6" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-table
          :fields="fields"
          :current-page="currentPage"
          :per-page="perPage"
          head-variant="dark"
          bordered
          :items="items"
        >
          <template v-slot:cell(action)="row">
            <b-button-group>
              <b-button variant="info" @click="row.toggleDetails">Xem</b-button>
              <b-button variant="warning">Sửa</b-button>
            </b-button-group>
          </template>

          <template v-slot:row-details="row">
            <b-card>
              <ul>
                <li v-for="(value, key) in row.item" :key="key">
                  {{ key }}: {{ value }}
                </li>
              </ul>
            </b-card>
          </template>
        </b-table>
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
      fields: [
        { key: "category", label: "nhóm" },
        { key: "code", label: "mã sản phẩm" },
        { key: "name", label: "tên sản phẩm" },
        { key: "status", label: "trạng thái" },
        { key: "action", label: "xem" }
      ],
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
        .get("/api/products")
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
