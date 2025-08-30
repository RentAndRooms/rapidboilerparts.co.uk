import { ref } from 'vue'

const cart = ref([])
const showCart = ref(false)

export function useCart() {
    function addToCart(item) {
        const found = cart.value.find(i => i.id === item.id)
        if (found) {
            found.qty += 1
        } else {
            cart.value.push({ ...item, qty: 1 })
        }
    }

    function removeFromCart(id) {
        const idx = cart.value.findIndex(i => i.id === id)
        if (idx !== -1) {
            cart.value.splice(idx, 1)
        }
    }

    function updateQuantity(id, qty) {
        const item = cart.value.find(i => i.id === id)
        if (item) {
            item.qty = qty
            if (item.qty <= 0) {
                removeFromCart(id)
            }
        }
    }

    function clearCart() {
        cart.value = []
    }

    function getTotal() {
        return cart.value.reduce((sum, item) => sum + (item.price * item.qty), 0)
    }

    function getItemCount() {
        return cart.value.reduce((sum, item) => sum + item.qty, 0)
    }

    return {
        cart,
        showCart,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        getTotal,
        getItemCount
    }
}

export default useCart
