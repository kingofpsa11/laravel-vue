<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu xin lĩnh vật tư</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu xin lĩnh vật tư - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="onSubmit">
      <b-row>
        <b-col>
          <b-form-group label="Đơn vị" name="department">
            <v-select
              :options="departmentList"
              :selectOnTab="true"
              :value="material_requistion.department_name"
              @input="department => updateDepartment(department)"
              @search="onSearchDepartment"
              :filterable="false"
            >
              <template v-slot:no-options>
                Nhập tên đơn vị
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số phiếu">
            <b-form-input
              v-model="material_requistion.number"
              type="number"
              required
              placeholder="Nhập số phiếu"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày lập">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              v-model="material_requistion.date"
            />
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
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr
                v-for="(row,
                index) in material_requistion.material_requistion_details"
                :key="row.id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ row.product_code }}</td>
                <td>
                  <v-select
                    :options="productList"
                    @search="onSearchProduct"
                    :filterable="false"
                    @input="product => updateProduct(row, product)"
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
                      Chọn sản phẩm
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
      material_requistion: {
        department_id: "",
        number: "",
        material_requistion_details: [
          {
            product_code: "",
            product_name: "",
            product_id: "",
            quantity: null,
            note: ""
          }
        ]
      },
      newItem: {
        product_code: "",
        product_name: "",
        product_id: "",
        quantity: null,
        note: ""
      },
      departmentList: [],
      productList: []
    };
  },
  created() {
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, "0");
    let mm = String(today.getMonth() + 1).padStart(2, "0");
    let yyyy = today.getFullYear();

    this.material_requistion.date = dd + "/" + mm + "/" + yyyy;

    if (this.$route.params.id) {
      this.getMaterialRequistions(this.$route.params.id);
    }
  },
  computed: {
    count() {
      return this.material_requistion.material_requistion_details.length;
    }
  },
  methods: {
    getMaterialRequistions(id) {
      axios.get(`api/material_requistions/${id}`).then(res => {
        this.material_requistion = res.data.data;
      });
    },
    addRow() {
      this.material_requistion.material_requistion_details.push({
        ...this.newItem
      });
    },
    onSubmit() {
      axios
        .post("/api/material_requistions", this.material_requistion)
        .then(res => {
          if (res.data.status === "success")
            this.$router.push("/material_requistions/" + res.data.id);
        })
        .catch(error => {
          console.log(this.material_requistion);
        });
    },
    onSearchDepartment(search, loading) {
      loading(true);
      this.searchDepartment(loading, search, this);
    },
    searchDepartment: _.debounce((loading, search, vm) => {
      axios.get(`api/departments/search?q=${encodeURI(search)}`).then(res => {
        vm.departmentList = _.map(res.data, value => {
          return { label: value.name, code: value.id };
        });
        loading(false);
      });
    }, 350),
    onSearchProduct(search, loading) {
      loading(true);
      this.searchProduct(loading, search, this);
    },
    searchProduct: _.debounce((loading, search, vm) => {
      axios
        .get(
          `api/products/search?q=${encodeURI(search)}&department_id=${
            vm.material_requistion.department_id
          }`
        )
        .then(res => {
          vm.productList = _.map(res.data, value => {
            return {
              label: value.name,
              id: value.id,
              product: value.sell_product,
              code: value.code
            };
          });
          loading(false);
        });
    }, 350),
    updateDepartment(department) {
      this.material_requistion.department_name = department.label;
      this.material_requistion.department_id = department.code;
    },
    updateProduct(row, product) {
      row.product_id = product.id;
      row.selling_product = product.product;
      row.product_name = product.label;
      row.product_code = product.code;
    },
    deleteRow(index) {
      this.material_requistion.material_requistion_details.splice(index, 1);
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
