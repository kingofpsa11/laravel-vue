<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Định mức</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Định mức - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="onSubmit">
      <b-row>
        <b-col md="9">
          <b-form-group label="Tên sản phẩm" name="product">
            <v-select
              :options="productList"
              :selectOnTab="true"
              :value="bom.product_name"
              @input="product => updateProduct(product)"
              @search="onSearchProduct"
              :filterable="false"
            >
              <template v-slot:no-options>
                Nhập tên sản phẩm
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col md="3">
          <b-form-group label="Tên định mức">
            <b-form-input
              v-model="bom.name"
              type="text"
              required
              placeholder="Nhập tên định mức"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <table class="table table-bordered">
            <b-thead>
              <b-tr>
                <th>STT</th>
                <th>Mã SP</th>
                <th>Tên vật tư</th>
                <th>Số lượng</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr v-for="(row, index) in bom.bom_details" :key="row.id">
                <td>{{ index + 1 }}</td>
                <td>{{ row.product_code }}</td>
                <td>
                  <v-select
                    :options="productList"
                    :selectOnTab="true"
                    @search="onSearchProduct"
                    :filterable="false"
                    @input="product => updateProductDetail(row, product)"
                    name="product_id"
                    :value="row.product_name"
                  >
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!row.product_id"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>
                    <template v-slot:no-options>
                      Chọn vật tư
                    </template>
                  </v-select>
                </td>
                <td>
                  <b-form-input
                    v-model="row.quantity"
                    type="text"
                    required
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input v-model="row.note" type="text"></b-form-input>
                </td>
                <td v-if="count !== 1">
                  <b-button variant="success" @click="deleteRow(index)">
                    <i class="fa fa-minus"></i>
                  </b-button>
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <b-button variant="primary" @click.prevent="addRow"
            >Thêm dòng</b-button
          >
          <b-button type="submit" variant="success">Lưu</b-button>
          <b-button type="reset" variant="danger">Huỷ</b-button>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
import Vue from "vue";
import _ from "lodash";

export default {
  data() {
    return {
      bom: {
        product_name: "",
        product_id: "",
        name: "",
        bom_details: [
          {
            product_id: "",
            product_code: "",
            product_name: "",
            quantity: null,
            note: ""
          }
        ]
      },
      newItem: {
        product_code: "",
        product_name: "",
        quantity: null,
        note: ""
      },
      productList: []
    };
  },
  created() {
    if (this.$route.params.id) {
      this.getBom(this.$route.params.id);
    }
  },
  computed: {
    count() {
      return this.bom.bom_details.length;
    }
  },
  methods: {
    getBom(id) {
      axios.get(`api/boms/${id}`).then(res => {
        this.bom = res.data.data;
      });
    },
    addRow() {
      this.bom.bom_details.push({ ...this.newItem });
    },
    onSubmit() {
      let currentRoute = this.$route.path;
      if (currentRoute.includes("edit")) {
        axios
          .put(`/api/boms/${this.id}`, this.bom)
          .then(res => {
            if (res.data.status === "success")
              this.$router.push("/boms/" + res.data.id);
          })
          .catch(error => {});
      } else {
        axios
          .post("/api/boms", this.bom)
          .then(res => {
            if (res.data.status === "success")
              this.$router.push("/boms/" + res.data.id);
          })
          .catch(error => {});
      }
    },
    onSearchProduct(search, loading) {
      loading(true);
      this.searchproduct(loading, search, this);
    },
    searchproduct: _.debounce((loading, search, vm) => {
      axios.get(`api/products/search?q=${encodeURI(search)}`).then(res => {
        vm.productList = _.map(res.data, value => {
          return {
            label: value.name,
            id: value.id,
            code: value.code
          };
        });
        loading(false);
      });
    }, 350),
    updateProduct(product) {
      this.bom.product_name = product.label;
      this.bom.product_id = product.id;
    },
    updateProductDetail(row, product) {
      row.product_id = product.id;
      row.product_name = product.label;
      row.product_code = product.code;
    },
    deleteRow(index) {
      this.bom.bom_details.splice(index, 1);
    }
  }
};
</script>
<style lang="scss">
[name="product_id"] {
  .vs__dropdown-menu {
    width: 500px;
  }
}
</style>
<style src="./Create.scss" lang="scss" scoped />
