<template>
  <form class="w-full">
    <label
      for=" default-search"
      class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300"
      >Ara</label
    >
    <div class="relative">
      <div
        class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
      >
        <svg
          aria-hidden="true"
          class="w-5 h-5 text-gray-500 dark:text-gray-400"
          fill="none"
          stroke="currentColor"
          viewbox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          ></path>
        </svg>
      </div>
      <input
        type="search"
        id="default-search"
        class="block p-4 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Kategori Ara, Ürün..."
        required
        v-on:keyup="searchData"
      />
    </div>

    <div
      v-if="
        search.length > 0 &&
        (data.products.length > 0 || data.categories.length > 0)
      "
      class="w-full mt-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
    >
      <div>
        <a
          v-for="item in data.products"
          :key="item.id"
          href=""
          aria-current="true"
          class="block py-2 px-4 w-full border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white"
        >
          <span class="text-sm text-green-500">Ürün > </span>
          {{ item.name }}
        </a>
      </div>

      <hr />
      <div>
        <a
          v-for="item in data.categories"
          :key="item.id"
          v-bind="{ href: '/tum-urunler/' + item.id }"
          class="block py-2 px-4 w-full border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white"
        >
          <span class="text-sm text-green-500">Kategori > </span>
          {{ item.name }}
        </a>
      </div>
    </div>
  </form>
</template>

<script>
import Axios from 'axios';
export default {
  props: {
    searchUrl: String,
  },
  data: () => ({
    search: '',
    data: [],
  }),
  methods: {
    searchData(event) {
      Axios.post(this.searchUrl, { search: event.target.value }).then(
        (response) => {
          this.data = response.data;
          this.search = event.target.value;
          console.log(response.data);
        }
      );
    },
  },
};
</script>
