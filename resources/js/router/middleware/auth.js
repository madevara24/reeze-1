export default function auth ({ next, store }){
    if(!store.getters.getIsLogin){
        return next({
           name: 'login'
        })
    }
   
    return next()
}