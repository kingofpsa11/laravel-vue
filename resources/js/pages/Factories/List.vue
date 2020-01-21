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
              <router-link :to="`/factories/${row.id}`" class="btn btn-success"
                >Xem</router-link
              >
              <b-button variant="warning">Sửa</b-button>
            </b-button-group>
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
    this.getFactoryList();
  },
  data() {
    return {
      currentPage: 1,
      totalRows: 1,
      perPage: 10,
      pageOptions: [10, 20],
      fields: [
        { key: "name", label: "tên phân xưởng" },
        { key: "action", label: "xem" }
      ],
      items: [],
      errors: [],
      isBusy: false
    };
  },
  methods: {
    getFactoryList() {
      axios
        .get("/api/factories")
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
