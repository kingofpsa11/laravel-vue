import Vue from "vue";
import Router from "vue-router";

import Layout from "./components/Layout/Layout";
import Login from "./pages/Login/Login";
import ErrorPage from "./pages/Error/Error";
// Core
import ContractCreate from "./pages/Contracts/Create";
import ContractList from "./pages/Contracts/List";

import ProductCreate from "./pages/Products/Create";
import ProductList from "./pages/Products/List";

import PriceCreate from "./pages/Prices/Create";
import PriceList from "./pages/Prices/List";

import AssignmentCreate from "./pages/Assignments/Create";
import AssignmentList from "./pages/Assignments/List";
// Tables
import TablesBasicPage from "./pages/Tables/Basic";

// Maps
import GoogleMapPage from "./pages/Maps/Google";

// Main
import AnalyticsPage from "./pages/Dashboard/Dashboard";

// Charts
import ChartsPage from "./pages/Charts/Charts";

// Ui
import IconsPage from "./pages/Icons/Icons";
import NotificationsPage from "./pages/Notifications/Notifications";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/login",
      name: "Login",
      component: Login
    },
    {
      path: "/error",
      name: "Error",
      component: ErrorPage
    },
    {
      path: "/",
      name: "Layout",
      component: Layout,
      children: [
        {
          path: "dashboard",
          name: "AnalyticsPage",
          component: AnalyticsPage
        },
        {
          path: "products/create",
          name: "ProductCreate",
          component: ProductCreate
        },
        {
          path: "products/list",
          name: "ProductList",
          component: ProductList
        },
        {
          path: "prices/create",
          name: "PriceCreate",
          component: PriceCreate
        },
        {
          path: "prices/list",
          name: "Price",
          component: PriceList
        },
        {
          path: "contracts/create",
          name: "ContractCreate",
          component: ContractCreate
        },
        {
          path: "contracts/list",
          name: "ContractList",
          component: ContractList
        },
        {
          path: "assignments/create",
          name: "AssignmentCreate",
          component: AssignmentCreate
        },
        {
          path: "assignments/list",
          name: "AssignmentList",
          component: AssignmentList
        },
        {
          path: "components/icons",
          name: "IconsPage",
          component: IconsPage
        },
        {
          path: "notifications",
          name: "NotificationsPage",
          component: NotificationsPage
        },
        {
          path: "components/charts",
          name: "ChartsPage",
          component: ChartsPage
        },
        {
          path: "tables",
          name: "TablesBasicPage",
          component: TablesBasicPage
        },
        {
          path: "components/maps",
          name: "GoogleMapPage",
          component: GoogleMapPage
        }
      ]
    }
  ]
});
