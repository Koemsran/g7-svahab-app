<template>
  <div class="title">
    <div v-if="loading" class="loading-spinner">Loading...</div>
    <div v-else>
      <h4>Let shopping with our products here!</h4>
      <div class="container-card card-me p-5">
        <div class="card-wrapper" v-for="(product, index) in uniqueProductsByCategory" :key="product.id"  >
          <div class="card h-104 shadow-sm position-relative">
            <div class="image-container">
              <div class="flex justify-between absolute w-full mt-1">
                <div class="discount-banner">
                  <span v-if="product.discounts.length > 0" class="discount-text bg-orange-500 px-4 py-2 rounded-md text-white text-2xl">
                    {{ roundedDiscount(product.discounts[0].discount) }}% OFF
                  </span>
                </div>
                <div class="cart-icon">
                  <div class="relative inline-block text-left">
                    <div class="shop-icon" @click="addToCart(product)">
                      <i class="fa fa-shopping-cart"></i>
                    </div>
                  </div>
                  <div class="favorite-icon" @click="toggleFavorite(product)">
                    <i :class="['fa', product.isFavorite ? 'fa-heart' : 'fa-heart-o']"></i>
                  </div>
                </div>
              </div>
              <router-link :to="'/product/detail/' + product.id">
                <img :src="getImageUrl(product.image)" class="card-img-top" alt="Product Image" />
              </router-link>
            </div>
            <div class="text-start p-4">
              <h5 class="card-title">{{ product.name }}</h5>
              <p class="card-text text-danger fw-bold" v-if="product.discounts.length > 0">
                <span class="text-danger fw-bold" style="text-decoration: line-through">
                  ${{ product.price }}
                </span>
                <span class="text-success ms-2">${{ product.discounted_price }}</span>
              </p>
              <p class="card-text text-danger fw-bold" v-else>${{ product.price }}</p>
              <p class="card-text mt-2 mb-2">{{ product.description }}</p>
              <div class="group mt-3">
                <router-link :to="{ path: '/category/show/' + product.category_id, query: { user: user.id } }" class="button me-2 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" >
                  See More
                </router-link>
                <router-link :to="{ path: '/product/detail/' + product.id, query: { customer: user.id } }" class="button inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-orange-500 rounded-lg hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 dark:bg-orange-400 dark:hover:bg-orange-500 dark:focus:ring-orange-600" style="margin-left: auto" >
                  Buy Now
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// ======================= Import Necessary Files and Libraries =======================
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  // ======================= Component Props =======================
  props: {
    user: Object
  },
  // ======================= Component Data =======================
  data() {
    return {
      products: [],
      uniqueProductsByCategory: [],
      loading: true,
    }
  },
  // ======================= Lifecycle Hooks =======================
  created() {
    this.fetchProducts()
    this.startPolling(); 
  },
  // ======================= Component Methods =======================
  methods: {
    // ======================= Fetch Products =======================
    async fetchProducts() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/product/list')

        const allProducts = response.data.all_products
        const products = allProducts.map((product) => ({
          ...product,
          colors: product.colors.map((color) => color.name),
          sizes: product.sizes.map((size) => size.name),
          discounted_price: this.calculateDiscountedPrice(product)
        }))

        this.products = products
        this.filterUniqueProductsByCategory()
      } catch (error) {
        console.error('Error fetching products:', error)
      } finally {
        this.loading = false; // Ensure loading is set to false in both success and error scenarios
      }
    },

    // ======================= Filter Unique Products by Category =======================
    filterUniqueProductsByCategory() {
      const uniqueProducts = []
      const categoryIds = new Set()

      this.products.forEach((product) => {
        if (!categoryIds.has(product.category_id)) {
          uniqueProducts.push(product)
          categoryIds.add(product.category_id)
        }
      })

      this.uniqueProductsByCategory = uniqueProducts
    },

    // ======================= Get Image URL =======================
    getImageUrl(imagePath) {
      return `http://127.0.0.1:8000/storage/${imagePath}`
    },

    // ======================= Toggle Favorite =======================
    toggleFavorite(product) {
      product.isFavorite = !product.isFavorite
    },

    // ======================= Calculate Discounted Price =======================
    calculateDiscountedPrice(product) {
      if (product.discounts.length > 0) {
        const discount = product.discounts[0]
        const discountedPrice = product.price - product.price * (discount.discount / 100)
        return Math.round(discountedPrice).toString() // Ensure price is rounded and converted to string
      }
      return product.price // Return the original price if no discount
    },

    roundedDiscount(discount) {
      return Math.round(discount);
    },

    // ======================= Add to Cart =======================
    addToCart(product) {
      axios
        .post(
          'http://127.0.0.1:8000/api/cart/create',
          {
            product_id: product.id,
            quantity: 1
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('access_token')}`
            }
          }
        )
        .then((response) => {
          console.log('Product added to cart:', product)
          // Display SweetAlert for success
          Swal.fire({
            position: 'center',
            icon: 'success',
            html: `<span style="font-size: 26px; font-weight: bold;">${product.name} added to cart successfully</span>`,
            showConfirmButton: false,
            timer: 1000
          })
        })
        .catch((error) => {
          console.error('Error adding to cart:', error)
          // Display SweetAlert for error
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Error adding to cart. Please try again.',
            showConfirmButton: false,
            timer: 1500
          })
        })
    },

    startPolling() {
      setInterval(() => this.fetchProducts(), 10000); // Poll every 10 seconds
    }
  }
}
</script>



<style scoped>
.container-card {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  align-items: flex-start;
  margin-left: 25px;
}

.card-wrapper {
  width: 23%;
  margin: 10px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
}

.card-wrapper:hover {
  transform: translateY(-5px);
}

.image-container {
  height: 240px;
  display: flex;
  justify-content: start;
  overflow: hidden;
  background-color: #f0f0f0;
}

.image-container {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
}

.button {
  display: inline-block;
  padding: 8px 16px;
  font-size: 14px;
  font-weight: 500;
  text-align: center;
  color: white;
  border-radius: 4px;
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.card {
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.card-body {
  background-color: #f8f9fa;
}

.rating .star {
  font-size: 20px;
}

.text-warning {
  color: #ffcc00;
}

.title h4 {
  text-align: center;
  margin-top: 20px;
  margin-bottom: 20px;
  font-size: 30px;
  color: #000;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
}

.title .text-s {
  text-align: center;
  margin-top: 20px;
  margin-bottom: 20px;
  font-size: 20px;
  color: #000;
}

button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.cart-icon {
  display: flex;
  justify-content: end;
  font-size: 25px;
  color: orange;
  border-radius: 50%;
  margin-right: 10px;
}

.shop-icon {
  margin-right: 10px;
  color: rgb(248, 163, 5);
}

.favorite-icon .fa-heart {
  color: #ff0000;
}

@media (max-width: 768px) {
  .card-wrapper {
    width: 100%;
  }
}
</style>
